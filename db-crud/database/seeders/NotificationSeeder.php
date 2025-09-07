<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample notifications for user ID 1
        $notifications = [
            [
                'type' => 'success',
                'title' => 'Welcome!',
                'message' => 'Welcome to the admin dashboard! Your account has been successfully set up.',
                'icon' => 'fas fa-check-circle',
                'user_id' => 1,
                'is_read' => false,
                'priority' => 'medium',
                'created_at' => Carbon::now()->subMinutes(2),
                'updated_at' => Carbon::now()->subMinutes(2)
            ],
            [
                'type' => 'info',
                'title' => 'New Event Created',
                'message' => 'A new event "EAD Course work" has been created successfully.',
                'icon' => 'fas fa-plus-circle',
                'user_id' => 1,
                'is_read' => false,
                'priority' => 'medium',
                'action_url' => '/events',
                'created_at' => Carbon::now()->subHours(1),
                'updated_at' => Carbon::now()->subHours(1)
            ],
            [
                'type' => 'warning',
                'title' => 'Event Deadline Approaching',
                'message' => 'The deadline for "coursework" event is approaching in 2 days.',
                'icon' => 'fas fa-clock',
                'user_id' => 1,
                'is_read' => false,
                'priority' => 'high',
                'action_url' => '/events',
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(3)
            ],
            [
                'type' => 'danger',
                'title' => 'High Priority Event',
                'message' => 'A high priority event requires your immediate attention.',
                'icon' => 'fas fa-exclamation-triangle',
                'user_id' => 1,
                'is_read' => false,
                'priority' => 'high',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5)
            ],
            [
                'type' => 'info',
                'title' => 'System Backup Completed',
                'message' => 'The scheduled system backup has been completed successfully.',
                'icon' => 'fas fa-database',
                'user_id' => 1,
                'is_read' => true,
                'read_at' => Carbon::now()->subHours(12),
                'priority' => 'low',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(12)
            ],
            [
                'type' => 'success',
                'title' => 'Profile Updated',
                'message' => 'Your profile information has been updated successfully.',
                'icon' => 'fas fa-user-check',
                'user_id' => 1,
                'is_read' => true,
                'read_at' => Carbon::now()->subDays(1),
                'priority' => 'low',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            // System-wide notifications (user_id = null)
            [
                'type' => 'info',
                'title' => 'System Maintenance',
                'message' => 'Scheduled system maintenance will occur this weekend from 2 AM to 4 AM.',
                'icon' => 'fas fa-tools',
                'user_id' => null, // System-wide notification
                'is_read' => false,
                'priority' => 'medium',
                'expires_at' => Carbon::now()->addWeek(),
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(6)
            ],
            [
                'type' => 'warning',
                'title' => 'Security Update Available',
                'message' => 'A new security update is available. Please update your system.',
                'icon' => 'fas fa-shield-alt',
                'user_id' => null, // System-wide notification
                'is_read' => false,
                'priority' => 'high',
                'action_url' => '/admin/updates',
                'created_at' => Carbon::now()->subHours(8),
                'updated_at' => Carbon::now()->subHours(8)
            ]
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        $this->command->info('Sample notifications created successfully!');
    }
}
