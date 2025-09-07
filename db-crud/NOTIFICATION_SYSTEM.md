# Database-Driven Notification System

## Overview

This Laravel application now features a complete database-driven notification system that replaces the previous localStorage-based approach. All notification data is now persisted in the MySQL database and accessed via REST API endpoints.

## Database Schema

### Notifications Table

-   `id` - Primary key
-   `type` - Notification type (success, warning, info, danger)
-   `title` - Notification title
-   `message` - Notification message content
-   `icon` - Font Awesome icon class
-   `user_id` - Foreign key to users table (nullable for system-wide notifications)
-   `is_read` - Boolean flag for read status
-   `read_at` - Timestamp when notification was read
-   `data` - JSON field for additional data
-   `action_url` - Optional URL for notification actions
-   `priority` - Notification priority (low, medium, high)
-   `expires_at` - Optional expiration timestamp
-   `created_at` - Creation timestamp
-   `updated_at` - Last update timestamp

## API Endpoints

### GET /api/notifications

Returns all notifications for the current user (including system-wide notifications)

### GET /api/notifications/unread-count

Returns the count of unread notifications

### PATCH /api/notifications/{id}/toggle-read

Toggles the read/unread status of a specific notification

### POST /api/notifications/mark-all-read

Marks all notifications as read

### POST /api/notifications/mark-all-unread

Marks all notifications as unread

### DELETE /api/notifications/{id}

Deletes a specific notification

### DELETE /api/notifications

Deletes all notifications (with confirmation)

### POST /api/notifications

Creates a new notification (for testing/admin purposes)

## Features

### ✅ Persistent Storage

-   All notification states saved to MySQL database
-   Survives server restarts and browser sessions
-   No data loss on page refresh

### ✅ Real-time Updates

-   JavaScript fetches latest data from database
-   Auto-refresh every 30 seconds
-   Immediate UI updates after actions

### ✅ Email-like Interface

-   Read/unread status with visual indicators
-   Individual toggle for each notification
-   Bulk actions (mark all read/unread, clear all)
-   Hover actions for delete and toggle

### ✅ Advanced Features

-   System-wide notifications (user_id = null)
-   User-specific notifications
-   Priority levels (low, medium, high)
-   Expiration dates for auto-cleanup
-   Action URLs for clickable notifications
-   JSON data field for extended information

### ✅ Security

-   CSRF token protection
-   User authorization (notifications scoped to user)
-   Input validation and sanitization

## Usage Examples

### Creating Notifications

```php
// User-specific notification
Notification::createForUser(1, 'success', 'Welcome!', 'Account created successfully');

// System-wide notification
Notification::createSystemWide('warning', 'Maintenance', 'System maintenance tonight');

// With additional options
Notification::create([
    'type' => 'info',
    'title' => 'Event Reminder',
    'message' => 'Your event starts in 1 hour',
    'user_id' => 1,
    'priority' => 'high',
    'action_url' => '/events/123',
    'expires_at' => now()->addDays(7)
]);
```

### JavaScript API Usage

```javascript
// Load notifications
await loadNotifications();

// Toggle read status
await toggleReadStatus(notificationId);

// Create test notification
await createNotification("info", "Test", "This is a test notification");

// Mark all as read
await markAllAsRead();
```

## Console Commands for Testing

### Create a test notification:

```bash
php artisan tinker --execute="App\Models\Notification::create(['type' => 'info', 'title' => 'Test', 'message' => 'Test notification', 'user_id' => 1]);"
```

### View notifications in console:

```bash
php artisan tinker --execute="App\Models\Notification::all()->each(function(\$n) { echo \$n->title . ' - ' . (\$n->is_read ? 'Read' : 'Unread') . PHP_EOL; });"
```

### Clean up expired notifications:

```bash
php artisan tinker --execute="App\Models\Notification::cleanupExpired();"
```

## File Structure

### Backend Files

-   `app/Models/Notification.php` - Eloquent model with relationships and methods
-   `app/Http/Controllers/NotificationController.php` - API controller
-   `database/migrations/2025_09_07_140223_create_notifications_table.php` - Database schema
-   `database/seeders/NotificationSeeder.php` - Sample data seeder
-   `routes/web.php` - API routes definition

### Frontend Files

-   `public/assets/admin.js` - Database-driven notification system
-   `public/assets/admin.css` - Notification UI styles
-   `resources/views/templates/includes/topbar.blade.php` - Notification dropdown
-   `resources/views/templates/admin-master.blade.php` - CSRF token setup

## Migration and Setup

1. **Run migrations:**

    ```bash
    php artisan migrate
    ```

2. **Seed sample data:**

    ```bash
    php artisan db:seed --class=NotificationSeeder
    ```

3. **Create admin user (if needed):**
    ```bash
    php artisan db:seed --class=DatabaseSeeder
    ```

## Key Improvements Over localStorage

1. **Data Persistence:** Notifications survive server restarts
2. **Multi-user Support:** Each user has their own notifications
3. **System Notifications:** Admin can send notifications to all users
4. **Rich Metadata:** Priority, expiration, action URLs, etc.
5. **Scalability:** Database can handle thousands of notifications
6. **Reporting:** Can query notification statistics and analytics
7. **Backup/Recovery:** Data is included in database backups

## Performance Considerations

-   Indexed columns for efficient queries (user_id, is_read, created_at, type)
-   Automatic cleanup of expired notifications
-   Pagination support for large notification sets
-   Optimized API responses with only necessary data

## Security Features

-   CSRF protection on all API endpoints
-   User authorization (users only see their own notifications + system-wide)
-   Input validation on all endpoints
-   SQL injection protection via Eloquent ORM
-   XSS protection via Laravel's built-in escaping
