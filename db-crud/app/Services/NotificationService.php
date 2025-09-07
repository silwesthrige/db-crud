<?php

namespace App\Services;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create event-related notifications with predefined templates
     */
    public static function createEventNotification($operation, $eventName, $eventData = [], $success = true, $userId = 1)
    {
        $templates = self::getEventNotificationTemplates();
        
        $key = $operation . ($success ? '_success' : '_failure');
        
        if (!isset($templates[$key])) {
            return false;
        }
        
        $template = $templates[$key];
        
        try {
            return Notification::create([
                'type' => $template['type'],
                'title' => $template['title'],
                'message' => str_replace('{event_name}', $eventName, $template['message']),
                'icon' => $template['icon'],
                'user_id' => $userId,
                'is_read' => false,
                'data' => array_merge($eventData, [
                    'operation' => $operation,
                    'success' => $success,
                    'timestamp' => now()->toISOString()
                ]),
                'action_url' => $template['action_url'],
                'priority' => $template['priority'],
                'expires_at' => now()->addDays(7)
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create event notification: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create bulk notifications for multiple operations
     */
    public static function createBulkEventNotification($operations, $userId = 1)
    {
        $count = count($operations);
        
        try {
            return Notification::create([
                'type' => 'info',
                'title' => 'Bulk Event Operations',
                'message' => "Completed {$count} event operations successfully.",
                'icon' => 'tasks',
                'user_id' => $userId,
                'is_read' => false,
                'data' => [
                    'operations' => $operations,
                    'count' => $count,
                    'timestamp' => now()->toISOString()
                ],
                'action_url' => url('/events'),
                'priority' => 'medium',
                'expires_at' => now()->addDays(7)
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create bulk event notification: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get predefined notification templates for events
     */
    private static function getEventNotificationTemplates()
    {
        return [
            'create_success' => [
                'type' => 'success',
                'title' => 'Event Created',
                'message' => "Event '{event_name}' has been created successfully.",
                'icon' => 'calendar-plus',
                'action_url' => url('/events'),
                'priority' => 'medium'
            ],
            'create_failure' => [
                'type' => 'danger',
                'title' => 'Event Creation Failed',
                'message' => "Failed to create event '{event_name}'. Please try again.",
                'icon' => 'exclamation-triangle',
                'action_url' => url('/events/create'),
                'priority' => 'high'
            ],
            'update_success' => [
                'type' => 'info',
                'title' => 'Event Updated',
                'message' => "Event '{event_name}' has been updated successfully.",
                'icon' => 'edit',
                'action_url' => url('/events'),
                'priority' => 'medium'
            ],
            'update_failure' => [
                'type' => 'danger',
                'title' => 'Event Update Failed',
                'message' => "Failed to update event '{event_name}'. Please try again.",
                'icon' => 'exclamation-triangle',
                'action_url' => url('/events'),
                'priority' => 'high'
            ],
            'delete_success' => [
                'type' => 'warning',
                'title' => 'Event Deleted',
                'message' => "Event '{event_name}' has been deleted successfully.",
                'icon' => 'trash',
                'action_url' => url('/events'),
                'priority' => 'medium'
            ],
            'delete_failure' => [
                'type' => 'danger',
                'title' => 'Event Deletion Failed',
                'message' => "Failed to delete event '{event_name}'. Please try again.",
                'icon' => 'exclamation-triangle',
                'action_url' => url('/events'),
                'priority' => 'high'
            ]
        ];
    }
    
    /**
     * Create system-level notifications for event management
     */
    public static function createSystemNotification($type, $title, $message, $data = [], $userId = 1)
    {
        try {
            return Notification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'icon' => 'cog',
                'user_id' => $userId,
                'is_read' => false,
                'data' => array_merge($data, [
                    'system_notification' => true,
                    'timestamp' => now()->toISOString()
                ]),
                'action_url' => url('/events'),
                'priority' => 'low',
                'expires_at' => now()->addDays(30)
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create system notification: ' . $e->getMessage());
            return false;
        }
    }
}
