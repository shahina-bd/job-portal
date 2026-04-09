# Job Portal - API Request/Response Examples

## Base URL
```
http://localhost:8000/api
OR
http://job-portal.local
```

## Headers (for all requests)
```
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: [from form or cookie]
```

---

## 1. AUTHENTICATION

### Register User (Employee)
```http
POST /register
Content-Type: application/json

{
  "username": "john_smith",
  "email": "john@example.com",
  "phone": "+1234567890",
  "password": "SecurePass123!",
  "password_confirmation": "SecurePass123!",
  "user_type": "employee"
}
```

**Response 201:**
```json
{
  "id": 1,
  "username": "john_smith",
  "email": "john@example.com",
  "phone": "+1234567890",
  "user_type": "employee",
  "is_active": true,
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T10:30:00Z"
}
```

### Register User (Employer)
```http
POST /register
Content-Type: application/json

{
  "username": "hr_manager",
  "email": "hr@techcorp.com",
  "phone": "+1999888777",
  "password": "SecurePass123!",
  "password_confirmation": "SecurePass123!",
  "user_type": "employer"
}
```

---

## 2. PROFILE ENDPOINTS (Authenticated Users)

### Get Full Profile
```http
GET /profile
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "id": 1,
  "username": "john_smith",
  "email": "john@example.com",
  "phone": "+1234567890",
  "user_type": "employee",
  "is_active": true,
  "company": null,
  "address": {
    "id": 1,
    "address_line": "123 Main St",
    "city": "New York",
    "state": "NY",
    "country": {
      "id": 1,
      "name": "United States",
      "code": "US"
    }
  },
  "educations": [
    {
      "id": 1,
      "degree": "Bachelor of Science",
      "institution": "State University",
      "field_of_study": "Computer Science",
      "start_date": "2016-09-01",
      "end_date": "2020-05-30"
    }
  ],
  "experiences": [
    {
      "id": 1,
      "title": "Junior Developer",
      "company": "Tech Corp",
      "start_date": "2020-06-01",
      "end_date": null,
      "is_current": true,
      "description": "Working on full-stack web applications"
    }
  ],
  "skills": [
    {
      "id": 1,
      "name": "PHP",
      "level": "Expert",
      "years_experience": 3
    }
  ],
  "applications": [
    {
      "id": 1,
      "job_post_id": 5,
      "job": {
        "id": 5,
        "title": "Senior Backend Developer",
        "company": "Tech Corp"
      },
      "status": "pending",
      "apply_date": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### Update Profile
```http
PATCH /profile
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "phone": "+1111222333",
  "email": "newemail@example.com"
}
```

**Response 200:**
```json
{
  "id": 1,
  "username": "john_smith",
  "email": "newemail@example.com",
  "phone": "+1111222333",
  "updated_at": "2024-01-15T11:00:00Z"
}
```

### Get Profile Address
```http
GET /profile/address
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "id": 1,
  "address_line": "123 Main St",
  "city": "New York",
  "state": "NY",
  "country_id": 1,
  "country": {
    "id": 1,
    "name": "United States",
    "code": "US"
  },
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Create/Update Address
```http
PATCH /profile/address
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "address_line": "456 Oak Ave",
  "city": "Los Angeles",
  "state": "CA",
  "address_type": "home",
  "country_id": 1
}
```

**Response 200:**
```json
{
  "id": 1,
  "address_line": "456 Oak Ave",
  "city": "Los Angeles",
  "state": "CA",
  "address_type": "home",
  "country_id": 1
}
```

---

## 3. EMPLOYEE PROFILE MANAGEMENT

### Create Education Record
```http
POST /education
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "degree": "Master of Science",
  "institution": "MIT",
  "field_of_study": "Computer Science",
  "start_date": "2020-09-01",
  "end_date": "2022-05-30",
  "description": "Specialized in Artificial Intelligence"
}
```

**Response 201:**
```json
{
  "id": 2,
  "user_id": 1,
  "degree": "Master of Science",
  "institution": "MIT",
  "field_of_study": "Computer Science",
  "start_date": "2020-09-01",
  "end_date": "2022-05-30",
  "description": "Specialized in Artificial Intelligence",
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Get All Educations
```http
GET /profile/educations
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "educations": [
    {
      "id": 1,
      "degree": "Bachelor of Science",
      "institution": "State University",
      "field_of_study": "Computer Science",
      "start_date": "2016-09-01",
      "end_date": "2020-05-30"
    },
    {
      "id": 2,
      "degree": "Master of Science",
      "institution": "MIT",
      "field_of_study": "Computer Science",
      "start_date": "2020-09-01",
      "end_date": "2022-05-30"
    }
  ]
}
```

### Update Education
```http
PATCH /education/1
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "end_date": "2020-06-15",
  "description": "Updated description"
}
```

### Delete Education
```http
DELETE /education/1
Authorization: Bearer [session_token]
```

**Response 204:** (No content)

### Create Experience Record
```http
POST /experience
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "title": "Senior Developer",
  "company": "Tech Corp Inc",
  "start_date": "2020-06-01",
  "end_date": null,
  "is_current": true,
  "description": "Led a team of 5 developers on microservices architecture"
}
```

**Response 201:**
```json
{
  "id": 1,
  "user_id": 1,
  "title": "Senior Developer",
  "company": "Tech Corp Inc",
  "start_date": "2020-06-01",
  "end_date": null,
  "is_current": true,
  "description": "Led a team of 5 developers on microservices architecture",
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Create Skill
```http
POST /skills
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "name": "Laravel",
  "level": "Expert",
  "years_experience": 4
}
```

**Response 201:**
```json
{
  "id": 1,
  "user_id": 1,
  "name": "Laravel",
  "level": "Expert",
  "years_experience": 4,
  "is_active": true,
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Get All Skills
```http
GET /profile/skills
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "skills": [
    {
      "id": 1,
      "name": "Laravel",
      "level": "Expert",
      "years_experience": 4
    },
    {
      "id": 2,
      "name": "React",
      "level": "Intermediate",
      "years_experience": 2
    }
  ]
}
```

### Upload Document
```http
POST /documents
Authorization: Bearer [session_token]
Content-Type: multipart/form-data

{
  "name": "AWS Solutions Architect",
  "document_type": "certification",
  "file": [binary file],
  "expiry_date": "2026-01-15"
}
```

---

## 4. EMPLOYER COMPANY MANAGEMENT

### Create Company
```http
POST /company
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "name": "Tech Innovation Corp",
  "company_type": "Startup",
  "website_url": "https://techinnovation.com",
  "company_size": "51-200",
  "company_description": "Leading provider of cloud solutions",
  "country_id": 1
}
```

**Response 201:**
```json
{
  "id": 1,
  "user_id": 2,
  "name": "Tech Innovation Corp",
  "company_type": "Startup",
  "website_url": "https://techinnovation.com",
  "company_size": "51-200",
  "company_description": "Leading provider of cloud solutions",
  "country_id": 1,
  "is_active": true,
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Get Company Profile
```http
GET /profile/company
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "id": 1,
  "user_id": 2,
  "name": "Tech Innovation Corp",
  "website_url": "https://techinnovation.com",
  "company_size": "51-200",
  "country": {
    "id": 1,
    "name": "United States",
    "code": "US"
  }
}
```

### Update Company
```http
PATCH /company/1
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "company_description": "Updated company description",
  "company_size": "201-500"
}
```

---

## 5. JOB POSTING MANAGEMENT (Employer)

### Create Job Post
```http
POST /jobs
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "category_id": 1,
  "title": "Senior Backend Developer (Laravel)",
  "job_description": "We are looking for an experienced Laravel developer with 5+ years of experience in building scalable web applications.",
  "requirements": "- 5+ years of Laravel experience\n- Strong understanding of RESTful APIs\n- Experience with Docker and Kubernetes\n- Knowledge of Redis and Caching strategies",
  "salary": 150000,
  "currency": "USD",
  "job_type": "full-time",
  "publish_date": "2024-01-15",
  "end_date": "2024-02-15"
}
```

**Response 201:**
```json
{
  "id": 5,
  "user_id": 2,
  "category_id": 1,
  "title": "Senior Backend Developer (Laravel)",
  "job_description": "We are looking for an experienced Laravel developer...",
  "requirements": "- 5+ years of Laravel experience...",
  "salary": 150000,
  "currency": "USD",
  "job_type": "full-time",
  "publish_date": "2024-01-15",
  "end_date": "2024-02-15",
  "status": true,
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Get All Jobs Posted by Employer
```http
GET /profile/jobs
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "jobs": [
    {
      "id": 5,
      "title": "Senior Backend Developer (Laravel)",
      "category": "Technology",
      "applications_count": 3,
      "publish_date": "2024-01-15",
      "end_date": "2024-02-15",
      "status": true
    }
  ]
}
```

### Update Job Post
```http
PATCH /jobs/5
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "salary": 160000,
  "end_date": "2024-02-28",
  "status": true
}
```

### Delete Job Post
```http
DELETE /jobs/5
Authorization: Bearer [session_token]
```

**Response 204:** (No content)

---

## 6. JOB BROWSING & APPLICATION (Employee)

### Get All Jobs (with filters)
```http
GET /jobs?category_id=1&job_type=full-time&search=Laravel
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "jobs": [
    {
      "id": 5,
      "title": "Senior Backend Developer (Laravel)",
      "company": {
        "id": 1,
        "name": "Tech Innovation Corp"
      },
      "category": {
        "id": 1,
        "name": "Technology"
      },
      "job_type": "full-time",
      "salary": 150000,
      "currency": "USD",
      "location": "New York, NY",
      "publish_date": "2024-01-15",
      "end_date": "2024-02-15"
    }
  ],
  "total": 1,
  "per_page": 15,
  "current_page": 1
}
```

### Get Job Details
```http
GET /jobs/5
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "id": 5,
  "title": "Senior Backend Developer (Laravel)",
  "job_description": "We are looking for...",
  "requirements": "- 5+ years of Laravel experience...",
  "salary": 150000,
  "currency": "USD",
  "job_type": "full-time",
  "publish_date": "2024-01-15",
  "end_date": "2024-02-15",
  "employer": {
    "id": 2,
    "username": "hr_manager",
    "email": "hr@techcorp.com"
  },
  "company": {
    "id": 1,
    "name": "Tech Innovation Corp",
    "website_url": "https://techinnovation.com"
  },
  "category": {
    "id": 1,
    "name": "Technology"
  },
  "applied": false,
  "created_at": "2024-01-15T10:30:00Z"
}
```

### Apply for Job
```http
POST /jobs/5/apply
Authorization: Bearer [session_token]
Content-Type: application/json

{
  "cover_letter": "I am very interested in this position as it aligns with my career goals."
}
```

**Response 201:**
```json
{
  "id": 1,
  "job_post_id": 5,
  "employee_id": 1,
  "employer_id": 2,
  "status": "pending",
  "apply_date": "2024-01-15T10:30:00Z",
  "message": "Application submitted successfully. A notification email has been sent to the employer."
}
```

**Note:** This triggers a queued job `SendJobApplicationMail` that will email the employer.

### Get All My Applications
```http
GET /profile/applications
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "applications": [
    {
      "id": 1,
      "job": {
        "id": 5,
        "title": "Senior Backend Developer (Laravel)",
        "company": "Tech Innovation Corp",
        "salary": 150000,
        "currency": "USD"
      },
      "employer": {
        "id": 2,
        "username": "hr_manager",
        "company": "Tech Innovation Corp"
      },
      "status": "pending",
      "apply_date": "2024-01-15T10:30:00Z"
    }
  ]
}
```

### Get Application Details
```http
GET /applications/1
Authorization: Bearer [session_token]
```

**Response 200:**
```json
{
  "id": 1,
  "job_post_id": 5,
  "employee_id": 1,
  "employer_id": 2,
  "status": "pending",
  "apply_date": "2024-01-15T10:30:00Z",
  "job": {
    "id": 5,
    "title": "Senior Backend Developer (Laravel)",
    "description": "..."
  },
  "employee": {
    "id": 1,
    "username": "john_smith",
    "email": "john@example.com",
    "phone": "+1234567890"
  },
  "employer": {
    "id": 2,
    "username": "hr_manager",
    "company": "Tech Innovation Corp"
  }
}
```

### Withdraw Application
```http
DELETE /applications/1
Authorization: Bearer [session_token]
```

**Response 204:** (No content)

---

## 7. ADMIN ENDPOINTS

### Admin Dashboard
```http
GET /admin/dashboard
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "total_users": 150,
  "total_jobs": 45,
  "total_applications": 320,
  "today_registrations": 5,
  "pending_applications": 89,
  "active_jobs": 42
}
```

### List Users
```http
GET /admin/users?user_type=employee&page=1
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "users": [
    {
      "id": 1,
      "username": "john_smith",
      "email": "john@example.com",
      "user_type": "employee",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00Z",
      "company": null
    }
  ],
  "total": 100,
  "per_page": 50,
  "current_page": 1
}
```

### View User Details
```http
GET /admin/users/1
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "id": 1,
  "username": "john_smith",
  "email": "john@example.com",
  "phone": "+1234567890",
  "user_type": "employee",
  "is_active": true,
  "created_at": "2024-01-15T10:30:00Z",
  "educations": [...],
  "experiences": [...],
  "skills": [...],
  "applications": [...]
}
```

### Update User (Admin)
```http
PATCH /admin/users/1
Authorization: Bearer [admin_token]
Content-Type: application/json

{
  "is_active": false,
  "user_type": "employee"
}
```

**Response 200:**
```json
{
  "id": 1,
  "username": "john_smith",
  "is_active": false,
  "user_type": "employee"
}
```

### Delete User (Admin)
```http
DELETE /admin/users/1
Authorization: Bearer [admin_token]
```

**Response 204:** (Cascade deletes all related data)

### List Jobs (Admin)
```http
GET /admin/jobs?status=true&page=1
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "jobs": [
    {
      "id": 5,
      "title": "Senior Backend Developer",
      "employer": {
        "id": 2,
        "username": "hr_manager"
      },
      "category": "Technology",
      "applications_count": 12,
      "salary": 150000,
      "status": true,
      "created_at": "2024-01-15T10:30:00Z"
    }
  ],
  "total": 45,
  "per_page": 50,
  "current_page": 1
}
```

### List Applications (Admin)
```http
GET /admin/applications?status=pending&page=1
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "applications": [
    {
      "id": 1,
      "job": {
        "id": 5,
        "title": "Senior Backend Developer"
      },
      "employee": {
        "id": 1,
        "username": "john_smith"
      },
      "employer": {
        "id": 2,
        "username": "hr_manager"
      },
      "status": "pending",
      "apply_date": "2024-01-15T10:30:00Z"
    }
  ],
  "total": 89,
  "per_page": 50,
  "current_page": 1
}
```

### Update Application Status (Admin)
```http
PATCH /admin/applications/1
Authorization: Bearer [admin_token]
Content-Type: application/json

{
  "status": "reviewed"
}
```

**Response 200:**
```json
{
  "id": 1,
  "job_post_id": 5,
  "status": "reviewed",
  "updated_at": "2024-01-15T11:00:00Z"
}
```

### Get Categories
```http
GET /admin/categories
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "categories": [
    {
      "id": 1,
      "name": "Technology",
      "description": "IT & Software Development roles",
      "status": true,
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

### Create Category
```http
POST /admin/categories
Authorization: Bearer [admin_token]
Content-Type: application/json

{
  "name": "Healthcare",
  "description": "Medical and health-related positions",
  "status": true
}
```

**Response 201:**
```json
{
  "id": 5,
  "name": "Healthcare",
  "description": "Medical and health-related positions",
  "status": true
}
```

### Get Countries
```http
GET /admin/countries
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "countries": [
    {
      "id": 1,
      "name": "United States",
      "code": "US",
      "is_active": true
    },
    {
      "id": 2,
      "name": "Canada",
      "code": "CA",
      "is_active": true
    }
  ]
}
```

### Create Country
```http
POST /admin/countries
Authorization: Bearer [admin_token]
Content-Type: application/json

{
  "name": "Australia",
  "code": "AU",
  "is_active": true
}
```

**Response 201:**
```json
{
  "id": 10,
  "name": "Australia",
  "code": "AU",
  "is_active": true
}
```

### Analytics
```http
GET /admin/analytics?period=week
Authorization: Bearer [admin_token]
```

**Response 200:**
```json
{
  "registrations_by_date": [
    {
      "date": "2024-01-15",
      "count": 5,
      "employee": 3,
      "employer": 2
    }
  ],
  "applications_by_date": [
    {
      "date": "2024-01-15",
      "count": 12
    }
  ],
  "jobs_by_category": [
    {
      "category": "Technology",
      "count": 28
    }
  ],
  "top_employers": [
    {
      "id": 2,
      "company_name": "Tech Innovation Corp",
      "jobs_posted": 8,
      "applications": 85
    }
  ],
  "most_applied_jobs": [
    {
      "id": 5,
      "title": "Senior Backend Developer",
      "applications": 24
    }
  ]
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required"],
    "password": ["The password must be at least 8 characters"]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthorized",
  "error": "User must be authenticated"
}
```

### 403 Forbidden
```json
{
  "message": "Forbidden",
  "error": "This action is unauthorized. Only admins can access this resource."
}
```

### 404 Not Found
```json
{
  "message": "Not found",
  "error": "The requested resource does not exist"
}
```

### 409 Conflict
```json
{
  "message": "Conflict",
  "error": "You have already applied for this job"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server error occurred",
  "error": "An unexpected error occurred. Please try again later."
}
```

---

## Testing with Postman

1. **Create Collection:** "Job Portal API"
2. **Add Requests:** Use the examples above
3. **Environment Variables:**
   ```json
   {
     "base_url": "http://localhost:8000",
     "admin_token": "[your-admin-token]",
     "user_token": "[your-user-token]"
   }
   ```
4. **Run Requests:** Start with Register, then use returned tokens for auth
5. **Sample Workflow:**
   - POST /register (employee) → Note user ID = 1
   - POST /register (employer) → Note user ID = 2
   - POST /company (employer)
   - POST /jobs (employer)
   - GET /jobs (employee)
   - POST /jobs/5/apply (employee)
   - GET /admin/applications (admin)

---

## Common Test Scenarios

### Scenario 1: Complete Employee Workflow
```
1. Register as employee
2. Create education record
3. Create experience record
4. Add skills
5. Update address
6. Browse jobs
7. Apply for job
8. Check application status
```

### Scenario 2: Complete Employer Workflow
```
1. Register as employer
2. Create company
3. Post a job
4. View applications for job
5. Check applicant profiles
6. Update application status
```

### Scenario 3: Admin Management
```
1. Login as admin
2. View all users
3. View all jobs and applications
4. Update application status
5. Manage categories and countries
6. Delete a job post
7. View analytics
```
