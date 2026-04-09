# Job Portal - Developer Quick Reference

## 🚀 Quick Start (5 Minutes)

```bash
# 1. Install dependencies
composer install && npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database
php artisan migrate

# 4. Run server (Terminal 1)
php artisan serve

# 5. Start queue worker (Terminal 2)
php artisan queue:work

# 6. Test with Postman
# Import: postman_collection.json
```

---

## 📚 Core Models & Relationships

```php
// User relationships
User::with([
    'company',           // 1:1 - Company
    'jobPosts',          // 1:M - JobPosts
    'jobApplications',   // 1:M - Applications
    'educations',        // 1:M - Educations
    'experiences',       // 1:M - Experiences
    'skills',            // 1:M - Skills
])->get();

// Company relationships
Company::with([
    'user',              // 1:1 - User (employer)
    'jobPosts',          // 1:M - Job listings
])->get();

// JobPost relationships
JobPost::with([
    'employer',          // 1:1 - User (employer)
    'category',          // 1:1 - Category
    'applications',      // 1:M - Job applications
])->get();

// JobApplication relationships
JobApplication::with([
    'job',               // 1:1 - JobPost
    'employee',          // 1:1 - User (applicant)
    'employer',          // 1:1 - User (employer)
])->get();
```

---

## 🔐 User Types & Routes

### User Types (Enum in users table):
```
'admin'    → Full system access
'employer' → Company & job management
'employee' → Profile & job applications
```

### Route Protection:
```php
Route::middleware(['auth', 'role:employee'])->group(function () {
    // Employee-only routes
});

Route::middleware(['auth', 'role:employer'])->group(function () {
    // Employer-only routes
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes
});
```

---

## 📊 Database Summary

| Table | Purpose | Key Relations |
|-------|---------|---------------|
| users | Authentication & roles | Core: has company, jobs, apps |
| companies | Employer profiles | belongs_to user; has jobs |
| job_posts | Job listings | belongs_to user+category; has apps |
| job_applications | Applications | belongs_to job+user (employee/employer) |
| educations | Employee education | belongs_to user |
| experiences | Employee work history | belongs_to user |
| skills | Employee skills | belongs_to user |
| categories | Job categories | has_many job_posts |
| countries | Country master | has_many addresses+companies |
| addresses | User addresses | belongs_to user+country |

---

## 🎯 Controller Quick Reference

| Controller | Context | Key Methods |
|-----------|---------|------------|
| RegisterController | Auth | handle registration (POST /register) |
| ProfileController | User | show(), educations(), experiences(), skills(), company(), address(), applications(), jobs() |
| CompanyController | Employer | store(), show(), update() |
| JobPostController | Employer | index(), store(), show(), update(), delete() |
| EducationController | Employee | store(), show(), update(), delete() |
| ExperienceController | Employee | store(), show(), update(), delete() |
| SkillController | Employee | store(), show(), update(), delete() |
| JobApplicationController | Employee | store(), show(), destroy() |
| AdminController | Admin | users(), jobs(), applications(), categories(), countries(), dashboard(), analytics() |

---

## 🔄 Data Flow Examples

### Employer Workflow
```
1. Register (POST /register, user_type='employer')
2. Create Company (POST /company)
3. Post Job (POST /jobs)
4. View Applications (GET /admin/applications or /profile/jobs)
5. Update Application Status (PATCH /admin/applications/{id})
```

### Employee Workflow
```
1. Register (POST /register, user_type='employee')
2. Build Profile
   - Education (POST /education)
   - Experience (POST /experience)
   - Skills (POST /skills)
   - Address (PATCH /profile/address)
3. Browse Jobs (GET /jobs?category_id=1&job_type=full-time)
4. Apply (POST /jobs/{id}/apply)
5. Track Status (GET /profile/applications)
```

### Admin Workflow
```
1. Login (user_type='admin')
2. Dashboard (GET /admin/dashboard)
3. Manage Users (GET/PATCH/DELETE /admin/users)
4. Manage Jobs (GET/PATCH/DELETE /admin/jobs)
5. Manage Applications (GET/PATCH /admin/applications)
6. Manage Master Data (categories, countries)
7. View Analytics (GET /admin/analytics)
```

---

## 🔗 Essential Endpoints

### Public
```
POST /register       - Create new user
GET  /              - Landing page
```

### Profile (All authenticated users)
```
GET    /profile                    - Full profile data
PATCH  /profile                    - Update basic info
GET    /profile/address            - Get address
PATCH  /profile/address            - Create/update address
GET    /profile/educations         - List educations
GET    /profile/experiences        - List experiences
GET    /profile/skills             - List skills
```

### Employee
```
POST   /education                  - Add education
GET    /education/{id}             - View education
PATCH  /education/{id}             - Update education
DELETE /education/{id}             - Delete education

[Similar for /experience and /skills]

GET    /jobs                       - Browse jobs (with filters)
GET    /jobs/{id}                  - Job details
POST   /jobs/{id}/apply            - Apply for job
GET    /profile/applications       - My applications
```

### Employer
```
POST   /company                    - Create company
GET    /company                    - Get my company
PATCH  /company/{id}               - Update company
GET    /profile/company            - Get company profile
GET    /profile/jobs               - My job posts

POST   /jobs                       - Post new job
GET    /jobs                       - My jobs
GET    /jobs/{id}                  - Job details
PATCH  /jobs/{id}                  - Edit job
DELETE /jobs/{id}                  - Delete job
```

### Admin
```
GET    /admin/dashboard            - Dashboard metrics
GET    /admin/users                - List users (paginated)
GET    /admin/users/{id}           - View user details
PATCH  /admin/users/{id}           - Update user
DELETE /admin/users/{id}           - Delete user (cascade)

GET    /admin/jobs                 - List jobs
GET    /admin/jobs/{id}            - Job details
PATCH  /admin/jobs/{id}            - Update job
DELETE /admin/jobs/{id}            - Delete job

GET    /admin/applications         - List applications
GET    /admin/applications/{id}    - Application details
PATCH  /admin/applications/{id}    - Update status

GET/POST/PATCH/DELETE /admin/categories
GET/POST/PATCH/DELETE /admin/countries

GET    /admin/analytics            - Analytics data
```

---

## 🔌 Queue & Jobs

### Available Jobs
```php
// When: User applies for job
dispatch(new SendJobApplicationMail($application));

// Mailable: JobApplicationNotification
// Sends to: Employer email
// Contains: Job title, applicant details, application link
```

### Start Queue Worker
```bash
# Option 1: Basic worker
php artisan queue:work

# Option 2: With Horizon dashboard (recommended)
php artisan horizon
# Access: http://localhost:8000/horizon
```

### Debug Queue
```bash
# Check pending jobs
SELECT * FROM jobs WHERE status = 'queued';

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

---

## 💾 Eager Loading Examples

```php
// Bad (N+1 problem):
$jobs = JobPost::all();
foreach ($jobs as $job) {
    echo $job->employer->name;  // Query per job!
}

// Good (eager loading):
$jobs = JobPost::with('employer', 'category')->get();
foreach ($jobs as $job) {
    echo $job->employer->name;  // No extra queries!
}

// In Controller:
public function index()
{
    $jobs = JobPost::with(['employer:id,username,email', 'category:id,name'])
        ->where('status', true)
        ->latest()
        ->paginate(15);
    
    return response()->json(['jobs' => $jobs]);
}
```

---

## 🛡️ Common Authorization Patterns

```php
// Check if authorized to update
if ($job->user_id !== auth()->id()) {
    abort(403, 'Unauthorized');
}

// Check if user type
if (auth()->user()->user_type !== 'employer') {
    abort(403, 'Only employers can post jobs');
}

// Check if user is admin or owner
if (!auth()->user()->user_type === 'admin' && $job->user_id !== auth()->id()) {
    abort(403);
}

// In middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Routes here require admin role
});
```

---

## 🧪 Testing Scenarios

### Test Employee Full Workflow
```bash
# 1. Register
POST /register
{user_type: "employee", ...}

# 2. Add education
POST /education

# 3. Add skill
POST /skills

# 4. Browse jobs
GET /jobs?category_id=1

# 5. Apply
POST /jobs/5/apply

# 6. Check status
GET /profile/applications
```

### Test Employer Full Workflow
```bash
# 1. Register
POST /register
{user_type: "employer", ...}

# 2. Create company
POST /company

# 3. Post job
POST /jobs

# 4. View applications
GET /admin/applications?job_post_id=1

# 5. Update status
PATCH /admin/applications/1
{status: "reviewed"}
```

### Test Admin Full Workflow
```bash
# 1. Admin login (user_type='admin')
GET /admin/dashboard

# 2. View all users
GET /admin/users

# 3. View all jobs
GET /admin/jobs

# 4. View all applications
GET /admin/applications

# 5. View analytics
GET /admin/analytics
```

---

## 📁 Important Files

| Path | Purpose |
|------|---------|
| `app/Models/User.php` | Core user model with relationships |
| `app/Http/Controllers/` | All business logic controllers |
| `app/Http/Middleware/RoleMiddleware.php` | RBAC enforcement |
| `app/Jobs/SendJobApplicationMail.php` | Queued email job |
| `routes/web.php` | All API route definitions |
| `database/migrations/` | Schema and relationships |
| `postman_collection.json` | Ready-to-import API tests |
| `QUICKSTART.md` | Setup guide |
| `API_EXAMPLES.md` | Request/response examples |
| `SYSTEM_FLOW.md` | Architecture & workflows |

---

## ⚙️ Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=

# Queue
QUEUE_CONNECTION=database

# Mail
MAIL_MAILER=log              # dev: logs to file
MAIL_MAILER=smtp             # production: SMTP
MAIL_FROM_ADDRESS=noreply@jobportal.com
MAIL_FROM_NAME="Job Portal"
```

---

## 🚨 Common Errors & Solutions

| Error | Solution |
|-------|----------|
| Class not found | Run `composer dump-autoload` |
| Middleware not recognized | Register in `app/Http/Kernel.php` |
| 403 Forbidden | Check user role matches route middleware |
| Queue not processing | Run `php artisan queue:work` in separate terminal |
| Email not sending | Check `.env` MAIL_MAILER setting; check `storage/logs/laravel.log` |
| 409 Conflict on apply | User already applied for this job (expected behavior) |
| N+1 queries | Add `.with()` to load relations eagerly |
| Foreign key errors | Ensure foreign key values exist and types match |

---

## 📞 Quick Commands Reference

```bash
# Development Server
php artisan serve                      # Run server on localhost:8000

# Database
php artisan migrate                    # Run all migrations
php artisan migrate:rollback           # Rollback last migration
php artisan migrate:refresh            # Drop & recreate all tables
php artisan migrate:status             # Check migration status

# Queue
php artisan queue:work                 # Process queue jobs
php artisan queue:failed               # Show failed jobs
php artisan queue:retry all            # Retry all failed jobs
php artisan queue:flush                # Clear all failed jobs

# Testing
php artisan tinker                     # Interactive shell
php artisan test                       # Run test suite
php artisan test --filter=JobPostTest  # Run specific test

# Code
php artisan make:model ModelName       # Create model
php artisan make:controller ControllerName
php artisan make:migration create_table_name
php artisan make:job JobClassName
php artisan make:mail MailClassName

# Routes
php artisan route:list                 # Show all routes
php artisan route:cache                # Cache routes (production)

# Utilities
php artisan config:cache               # Cache config
php artisan cache:clear                # Clear cache
php artisan view:clear                 # Clear compiled views
```

---

## 🎓 Learning Path

1. **Understand Database** → Read migrations in `database/migrations/`
2. **Understand Models** → Check relationships in `app/Models/`
3. **Understand Routes** → Review `routes/web.php`
4. **Understand Controllers** → Check `app/Http/Controllers/`
5. **Test API** → Use `postman_collection.json`
6. **Read Workflows** → Review `SYSTEM_FLOW.md`
7. **Read Examples** → Check `API_EXAMPLES.md`
8. **Deploy** → Follow `QUICKSTART.md`

---

## 🔍 Code Search Tips

```bash
# Find all uses of a model
grep -r "JobPost::" app/

# Find all route definitions
grep -r "Route::" routes/

# Find all data validation
grep -r "validated()" app/Http/Controllers/

# Find all eager loads
grep -r "with(" app/Http/Controllers/

# Find all authorization checks
grep -r "abort(403" app/Http/Controllers/
```

---

## Final Tips

✅ **Always eager load** relationships to prevent N+1 queries
✅ **Always validate** user input before storing
✅ **Always check authorization** before modifying data
✅ **Always queue** long-running operations (emails, exports)
✅ **Always paginate** list endpoints
✅ **Always handle errors** and return appropriate HTTP status codes
✅ **Always log** important events for debugging

---

**Keep this file handy!** Reference it frequently while developing.

**Last Updated:** January 2024
**Version:** 1.0
