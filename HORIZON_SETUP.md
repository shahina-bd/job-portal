# Job Portal - Queue & Horizon Setup Guide

## Current Configuration

✅ **Queue Driver:** Database (`QUEUE_CONNECTION=database`)
✅ **Mail Mailer:** Log (`MAIL_MAILER=log`)

## Installing Horizon

To enable queue monitoring via Laravel Horizon, install it with:

```bash
composer require laravel/horizon
php artisan horizon:install
```

## Running Queue Workers

### Using Horizon Dashboard
```bash
php artisan horizon
```

Visit: `http://localhost:8000/horizon`

### Using Queue Worker (Without Horizon UI)
```bash
php artisan queue:work
```

## Processing Jobs

Once a queue worker is running:

1. **Job Applications** - When an employee applies for a job:
   - `SendJobApplicationMail` job is dispatched
   - Email sent to employer in the background
   - No blocking of API response

2. **Job Alerts** - When a new job is posted:
   - `SendJobAlertMail` job can be dispatched
   - Emails sent to matching candidates

## Mail Configuration

Current setup uses **Log mailer** for development:
- Emails are logged to `storage/logs/`
- To use real email, update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## Database Setup

Run migrations to create queue tables:

```bash
php artisan migrate
```

This creates:
- `jobs` - for stored jobs
- `job_batches` - for job batching
- `failed_jobs` - for failed job tracking

## Debugging

Check failed jobs:
```bash
php artisan queue:failed
```

Retry failed jobs:
```bash
php artisan queue:retry all
```

Clear all jobs:
```bash
php artisan queue:clear
```

## Example: Dispatching a Job

```php
use App\Jobs\SendJobApplicationMail;

dispatch(new SendJobApplicationMail($application));

// Or queue for later:
dispatch(new SendJobApplicationMail($application))->delay(now()->addMinutes(10));
```

## Performance Tips

- Monitor queue with `php artisan horizon` for real-time insights
- Use Redis for higher throughput (update `QUEUE_CONNECTION=redis`)
- Set up supervisor to auto-restart queue workers
- Configure `config/queue.php` for retry limits and timeouts
