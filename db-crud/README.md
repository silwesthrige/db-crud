<div align="center">
	<h1>User & Event Management Platform</h1>
	<p><strong>Role-based user onboarding, approval workflow, notification system and event tracking built with Laravel 12.</strong></p>
	<p>
		<a href="#features">Features</a> •
		<a href="#quick-start">Quick Start</a> •
		<a href="#database">Database</a> •
		<a href="#user-lifecycle">User Lifecycle</a> •
		<a href="#notifications">Notifications</a> •
		<a href="#scripts">Dev Scripts</a> •
		<a href="#contributing">Contributing</a>
	</p>
</div>

---

## Overview

This application provides a production-ready foundation for managing users, their approval lifecycle (pending → approved/inactive → rejected), event records, and administrative actions. It includes bulk approval/rejection, role distinction (admin vs regular), soft lifecycle state transitions, and a notification infrastructure ready for extension.

The codebase deliberately separates concerns via services, observers, and Eloquent models to ensure maintainability and scalability.

## Technology Stack

-   PHP 8.2+ / Laravel 12
-   Blade + Bootstrap 5 + Font Awesome 6
-   Database (supports MySQL/PostgreSQL/SQLite; default `.env.example` uses SQLite)
-   Queue & Session backed by database drivers
-   Vite build pipeline for front-end assets

## Features

-   User registration with admin approval workflow
-   Status categories: Pending, Active (approved), Inactive, Rejected
-   Bulk approve / bulk reject with reason capture
-   Individual rejection with mandatory reason
-   Reactivation (move rejected user back to pending)
-   Role-based UI (admin crown vs regular user icon) with high-contrast icon set
-   User detail profile with status metadata and audit references
-   Event tracking model (`Event`) linked to users (extensible)
-   Notification scaffolding (`Notification` model + seeder + services)
-   Database-backed sessions, cache, and queue configuration
-   Clean separation of business logic (Services) and persistence (Models)
-   Seeders for admin and sample users
-   Modular migrations with incremental improvements (status fields, soft attributes, reasons)

## Quick Start

### 1. Clone

```bash
git clone https://github.com/<your-org-or-username>/db-crud.git
cd db-crud
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env   # On Windows PowerShell: copy .env.example .env
php artisan key:generate
```

Adjust in `.env` as needed:

```
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql   # or sqlite (default) / pgsql
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=secret
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database
```

### 4. Database Setup

Choose ONE of the following paths:

#### A. Fresh schema via migrations

```bash
php artisan migrate
php artisan db:seed
```

#### B. Import existing snapshot (provided `lara_crud.sql`)

Create the database, then import (MySQL example):

```bash
mysql -u root -p your_database < lara_crud.sql
```

If you import, still run (to ensure any new migrations apply):

```bash
php artisan migrate
```

### 5. Build Assets (Vite)

```bash
npm run dev   # for development (hot reload)
# or
npm run build # production build
```

### 6. Run Application

```bash
php artisan serve
```

Visit: http://127.0.0.1:8000

### 7. Queue (Optional but recommended for notifications)

```bash
php artisan queue:work
```

## Database

Key tables (summarized):

| Table               | Purpose                                                                               |
| ------------------- | ------------------------------------------------------------------------------------- |
| users               | Core user accounts with status, role, approval metadata, rejection reason, timestamps |
| events (event1)\*   | Event records linked to users (naming based on migration)                             |
| notifications       | System or user-facing notifications (seeded)                                          |
| jobs / failed_jobs  | Queue infrastructure                                                                  |
| sessions            | Session storage                                                                       |
| cache / cache_locks | Cache storage                                                                         |

(\* Adjust naming if consolidated later; migrations show `event1`.)

### Status Fields

`users.status` values used:

-   `pending`
-   `approved` (displayed as Active)
-   `inactive`
-   `rejected`

Additional columns added via incremental migrations include approval timestamps, rejection reason, inactive marker, etc.

## User Lifecycle

```
Registration -> Pending -> (Approve) -> Active
													 (Reject with reason) -> Rejected -> (Reactivate) -> Pending
Active -> (Deactivate) -> Inactive -> (Activate) -> Active
```

Bulk operations allowed only in Pending state. Rejected users can be reactivated (moved back to Pending) preserving audit history.

## Notifications

The notification system is structurally prepared via `Notification` model and seeders. Extend by:

1. Creating dispatchable jobs / events
2. Adding channels (mail, database, broadcast)
3. Triggering notifications inside `Services/NotificationService.php`

## Services & Observers

-   `App\Services\EventService` and `NotificationService` encapsulate business logic.
-   `EventObserver` can be extended for auditing, enrichment, or cascading notifications.

## Code Style & Tooling

Run style fixer:

```bash
./vendor/bin/pint
```

Run tests:

```bash
php artisan test
```

## Dev Convenience Script

The `composer dev` script concurrently runs:

-   Laravel server
-   Queue listener
-   Log tail (pail)
-   Vite dev server

Start it:

```bash
composer run dev
```

## Seeding

After migrations:

```bash
php artisan db:seed
```

Look into `database/seeders` for:

-   `AdminUserSeeder` (ensures an admin account)
-   `SampleUsersSeeder`
-   `NotificationSeeder`

## Environment Notes

-   Default `.env.example` uses `sqlite`. Switch to MySQL/PostgreSQL by changing `DB_CONNECTION` and related vars.
-   Sessions, cache, queue use database drivers for easier horizontal scaling.
-   For production: set `APP_ENV=production`, `APP_DEBUG=false`, configure proper cache/queue (e.g., Redis) and a real mail driver.

## Security

-   Always validate user state transitions server-side.
-   Rejection requires a reason (enforced in UI and controller logic).
-   Limit admin-only routes via middleware (ensure `isAdmin()` checks are enforced in controllers/policies).
-   Rotate `APP_KEY` only before production data is stored.

## Extensibility Ideas

-   Add audit trail table (user_status_history)
-   Add soft deletes to users and events if needed
-   Implement granular roles/permissions (e.g., via spatie/laravel-permission)
-   WebSocket broadcasting for real-time status updates
-   Export CSV/Excel of user segments

## Troubleshooting

| Symptom             | Resolution                                                                               |
| ------------------- | ---------------------------------------------------------------------------------------- |
| Icons not showing   | Ensure Font Awesome CDN in `templates/admin-master.blade.php` loads (network tab)        |
| Cache inconsistency | Run: `php artisan cache:clear && php artisan config:clear && php artisan view:clear`     |
| Migrations failing  | Verify DB credentials; drop conflicting tables if using snapshot and migrations together |
| Queue jobs stuck    | Run `php artisan queue:failed` then `php artisan queue:retry all`                        |

## Contributing

1. Fork & branch (`feature/<name>`)
2. Run tests and style checks before PR
3. Provide concise PR description (what/why)
4. Avoid mixing unrelated changes

## License

MIT. See `LICENSE` (inherits from Laravel base skeleton).

## Attribution

Built on Laravel 12 skeleton with custom user lifecycle, event management, and notification scaffolding enhancements.

---

### Concise Summary

This repository delivers a ready-to-extend foundation for user onboarding, administrative approval flows, and event-driven interactions. It emphasizes clarity, modularity, and production-aligned defaults. Start here if you need a robust user approval + notification + event core without reinventing structural basics.
