<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\NotificationService;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        NotificationService::createSystemNotification(
            'success',
            'Database Event Created',
            "Event '{$event->name}' was successfully saved to the database.",
            [
                'event_id' => $event->id,
                'event_data' => $event->toArray(),
                'database_operation' => 'insert'
            ]
        );
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        $changes = $event->getChanges();
        $original = $event->getOriginal();
        
        NotificationService::createSystemNotification(
            'info',
            'Database Event Updated',
            "Event '{$event->name}' was successfully updated in the database.",
            [
                'event_id' => $event->id,
                'changes' => $changes,
                'original' => $original,
                'database_operation' => 'update'
            ]
        );
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        NotificationService::createSystemNotification(
            'warning',
            'Database Event Deleted',
            "Event '{$event->name}' was successfully removed from the database.",
            [
                'event_id' => $event->id,
                'deleted_data' => $event->toArray(),
                'database_operation' => 'delete'
            ]
        );
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        NotificationService::createSystemNotification(
            'success',
            'Event Restored',
            "Event '{$event->name}' was restored from soft delete.",
            [
                'event_id' => $event->id,
                'restored_data' => $event->toArray(),
                'database_operation' => 'restore'
            ]
        );
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        NotificationService::createSystemNotification(
            'danger',
            'Event Permanently Deleted',
            "Event '{$event->name}' was permanently deleted from the database.",
            [
                'event_id' => $event->id,
                'deleted_data' => $event->toArray(),
                'database_operation' => 'force_delete'
            ]
        );
    }
}
