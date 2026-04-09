# Job Portal - Complete System Flow

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                     Job Portal Application                       │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  Users (Employees, Employers, Admins)                           │
│    ├── Authentication (JWT/Session)                             │
│    ├── Role-Based Access Control (RBAC)                         │
│    └── Profile Management                                        │
│                                                                   │
│  Core Entities                                                   │
│    ├── Users (username, email, phone, user_type, is_active)    │
│    ├── Companies (employer profiles)                             │
│    ├── JobPosts (job listings with salary, type, dates)         │
│    ├── JobApplications (applications tracked with status)        │
│    ├── CandidateSelection (interview tracking)                   │
│    └── Employee Profiles                                         │
│        ├── Education (degree, institution, dates)               │
│        ├── Experience (title, company, dates)                    │
│        ├── Skills (name, level, years)                           │
│        ├── Training (certificates)                               │
│        └── Documents (resumes, certs)                            │
│                                                                   │
│  Support Tables                                                  │
│    ├── Categories (job categories)                               │
│    ├── Countries (location data)                                 │
│    ├── Addresses (user locations)                                │
│    └── CompanyContacts (branch contacts)                         │
│                                                                   │
│  Queue System (Horizon)                                          │
│    ├── SendJobApplicationMail (notify employer)                 │
│    └── SendJobAlertMail (notify candidates)                      │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## 1. EMPLOYER WORKFLOW

### Phase 1: Registration & Setup
```
1. POST /register
   ├── Input: username, email, password, user_type='employer'
   ├── Validation: unique email, strong password
   └── Output: user_id, JWT token

2. POST /company
   ├── Input: name, company_type, website_url, company_size, country_id
   ├── Relationships: user_id → User (1:1)
   └── Output: company_id
```

### Phase 2: Job Posting
```
3. POST /jobs (with middleware 'auth', 'role:employer')
   ├── Input: 
   │   ├── title (required)
   │   ├── job_description (required)
   │   ├── requirements (required)
   │   ├── category_id (required)
   │   ├── job_type: 'full-time'|'part-time'|'remote'
   │   ├── salary (optional)
   │   ├── currency (optional)
   │   └── publish_date, end_date
   ├── Database: user_id → User
   │           category_id → Category
   └── Output: job_post_id

4. GET /jobs (list their jobs)
   └── Query: JobPost::with('employer', 'category', 'applications')->where('user_id', auth()->id())

5. PATCH /jobs/{job} (edit job)
   └── Validation: user_id == auth()->id()

6. DELETE /jobs/{job} (delete job)
   └── Cascade: delete related JobApplications
```

### Phase 3: Managing Applications
```
7. GET /profile/jobs (with eager loading)
   └── Returns: jobs with applications count and applicant details

8. GET /applications/{application}
   ├── Load: employee profile, education, skills, experience
   └── Access: employer_id == auth()->id()

9. Candidate Selection (interview process)
   └── POST /candidate-selections
       ├── job_post_id, employee_id, employer_id
       ├── interview_date, interview_status, status
       └── Trigger: SendJobApplicationMail (queued)
```

### Phase 4: Dashboard
```
10. Employer Dashboard
    ├── Total jobs posted
    ├── Total applications received
    ├── Company info (editable)
    ├── Recent applications (with notification)
    └── Job performance metrics
```

## 2. EMPLOYEE WORKFLOW

### Phase 1: Registration & Profile Setup
```
1. POST /register
   ├── Input: username, email, password, user_type='employee'
   ├── Validation: unique email, strong password
   └── Output: user_id, JWT token

2. PATCH /profile
   ├── Update: email, phone (optional fields)
   └── user_id: auth()->id()

3. PATCH /profile/address
   ├── Input: address_line, city, state, country_id
   ├── Create or Update: Address model
   └── Relations: user_id, country_id (foreign key)
```

### Phase 2: Building Profile
```
4. POST /education (multiple records)
   ├── Input: degree, institution, field_of_study, start_date, end_date
   ├── Relations: user_id → User (1:M)
   └── Cascade: delete on user delete

5. POST /experience (multiple records)
   ├── Input: title, company, start_date, end_date, is_current
   ├── Relations: user_id → User (1:M)
   └── Cascade: delete on user delete

6. POST /skills (multiple records)
   ├── Input: name, level ('Beginner'|'Intermediate'|'Expert'), years_experience
   ├── Relations: user_id → User (1:M)
   └── is_active: default true

7. POST /trainings (multiple records)
   ├── Input: title, institution, certificate_url, dates
   ├── Relations: user_id → User (1:M)
   └── Optional: expiry_date validation

8. POST /documents (resume, certifications)
   ├── Input: name, document_type, file_path, expiry_date
   ├── Relations: user_id → User (1:M)
   └── Storage: filesystem (local/S3)
```

### Phase 3: Job Hunting
```
9. GET /jobs (list all active jobs)
   ├── Query: JobPost::with('employer', 'category')
   │          ->where('status', true)
   │          ->where('end_date', '>=', today)
   ├── Filters: category_id, job_type, salary range, search keyword
   └── Load: employer.company relationship

10. GET /jobs/{job} (view job details)
    ├── Load: employer, category, applications.count()
    └── Display: full description, requirements, salary, deadline

11. POST /jobs/{job}/apply
    ├── Validation: not already applied
    ├── Create: JobApplication (employee_id, job_post_id, employer_id)
    ├── apply_date: now()
    ├── status: 'pending'
    └── Trigger: dispatch(new SendJobApplicationMail($application))
        └── Queue: database
        └── Worker: php artisan queue:work
        └── Email: sent to employer
```

### Phase 4: Tracking Applications
```
12. GET /profile/applications
    ├── Relations: application.job.category, application.job.employer
    ├── Status filter: pending, reviewed, selected, rejected, hired
    └── Display: job title, company, salary, apply date, current status

13. GET /applications/{application}
    ├── View: full application with all profile data
    └── Access: employee_id == auth()->id()

14. DELETE /applications/{application}
    ├── Withdraw: application (employee can withdraw anytime)
    ├── Status update: 'withdrawn'
    └── Access: employee_id == auth()->id()
```

### Phase 5: Profile Management
```
15. GET /profile
    ├── Load all relations: educations, experiences, skills, trainings, documents
    ├── user_id: auth()->id()
    └── Return: complete profile with all sections

16. GET /profile/educations
17. GET /profile/experiences
18. GET /profile/skills
19. GET /profile/trainings
20. GET /profile/documents
    └── All: allow filtering, sorting, pagination
```

## 3. ADMIN WORKFLOW

### Phase 1: Admin Access (Super User)
```
1. Admin Registration (manual setup in database)
   ├── user_type: 'admin'
   ├── is_active: true
   └── Access: all routes with 'role:admin' middleware

2. Middleware Protection
   ├── Route::middleware(['auth', 'role:admin'])
   └── Blocks: non-admin users with 403 Forbidden
```

### Phase 2: User Management
```
3. GET /admin/users (list all users)
   ├── Filters: user_type, is_active status, search by email/username
   ├── Load: user.company, user.jobs.count(), user.applications.count()
   └── Paginate: 50 per page

4. GET /admin/users/{user} (view user details)
   ├── Profile: username, email, phone, user_type, is_active
   ├── Relations: company, jobs, applications, addresses
   └── Activity: created_at, updated_at, last_login

5. PATCH /admin/users/{user} (update user)
   ├── Fields: is_active (suspend/reactivate)
   ├── user_type: change role
   └── Override: any user restrictions

6. DELETE /admin/users/{user} (delete user)
   ├── Cascade: company, addresses, jobs, applications, profile data
   └── Soft delete option: preserve history

7. POST /admin/users (create user manually)
   ├── Input: username, email, password, user_type
   └── Direct: bypass registration flow
```

### Phase 3: Content Management
```
8. GET /admin/jobs (list all job posts)
   ├── Filters: status, category, employer
   ├── Load: employer.company, category, applications.count()
   └── Metrics: applications, views, days posted

9. PATCH /admin/jobs/{job} (update job status)
   ├── status: 'active', 'closed', 'archived'
   ├── Bulk: change multiple jobs at once
   └── Override: employer restrictions

10. DELETE /admin/jobs/{job} (remove job)
    ├── Cascade: JobApplications, CandidateSelections
    └── Audit: log deletion with reason

11. GET /admin/applications (list all job applications)
    ├── Filters: status, job, employer, employee
    ├── Load: application.job, application.employee, application.employer
    └── Metrics: pending, reviewed, selected count

12. PATCH /admin/applications/{application} (manage application)
    ├── status: 'pending', 'reviewed', 'selected', 'rejected', 'hired'
    └── Notify: send email to employee on status change
```

### Phase 4: Category & Configuration Management
```
13. GET /admin/categories (list all categories)
    └── Return: id, name, status, job_count

14. POST /admin/categories (create category)
    ├── Input: name, description, status
    └── Default: status = true

15. PATCH /admin/categories/{category} (update category)
16. DELETE /admin/categories/{category}

17. GET /admin/countries (list countries)
    └── Used: for address and company location data

18. POST /admin/countries (add country)
19. PATCH /admin/countries/{country}
20. DELETE /admin/countries/{country}
```

### Phase 5: Dashboard & Analytics
```
21. GET /admin/dashboard
    └── Metrics:
        ├── Total users: employees, employers, admins
        ├── Active jobs: posted, closed, archived
        ├── Total applications: pending, accepted, rejected
        ├── Registered: today, this month, all time
        └── Charts: registrations trend, applications by category

22. GET /admin/reports
    ├── Report: job market analysis
    ├── Report: most applied jobs
    ├── Report: employer activity
    ├── Report: employee conversion funnel
    └── Export: CSV, PDF
```

## Data Relationships

### User
```
User (1) ──── (1) Company
User (1) ──── (1) Address
User (1) ──── (M) Education
User (1) ──── (M) Experience
User (1) ──── (M) Skill
User (1) ──── (M) Training
User (1) ──── (M) Document
User (1) ──── (M) JobPost (as employer)
User (1) ──── (M) JobApplication (as employee)
User (1) ──── (M) JobApplication (as employer)
User (1) ──── (M) CandidateSelection
```

### JobPost
```
JobPost (M) ──── (1) User (employer)
JobPost (M) ──── (1) Category
JobPost (1) ──── (M) JobApplication
JobPost (1) ──── (M) CandidateSelection
```

### JobApplication
```
JobApplication (M) ──── (1) JobPost
JobApplication (M) ──── (1) User (employee)
JobApplication (M) ──── (1) User (employer)
JobApplication (1) ──── (M) CandidateSelection
```

## API Response Patterns

### Success (200, 201)
```json
{
  "data": { /* model or collection */ },
  "message": "Operation successful"
}
```

### Error (400, 403, 404, 422)
```json
{
  "error": "Error message",
  "errors": { /* validation errors */ }
}
```

## Queue & Background Jobs

### Job 1: SendJobApplicationMail
- Trigger: when employee applies for job
- Queue: database
- Recipient: employer email
- Template: application notification with applicant details
- Retry: 3 attempts, 90 second delay

### Job 2: SendJobAlertMail
- Trigger: when employer posts new job (optional)
- Queue: database
- Recipients: employees matching job criteria
- Template: job alert with job details
- Run: `php artisan horizon` or `php artisan queue:work`

## Security Considerations

1. **Authentication**: JWT or Session-based auth
2. **Authorization**: Role-based middleware (employer, employee, admin)
3. **Validation**: Request validation on all inputs
4. **CSRF Protection**: X-CSRF-TOKEN header
5. **Rate Limiting**: Prevent spam (e.g., max 5 applications per minute)
6. **Password Hashing**: bcrypt/argon2
7. **Soft Deletes**: Archive data instead of hard deletes
8. **Audit Logging**: Track admin actions
9. **Data Privacy**: Don't expose sensitive fields (passwords, hashed tokens)
10. **File Upload Security**: Validate file types, max size, scan for malware

## Deployment Checklist

- [ ] Database migrations: `php artisan migrate`
- [ ] Queue tables: `php artisan queue:table` & `php artisan migrate`
- [ ] Middleware registration: RoleMiddleware in kernel
- [ ] Middleware aliases: assign to routes/kernel
- [ ] Horizon setup: `composer require laravel/horizon` (optional)
- [ ] Email configuration: set MAIL_* in .env
- [ ] File storage: set FILESYSTEM_DISK
- [ ] Run tests: `php artisan test`
- [ ] Cache & config: `php artisan config:cache`
- [ ] Queue worker: start with supervisor or manual
- [ ] Monitoring: track queue, error logs, performance

## Performance Optimization

1. Query Optimization
   - Use eager loading (`.with()`) to prevent N+1
   - Specify columns: `->select('id', 'name')` where applicable
   - Pagination: `->paginate(50)` for large datasets

2. Caching
   - Cache job categories: 24 hours
   - Cache countries list: 7 days
   - Cache user profile: 1 hour per-user

3. Indexing
   - Foreign keys: category_id, user_id, country_id
   - Search columns: title, username, email
   - Timestamps: created_at, updated_at, end_date

4. Queue Processing
   - Background jobs for emails (don't block requests)
   - Monitor queue with Horizon
   - Set appropriate retry timeouts
