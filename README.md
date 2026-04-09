# 🌐 Job Portal - Complete Laravel Application

A production-ready job portal platform built with **Laravel 11**, **Vue 3**, and **Inertia.js**. Supports three distinct user types: **Admin**, **Employer**, and **Employee** with complete workflows, authentication, and queue-based email notifications.

## ✨ Features

### 👨‍💼 For Employers
- ✅ Company profile management
- ✅ Post job listings with full details
- ✅ View and manage job applications
- ✅ Track candidate progress
- ✅ Analytics dashboard

### 👤 For Employees
- ✅ Build comprehensive professional profile
- ✅ Browse job listings with advanced filters
- ✅ Apply for jobs with duplicate prevention
- ✅ Track application status
- ✅ Receive notifications

### 🔐 For Admins
- ✅ Manage all users (create, update, suspend, delete)
- ✅ Manage job postings and applications
- ✅ Master data management (categories, countries)
- ✅ System analytics and reporting

### ⚙️ Technical Features
- ✅ Role-based access control (RBAC)
- ✅ Laravel Horizon queue system
- ✅ Email notifications
- ✅ 70+ RESTful endpoints
- ✅ Form validation
- ✅ Comprehensive error handling
- ✅ Database optimization (N+1 prevention)

## 🚀 Quick Start (5 Minutes)

### Prerequisites
- PHP 8.2+, Composer
- MySQL 5.7+
- Node.js & npm

### Installation

```bash
# Install dependencies
composer install && npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (update DB credentials in .env first)
php artisan migrate

# Start servers
# Terminal 1:
php artisan serve

# Terminal 2:
php artisan queue:work

# Open: http://localhost:8000
```

👉 **[Full Setup Guide →](QUICKSTART.md)**

---

## 📚 Documentation (Start Here!)

| Document | Purpose |
|----------|---------|
| 🚀 **[QUICKSTART.md](QUICKSTART.md)** | Installation, setup, first run |
| 📖 **[DEVELOPER_CHEATSHEET.md](DEVELOPER_CHEATSHEET.md)** | Quick reference, essential commands |
| 🔌 **[API_EXAMPLES.md](API_EXAMPLES.md)** | All API endpoints with examples |
| 🏗️ **[SYSTEM_FLOW.md](SYSTEM_FLOW.md)** | Architecture, workflows, data flow |
| ⚙️ **[HORIZON_SETUP.md](HORIZON_SETUP.md)** | Queue setup, email configuration |
| ✅ **[VERIFICATION_CHECKLIST.md](VERIFICATION_CHECKLIST.md)** | Complete setup verification |
| 🗺️ **[DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)** | Map of all documentation |
| 📮 **[postman_collection.json](postman_collection.json)** | Ready-to-import API tests |

**👉 Start with [QUICKSTART.md](QUICKSTART.md) if you're new here!**

---

## 🎯 Key Workflows

### Employee: Register → Profile → Apply → Track
```
POST /register (user_type=employee)
  → POST /education, /experience, /skills
  → GET /jobs (browse)
  → POST /jobs/{id}/apply
  → GET /profile/applications (track status)
```

### Employer: Register → Company → Post → Manage
```
POST /register (user_type=employer)
  → POST /company
  → POST /jobs (post job)
  → GET /admin/applications (view applicants)
  → PATCH /admin/applications/{id} (update status)
```

### Admin: Login → Dashboard → Manage
```
GET /admin/dashboard (metrics)
  → GET /admin/users (manage users)
  → GET /admin/jobs (manage jobs)
  → GET /admin/applications (manage applications)
  → GET /admin/analytics (view reports)
```

---

## 🏗️ Architecture

### User Types
- **admin**: Full system access
- **employer**: Company & job management
- **employee**: Profile & job applications

### Database
- 15 migrations (fully normalized)
- 12 Eloquent models with relationships
- Foreign keys with cascade deletes
- Optimized indexes

### Controllers
- 9 feature controllers with business logic
- RESTful endpoints
- Role-based authorization
- Complete error handling

### Queue System
- Database-backed with Laravel Horizon
- `SendJobApplicationMail` job
- `SendJobAlertMail` job
- Email notifications to applicants & employers

### API
- 70+ endpoints
- JSON responses
- Comprehensive validation
- Consistent error handling

---

## 🔗 API Endpoints (70+)

### Highlights
- **Profile**: 40+ methods (educations, experiences, skills, address, etc.)
- **Jobs**: Full CRUD for posting & managing
- **Applications**: Apply, withdraw, track status
- **Admin**: User management, analytics, master data

**👉 See [API_EXAMPLES.md](API_EXAMPLES.md) for all endpoints**

---

## 🔐 Security

✅ Password hashing (bcrypt)
✅ CSRF protection
✅ Role-based access control
✅ User ownership validation
✅ Input validation on all endpoints
✅ Foreign key constraints
✅ Cascade delete rules

---

## 📊 Database Schema

**15 tables:**
- Core: users, companies, job_posts, job_applications
- Employee Profile: educations, experiences, skills, trainings, documents
- Support: addresses, countries, categories, candidate_selections, company_contacts

**Relationships:**
- User ↔ Company (1:1)
- User ↔ JobPost (1:M)
- JobPost ↔ JobApplication (1:M)
- User ↔ Education/Experience/Skills/etc (1:M)

---

## 🧪 Testing

### Quick Test (5 min)
Use **[postman_collection.json](postman_collection.json)**:
1. Import into Postman
2. Set environment: `base_url=http://localhost:8000`
3. Register employee & employer
4. Create company → Post job → Apply → Check status

### Complete Workflows
See **[VERIFICATION_CHECKLIST.md](VERIFICATION_CHECKLIST.md)** for detailed testing guide.

---

## 🛠️ Development

### Key Technologies
- Laravel 11, MySQL, Eloquent ORM
- Vue 3 Composition API, Inertia.js
- Tailwind CSS
- Laravel Horizon

### Common Commands
```bash
# Server
php artisan serve
php artisan queue:work

# Database
php artisan migrate
php artisan migrate:fresh

# Development
php artisan tinker     # Interactive shell
php artisan routes:list # List routes
```

**👉 See [DEVELOPER_CHEATSHEET.md](DEVELOPER_CHEATSHEET.md) for 30+ commands**

---

## 📈 Performance

✅ Eager loading (`.with()`) prevents N+1 queries
✅ Pagination on all list endpoints
✅ Database indexes on foreign keys
✅ Ready for caching layer

---

## 🎉 Project Status

### ✅ Complete & Production Ready
- [x] Full database schema (15 migrations)
- [x] All models with relationships
- [x] 9 controllers with business logic
- [x] 70+ API endpoints
- [x] RBAC middleware
- [x] Queue system with Horizon
- [x] Email notifications
- [x] Admin panel
- [x] Complete documentation

### 📚 Comprehensive Guides
- [x] Setup guide
- [x] API documentation
- [x] System architecture
- [x] Developer cheatsheet
- [x] Verification checklist
- [x] Queue setup

---

## 🚀 Deployment

### Pre-Deployment
- [ ] Environment variables configured
- [ ] Database migrated
- [ ] Queue worker running (via supervisor)
- [ ] Email service (SMTP) configured
- [ ] SSL certificate installed

### Configuration
```env
APP_ENV=production
DB_CONNECTION=mysql
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
```

**👉 See [QUICKSTART.md](QUICKSTART.md) for full deployment checklist**

---

## 📞 Support

### Quick Navigation
| Need | Go To |
|------|-------|
| Setup help | [QUICKSTART.md](QUICKSTART.md) |
| API reference | [API_EXAMPLES.md](API_EXAMPLES.md) |
| Quick tips | [DEVELOPER_CHEATSHEET.md](DEVELOPER_CHEATSHEET.md) |
| Architecture | [SYSTEM_FLOW.md](SYSTEM_FLOW.md) |
| Queue help | [HORIZON_SETUP.md](HORIZON_SETUP.md) |
| Verify setup | [VERIFICATION_CHECKLIST.md](VERIFICATION_CHECKLIST.md) |

### Troubleshooting
- **Middleware error** → Check [VERIFICATION_CHECKLIST.md](VERIFICATION_CHECKLIST.md)
- **Queue not working** → See [HORIZON_SETUP.md](HORIZON_SETUP.md)
- **Email not sending** → Check [HORIZON_SETUP.md](HORIZON_SETUP.md)
- **API help** → See [API_EXAMPLES.md](API_EXAMPLES.md)

---

## 📜 License & Contributing

This is a complete, standalone project for immediate use. Contributions welcome via pull requests.

---

## 🎯 Next Steps

1. **👉 Start Here**: Read [QUICKSTART.md](QUICKSTART.md)
2. **Test API**: Import [postman_collection.json](postman_collection.json)
3. **Reference**: Use [DEVELOPER_CHEATSHEET.md](DEVELOPER_CHEATSHEET.md)
4. **Build**: Review [API_EXAMPLES.md](API_EXAMPLES.md)
5. **Deploy**: Follow [QUICKSTART.md](QUICKSTART.md) deployment section

---

**Created:** January 2024
**Framework:** Laravel 11 + Vue 3 + Inertia.js
**Status:** ✅ Production Ready
**License:** MIT

---

### 🌟 Quick Links
- [Setup Guide](QUICKSTART.md)
- [API Reference](API_EXAMPLES.md)
- [Developer Cheatsheet](DEVELOPER_CHEATSHEET.md)
- [System Architecture](SYSTEM_FLOW.md)
- [All Documentation](DOCUMENTATION_INDEX.md)

**Ready to get started? Open [QUICKSTART.md](QUICKSTART.md) →**

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
