<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the current user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = 1; // For demo purposes, using user ID 1. In real app, use auth()->id()
        
        $notifications = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id'); // Include system-wide notifications
            })
            ->notExpired()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'unread' => !$notification->is_read,
                    'time' => $notification->time_ago,
                    'timestamp' => $notification->created_at->timestamp * 1000, // JavaScript timestamp
                    'priority' => $notification->priority,
                    'action_url' => $notification->action_url,
                    'data' => $notification->data
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => Notification::getUnreadCountForUser($userId)
        ]);
    }

    /**
     * Get unread count for the current user
     */
    public function getUnreadCount(): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $count = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->unread()
            ->notExpired()
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Mark a specific notification as read/unread
     */
    public function toggleRead(Request $request, $id): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->toggleReadStatus();

        return response()->json([
            'success' => true,
            'message' => $notification->is_read ? 'Marked as read' : 'Marked as unread',
            'is_read' => $notification->is_read,
            'unread_count' => Notification::getUnreadCountForUser($userId)
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $updated = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} notifications as read",
            'updated_count' => $updated,
            'unread_count' => 0
        ]);
    }

    /**
     * Mark all notifications as unread
     */
    public function markAllAsUnread(): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $updated = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->read()
            ->update([
                'is_read' => false,
                'read_at' => null
            ]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} notifications as unread",
            'updated_count' => $updated,
            'unread_count' => Notification::getUnreadCountForUser($userId)
        ]);
    }

    /**
     * Delete a specific notification
     */
    public function destroy($id): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $notification = Notification::where('id', $id)
            ->where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->first();

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
            'unread_count' => Notification::getUnreadCountForUser($userId)
        ]);
    }

    /**
     * Delete all notifications
     */
    public function deleteAll(): JsonResponse
    {
        $userId = 1; // For demo purposes
        
        $deleted = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereNull('user_id');
            })
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} notifications",
            'deleted_count' => $deleted,
            'unread_count' => 0
        ]);
    }

    /**
     * Create a new notification (for testing purposes)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', Rule::in(['success', 'warning', 'info', 'danger'])],
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
            'action_url' => 'nullable|url',
            'expires_at' => 'nullable|date|after:now',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $notification = Notification::create([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'icon' => $request->icon ?? Notification::getDefaultIcon($request->type),
            'priority' => $request->priority ?? 'medium',
            'action_url' => $request->action_url,
            'expires_at' => $request->expires_at,
            'user_id' => $request->user_id ?? 1 // Default to user 1 for demo
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'notification' => [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'icon' => $notification->icon,
                'unread' => !$notification->is_read,
                'time' => $notification->time_ago,
                'timestamp' => $notification->created_at->timestamp * 1000,
                'priority' => $notification->priority,
                'action_url' => $notification->action_url
            ]
        ], 201);
    }
}
