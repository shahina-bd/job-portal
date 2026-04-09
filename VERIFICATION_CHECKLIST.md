# Job Portal - Verification Checklist

Use this checklist to verify your Laravel Job Portal installation is complete and working correctly.

## ✅ Initial Setup & Installation

- [ ] Cloned or downloaded project from repository
- [ ] Ran `composer install` (all dependencies installed)
- [ ] Ran `npm install` (Node packages installed)
- [ ] Created `.env` file from `.env.example`
- [ ] Ran `php artisan key:generate` (APP_KEY generated)
- [ ] Updated database credentials in `.env`
- [ ] Ran `php artisan migrate` (all 15 migrations completed successfully)

**Verification Command:**
```bash
php artisan migrate:status
# Should show: Table "migrations" created successfully, all migrations Up
```

---

## ✅ Database Schema Verification

- [ ] `users` table exists with columns: id, username, email, phone, password, user_type, is_active, timestamps
- [ ] `companies` table exists with foreign key to users
- [ ] `job_posts` table exists with foreign keys to users and categories
- [ ] `job_applications` table exists with foreign keys to job_posts and users
- [ ] All profile tables exist: educations, experiences, skills, trainings, documents
- [ ] Support tables exist: categories, countries, addresses, company_contacts, candidate_selections
- [ ] All foreign keys have cascade delete rules
- [ ] Indexes created on frequently queried columns (user_id, job_post_id, status)

**Verification Command:**
```bash
php artisan db:table users
php artisan db:table job_posts
php artisan db:table job_applications
# Verify all columns and relationships are present
```

---

## ✅ Models & Relationships

- [ ] `User` model has relationships: hasOne(Company), hasMany(JobPost, JobApplication, Education, ...)
- [ ] `Company` model has: belongsTo(User), hasMany(JobPost)
- [ ] `JobPost` model has: belongsTo(User, Category), hasMany(JobApplication)
- [ ] `JobApplication` model has correct foreign keys and eager loading
- [ ] All models have proper fillable arrays defined
- [ ] Cascading delete setup verified in model relationships

**Verification Command:**
```php
php artisan tinker
# Then test model relationships:
$user = User::with('company', 'jobPosts', 'educations')->find(1);
$user->company; // Should work if exists
$user->jobPosts->count(); // Should show number of posts
```

---

## ✅ Controllers & Actions

### Authentication
- [ ] `RegisterController` created with validation for: username, email, password, user_type
- [ ] Password hashing works (uses Hash::make())
- [ ] User registration creates record with correct user_type

**Test:**
```bash
POST /register
{
  "username": "testuser",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "user_type": "employee"
}
# Should return user data with id
```

### Profile Management
- [ ] `ProfileController` created with method: show(), educations(), experiences(), skills(), etc.
- [ ] Get full profile returns all relationships: company, address, educations, experiences, skills
- [ ] Profile methods have proper authorization checks

**Test:**
```bash
GET /profile
# Should return complete profile with all nested relationships
```

### Employee Controllers
- [ ] `EducationController` created with: store(), show(), update(), delete()
- [ ] `ExperienceController` created with same CRUD endpoints
- [ ] `SkillController` created with same CRUD endpoints
- [ ] All have user ownership authorization checks (403 if not owner)

**Test:**
```bash
POST /education
{
  "degree": "Bachelor",
  "institution": "University",
  "field_of_study": "CS",
  "start_date": "2020-01-01",
  "end_date": "2024-01-01"
}
# Should create and return record with user_id = auth()->id()
```

### Employer Controllers
- [ ] `CompanyController` created with: store(), show(), update()
- [ ] `JobPostController` created with: index(), store(), show(), update(), delete()
- [ ] Company creation sets user_id to authenticated user
- [ ] Job posts have eager loading: with('employer', 'category', 'applications')
- [ ] Only employer can edit/delete their own company and jobs

**Test:**
```bash
POST /company
{
  "name": "My Company",
  "company_type": "Startup",
  "country_id": 1
}
# Should create with user_id from auth()->id()
```

### Job Application
- [ ] `JobApplicationController` created with: store(), show(), destroy()
- [ ] Application creation checks for duplicates (409 Conflict if already applied)
- [ ] `SendJobApplicationMail` job dispatched on application creation
- [ ] Email notification queued for background processing

**Test:**
```bash
POST /jobs/1/apply
# First application: 201 Created
POST /jobs/1/apply
# Second application to same job: 409 Conflict
```

### Admin Controller
- [ ] `AdminController` created with 40+ methods
- [ ] dashboard() returns metrics: total_users, total_jobs, total_applications
- [ ] users() returns paginated users with filters
- [ ] jobs() returns paginated jobs with filters
- [ ] applications() returns paginated applications with filters
- [ ] categories() CRUD endpoints working
- [ ] countries() CRUD endpoints working
- [ ] analytics() returns graphs: registrations, applications, jobs by category, top employers

**Test:**
```bash
GET /admin/dashboard
# Should return JSON with user/job/application metrics

GET /admin/users?user_type=employee&page=1
# Should return paginated employee list with company relation
```

---

## ✅ Middleware & Authorization

- [ ] `RoleMiddleware` created in `app/Http/Middleware/`

**File: `/app/Http/Middleware/RoleMiddleware.php`**
```php
public function handle(Request $request, Closure $next, $role)
{
    if (!auth()->check() || auth()->user()->user_type !== $role) {
        abort(403);
    }
    return $next($request);
}
```

- [ ] Registered in `app/Http/Kernel.php` under `protected $routeMiddleware`
- [ ] Employer routes protected with `middleware('role:employer')`
- [ ] Employee routes protected with `middleware('role:employee')`
- [ ] Admin routes protected with `middleware('role:admin')`

**Verification:**
```bash
# As employee, try to access employer route: should 403
GET /company (as employee) → 403 Forbidden

# As employer, try to access admin route: should 403
GET /admin/users (as employer) → 403 Forbidden
```

---

## ✅ Routes & API Endpoints

**File: `/routes/web.php` should have:**
- [ ] Public route for registration: `POST /register`
- [ ] Public index route: `GET /`
- [ ] Profile routes (all authenticated):
  - [ ] `GET /profile`
  - [ ] `PATCH /profile`
  - [ ] `GET /profile/address`, `PATCH /profile/address`
  - [ ] `GET /profile/educations`, `GET /profile/experiences`, `GET /profile/skills`
  - [ ] `GET /profile/company`, `GET /profile/jobs`, `GET /profile/applications`

- [ ] Employee routes (authenticated + role:employee):
  - [ ] `POST /education`, `GET /education/{id}`, `PATCH /education/{id}`, `DELETE /education/{id}`
  - [ ] Similar for `/experience`, `/skills`, `/trainings`, `/documents`
  - [ ] `GET /jobs` (browse jobs)
  - [ ] `POST /jobs/{id}/apply` (apply for job)

- [ ] Employer routes (authenticated + role:employer):
  - [ ] `POST /company`, `GET /company`, `PATCH /company/{id}`
  - [ ] `POST /jobs`, `GET /jobs`, `PATCH /jobs/{id}`, `DELETE /jobs/{id}`

- [ ] Admin routes (authenticated + role:admin):
  - [ ] `GET /admin/dashboard`
  - [ ] `GET/POST/PATCH/DELETE /admin/users`, `GET /admin/users/{id}`
  - [ ] `GET/POST/PATCH/DELETE /admin/jobs`, `GET /admin/jobs/{id}`
  - [ ] `GET/POST/PATCH /admin/applications`, `GET /admin/applications/{id}`
  - [ ] `GET/POST/PATCH/DELETE /admin/categories`
  - [ ] `GET/POST/PATCH/DELETE /admin/countries`
  - [ ] `GET /admin/analytics`

**Verification Command:**
```bash
php artisan route:list
# Should show all routes with their middleware and controllers
```

---

## ✅ Queue System Setup

- [ ] `jobs` table created (created by migration 0001_01_01_000002_create_jobs_table)
- [ ] `SendJobApplicationMail` job created in `app/Jobs/`
- [ ] `SendJobAlertMail` job created in `app/Jobs/`
- [ ] Mail classes created: `JobApplicationNotification`, `JobAlertNotification`
- [ ] Queue connection set to 'database' in `.env`

**File: `.env` verification:**
```
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

- [ ] Queue worker can be started: `php artisan queue:work`
- [ ] Horizon available: `php artisan horizon` (starts at http://localhost:8000/horizon)
- [ ] Failed jobs can be retried: `php artisan queue:retry all`

**Test Queueing:**
```bash
# Start queue worker in separate terminal
php artisan queue:work

# Create a job application
POST /jobs/1/apply (as employee)

# Check jobs table for queued job
SELECT * FROM jobs WHERE queue = 'default' AND status = 'queued';

# Check command output shows job processing
# Output should show: Processing SendJobApplicationMail job
```

---

## ✅ Email Configuration

- [ ] Mail template `resources/views/emails/job-application.blade.php` exists
- [ ] Mail template `resources/views/emails/job-alert.blade.php` exists
- [ ] Mail classes extend `Mailable` and implement `Buildable`

**Development Testing (using Log mailer):**
```bash
# Check storage/logs/laravel.log for email contents
tail -f storage/logs/laravel.log

# Create a job application and check log
# You should see email HTML in the log
```

**Production Configuration (update in `.env`):**
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jobportal.com
MAIL_FROM_NAME="Job Portal"
```

---

## ✅ ORM Query Optimization

- [ ] `JobPortalQueries` class created with static methods
- [ ] All controller queries use eager loading: `.with(['relation1', 'relation2'])`
- [ ] N+1 prevention verified in major queries:
  - [ ] JobPost index loads: employer, category, applications
  - [ ] JobApplication load: job, employee, employer
  - [ ] User profile loads: company, address, educations, experiences, skills, etc.

**Verification Command:**
```bash
php artisan tinker
# Check query performance
$jobs = JobPost::with(['employer', 'category', 'applications'])->get();
# Should execute only 4 queries (1 jobs, 1 employers, 1 categories, 1 applications)
# Without with(): would execute 2+ queries per job (N+1 problem)
```

---

## ✅ Testing & Sample Data

### Create Sample Users
```bash
php artisan tinker

# Create employee
User::create([
    'username' => 'john_employee',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'user_type' => 'employee',
    'is_active' => true
]);

# Create employer
User::create([
    'username' => 'hr_manager',
    'email' => 'hr@company.com',
    'password' => Hash::make('password123'),
    'user_type' => 'employer',
    'is_active' => true
]);

# Create admin (required for admin routes testing)
User::create([
    'username' => 'admin',
    'email' => 'admin@jobportal.com',
    'password' => Hash::make('password123'),
    'user_type' => 'admin',
    'is_active' => true
]);
```

### Verify Complete Workflow
```
1. ✅ Register employee user
2. ✅ Register employer user
3. ✅ Create company (as employer)
4. ✅ Post job (as employer)
5. ✅ Browse jobs (as employee)
6. ✅ Apply for job (as employee) → Queued email job created
7. ✅ Check applications (as admin)
```

---

## ✅ Documentation

- [ ] `QUICKSTART.md` - Setup and installation guide
- [ ] `API_EXAMPLES.md` - Request/response examples for all endpoints
- [ ] `SYSTEM_FLOW.md` - Complete workflow diagrams and system architecture
- [ ] `HORIZON_SETUP.md` - Queue and Horizon documentation
- [ ] `postman_collection.json` - Importable Postman collection

---

## ✅ Performance Checks

- [ ] Database indexes on foreign keys: user_id, job_post_id, category_id, country_id
- [ ] Eager loading used in all controllers to prevent N+1
- [ ] Pagination implemented on list endpoints (default 50 per page)
- [ ] Query counts verified using Laravel Debugbar or Query logging

**Check Database Indexes:**
```bash
php artisan tinker
Schema::getIndexes('users'); // Check if indexes exist
Schema::getIndexes('job_posts');
```

---

## ✅ Security Verification

- [ ] Passwords hashed with bcrypt: `Hash::make($password)`
- [ ] CSRF protection on all POST/PATCH/DELETE requests
- [ ] User ownership verified in all update/delete operations
- [ ] Role-based access control enforced at route level
- [ ] Input validation on all endpoints
- [ ] Sensitive data not exposed in responses (no password hashes in JSON)

**Test Authorization:**
```bash
# Create 2 employee users, then as user 1, try to delete user 2's education
DELETE /education/[user_2_education_id] (as user_1)
# Should return 403 Forbidden
```

---

## ✅ Postman Testing

- [ ] Import `postman_collection.json` into Postman
- [ ] Set environment variables:
  - [ ] `base_url` = http://localhost:8000
  - [ ] `user_token` = [token from employee login]
  - [ ] `employer_token` = [token from employer login]
  - [ ] `admin_token` = [token from admin login]

- [ ] Run complete test scenario:
  1. Register → Get user ID
  2. Create profile data
  3. Create company (employer)
  4. Post job
  5. Apply for job
  6. Check applications
  7. Admin dashboard

---

## ✅ Final Integration Check

Run this complete test scenario:

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start queue worker
php artisan queue:work

# Terminal 3: Test with Postman or curl
# Use postman_collection.json for all endpoints

# Or test with curl:
curl -X POST http://localhost:8000/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "user_type": "employee"
  }'
```

---

## ✅ Troubleshooting

### Issue: "Middleware [controller path] not found"
**Solution:** Register RoleMiddleware in `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

### Issue: "Class not found" errors
**Solution:** Run `composer dump-autoload` to refresh autoloader

### Issue: Queue jobs not processing
**Solution:** 
1. Ensure `QUEUE_CONNECTION=database` in `.env`
2. Ensure `jobs` table exists (created by migrations)
3. Start queue worker: `php artisan queue:work`
4. Check failed jobs: `php artisan queue:failed-table` then `php artisan queue:failed`

### Issue: Email not sending
**Solution:**
1. For development, use `MAIL_MAILER=log` and check `storage/logs/laravel.log`
2. For production, configure SMTP in `.env` with valid credentials
3. Check that mail job was queued: `SELECT * FROM jobs;`

### Issue: "Cannot apply to job" returns 409
**Solution:** This is correct behavior - user has already applied. Error message should be clear in response.

---

## 🎉 Completion Confirmation

When all items above are checked, your Job Portal is:
- ✅ Fully installed and configured
- ✅ Database schema complete with all relationships
- ✅ All controllers implemented with business logic
- ✅ Authorization and role-based access control working
- ✅ Queue system operational
- ✅ Ready for testing and deployment

**Next Steps:**
1. Test all workflows end-to-end
2. Create database seeders for sample data (optional)
3. Write feature tests (optional)
4. Deploy to production with proper environment configuration
5. Set up monitoring and logging
6. Configure backup strategy

---

**Last Updated:** January 2024
**System Version:** Laravel 11 + Vue 3 + Inertia.js
**Status:** Production Ready ✅
