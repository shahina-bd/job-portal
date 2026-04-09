# Job Portal - Quick Start Guide

## 🚀 Installation & Setup

### 1. Clone & Install
```bash
cd job-portal
composer install
npm install
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
MAIL_MAILER=log
```

### 3. Database Setup
```bash
php artisan migrate
```

This creates all tables including:
- users, companies, job_posts, job_applications
- categories, countries, addresses
- educations, experiences, skills, trainings, documents
- candidate_selections, company_contacts

### 4. Seed Initial Data (Optional)
```bash
php artisan db:seed
```

Creates sample:
- Categories (Technology, Finance, Healthcare, etc.)
- Countries (USA, Canada, UK, etc.)

## 📋 User Roles Setup

### Create Admin User
```php
// In tinker: php artisan tinker
User::create([
    'username' => 'admin',
    'email' => 'admin@jobportal.com',
    'password' => Hash::make('password'),
    'user_type' => 'admin',
    'is_active' => true,
]);
```

### Create Sample Users
```bash
# Employee user
POST /register
{
  "username": "john_employee",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "user_type": "employee"
}

# Employer user
POST /register
{
  "username": "company_hr",
  "email": "hr@company.com",
  "password": "password123",
  "password_confirmation": "password123",
  "user_type": "employer"
}
```

## 🔄 System Workflows

### EMPLOYER FLOW
```
1. Register (POST /register)
   ↓
2. Create Company (POST /company)
   ├─ name (required)
   ├─ company_type (optional)
   ├─ website_url (optional)
   └─ country_id (optional)
   ↓
3. Post Job (POST /jobs)
   ├─ title, description, requirements (required)
   ├─ category_id, job_type (required)
   ├─ salary, currency (optional)
   └─ publish_date, end_date (required)
   ↓
4. View Job Applications (GET /admin/applications or /profile/jobs)
   ↓
5. Manage Applications (PATCH /admin/applications/{id})
   └─ Update status: pending → reviewed → selected → hired
```

### EMPLOYEE FLOW
```
1. Register (POST /register)
   ↓
2. Build Profile (POST /education, /experience, /skills)
   ├─ Education: degree, institution, dates
   ├─ Experience: title, company, current job flag
   └─ Skills: name, level (Beginner/Intermediate/Expert)
   ↓
3. Browse Jobs (GET /jobs)
   ├─ Filter by: category, type, search
   └─ View job details (GET /jobs/{id})
   ↓
4. Apply for Job (POST /jobs/{id}/apply)
   └─ Trigger: SendJobApplicationMail (queued)
   ↓
5. Track Applications (GET /profile/applications)
   └─ View status: pending, reviewed, selected, rejected, hired
```

### ADMIN FLOW
```
1. Login with admin account (user_type = 'admin')
   ↓
2. Dashboard (GET /admin/dashboard)
   ├─ Total users, jobs, applications metrics
   └─ Today's registrations
   ↓
3. Manage Users (GET /admin/users)
   ├─ Suspend/activate user
   ├─ Change user role
   └─ Delete user (cascade delete related data)
   ↓
4. Manage Jobs (GET /admin/jobs)
   ├─ Edit job status
   ├─ Close/reopen jobs
   └─ Delete job with cascade
   ↓
5. Manage Applications (GET /admin/applications)
   ├─ View application details
   └─ Update application status
   ↓
6. Manage Master Data
   ├─ Categories (GET/POST/PATCH/DELETE /admin/categories)
   └─ Countries (GET/POST/PATCH/DELETE /admin/countries)
   ↓
7. Analytics (GET /admin/analytics)
   ├─ Registrations by date
   ├─ Applications by date
   ├─ Jobs by category
   └─ Top employers & most applied jobs
```

## 📡 API Endpoints

### Authentication
```
POST /register          - Register new user (email, username, password, user_type)
```

### Profile Management (All authenticated users)
```
GET    /profile                  - Get full profile with all relations
PATCH  /profile                  - Update basic info (email, phone)
GET    /profile/educations       - Get all education records
GET    /profile/experiences      - Get all experience records
GET    /profile/skills           - Get all skills
GET    /profile/trainings        - Get all trainings
GET    /profile/documents        - Get all documents
GET    /profile/address          - Get address
PATCH  /profile/address          - Create/update address
GET    /profile/company          - [Employer] Get company (404 if none)
GET    /profile/jobs             - [Employer] Get all posted jobs
GET    /profile/applications     - [Employee] Get all applications
```

### Employee Profile (Employees only)
```
POST   /education/{id}           - Create education record
GET    /education/{id}           - Get education record
PATCH  /education/{id}           - Update education record
DELETE /education/{id}           - Delete education record

POST   /experience               - Create experience record
GET    /experience/{id}          - Get experience record
PATCH  /experience/{id}          - Update experience record
DELETE /experience/{id}          - Delete experience record

POST   /skills                   - Create skill
GET    /skills/{id}              - Get skill
PATCH  /skills/{id}              - Update skill
DELETE /skills/{id}              - Delete skill
```

### Job Management (Employees & Employers)
```
GET    /jobs                     - List all active jobs (with filters)
GET    /jobs/{id}                - View job details
POST   /jobs/{id}/apply          - Apply for job (employees only)
```

### Job Management (Employers only)
```
POST   /jobs                     - Create new job
PATCH  /jobs/{id}                - Edit job
DELETE /jobs/{id}                - Delete job
```

### Company (Employers only)
```
POST   /company                  - Create company
GET    /company                  - Get company
PATCH  /company                  - Update company
```

### Applications (Shared)
```
GET    /applications/{id}        - View application details
DELETE /applications/{id}        - Withdraw application (employee) | Delete (admin)
```

### Admin Only
```
GET    /admin/dashboard          - Dashboard with metrics
GET    /admin/users              - List users (paginated)
GET    /admin/users/{id}         - View user with all relations
PATCH  /admin/users/{id}         - Update user (is_active, user_type, etc)
DELETE /admin/users/{id}         - Delete user (cascade)

GET    /admin/jobs               - List jobs (paginated)
GET    /admin/jobs/{id}          - View job with applications
PATCH  /admin/jobs/{id}          - Update job
DELETE /admin/jobs/{id}          - Delete job

GET    /admin/applications       - List applications (paginated)
GET    /admin/applications/{id}  - View application with applicant profile
PATCH  /admin/applications/{id}  - Update application status

GET    /admin/categories         - List categories
POST   /admin/categories         - Create category
PATCH  /admin/categories/{id}    - Update category
DELETE /admin/categories/{id}    - Delete category (only if no jobs)

GET    /admin/countries          - List countries
POST   /admin/countries          - Create country
PATCH  /admin/countries/{id}     - Update country
DELETE /admin/countries/{id}     - Delete country

GET    /admin/analytics          - Analytics dashboard
```

## ⚙️ Middleware & Authorization

### Registered Routes
```
RoleMiddleware → checks auth()->user()->user_type against required role
Supported roles: 'employee', 'employer', 'admin'
```

Usage in routes:
```php
Route::middleware(['auth', 'role:employer'])->group(function () {
    // Employer-only routes
});
```

## 📨 Queue System

### Start Queue Worker
```bash
# Using Horizon (with dashboard)
php artisan horizon

# Using basic queue worker
php artisan queue:work
```

### Available Jobs
1. **SendJobApplicationMail** - Notifies employer when employee applies
2. **SendJobAlertMail** - Notifies employees of new matching jobs

### Email Configuration
Current setup uses **Log mailer** (development):
- Emails logged to `storage/logs/laravel.log`

For production, update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 🗄️ Database Schema

### Core User Tables
```
users
├── id, username, email, phone
├── password (hashed), user_type (admin|employee|employer)
├── is_active (boolean default true)
└── timestamps

companies
├── id, user_id (1:1), country_id
├── name, company_type, website_url, company_size
├── company_description, is_active

addresses
├── id, user_id, country_id
├── address_type, address_line, city, state
└── timestamps
```

### Job Management
```
categories
├── id, name (unique), description
├── status (default true)
└── timestamps

job_posts
├── id, user_id (employer), category_id
├── title, job_description, requirements
├── salary (decimal), currency, job_type (enum)
├── publish_date, end_date, status (default true)
└── timestamps

job_applications
├── id, job_post_id, employee_id, employer_id
├── status (default 'pending')
└── apply_date

candidate_selections
├── id, job_post_id, employee_id, employer_id
├── interview_date, interview_status, status
└── (no timestamps)
```

### Employee Profile
```
educations
├── id, user_id, degree, institution, field_of_study
├── start_date, end_date, description
└── timestamps

experiences
├── id, user_id, title, company
├── start_date, end_date, is_current
├── description
└── timestamps

skills
├── id, user_id, name, level, years_experience
├── is_active
└── timestamps

trainings
├── id, user_id, title, institution, certificate_url
├── start_date, end_date, description
└── timestamps

documents
├── id, user_id, name, document_type, file_path
├── expiry_date, notes
└── timestamps
```

### Support Tables
```
countries
├── id, name (unique), code (2 chars)
├── is_active
└── (no timestamps)

company_contacts
├── id, company_id, branch_name
├── person_name, phone, is_active
└── (no timestamps)
```

## 🔐 Security Best Practices

1. ✅ Passwords hashed with bcrypt
2. ✅ CSRF protection on all POST/PATCH/DELETE
3. ✅ Role-based access control (RBAC)
4. ✅ User authorization checks in models
5. ✅ Email validation & uniqueness
6. ✅ Rate limiting (recommended to implement)
7. ✅ Input validation on all endpoints
8. ✅ Queue jobs for background processing

## 📚 Additional Resources

- System Flow: See [SYSTEM_FLOW.md](SYSTEM_FLOW.md)
- Horizon Setup: See [HORIZON_SETUP.md](HORIZON_SETUP.md)
- ORM Queries: See [app/Queries/JobPortalQueries.php](app/Queries/JobPortalQueries.php)

## 🆘 Common Issues

### Queue not processing jobs
```bash
# Check if table exists
php artisan migrate

# Start queue worker
php artisan queue:work

# Check failed jobs
php artisan queue:failed
php artisan queue:retry all
```

### Middleware not working
```bash
# Ensure RoleMiddleware is registered in app/Http/Kernel.php
# Add to routeMiddleware array:
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

### Email not sending
```bash
# Check in production if mail config is correct
# For development, check storage/logs/laravel.log

# Test email in tinker:
Mail::to('test@test.com')->send(new TestMail());
```

## 📞 Support

For questions or issues, refer to:
- System documentation files
- Laravel Documentation: https://laravel.com/docs
- This codebase's inline comments and docstrings
