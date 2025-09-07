<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'user_id',
        'is_read',
        'read_at',
        'data',
        'action_url',
        'priority',
        'expires_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead(Builder $query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSystemWide(Builder $query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeNotExpired(Builder $query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeByPriority(Builder $query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors & Mutators
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger'
        ][$this->priority] ?? 'info';
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function toggleReadStatus()
    {
        if ($this->is_read) {
            $this->markAsUnread();
        } else {
            $this->markAsRead();
        }
    }

    // Static methods for creating notifications
    public static function createForUser($userId, $type, $title, $message, $options = [])
    {
        return self::create(array_merge([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => self::getDefaultIcon($type),
            'priority' => 'medium'
        ], $options));
    }

    public static function createSystemWide($type, $title, $message, $options = [])
    {
        return self::create(array_merge([
            'user_id' => null,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => self::getDefaultIcon($type),
            'priority' => 'medium'
        ], $options));
    }

    public static function getDefaultIcon($type)
    {
        return [
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle',
            'danger' => 'fas fa-exclamation-circle'
        ][$type] ?? 'fas fa-bell';
    }

    // Bulk operations
    public static function markAllAsReadForUser($userId)
    {
        return self::where('user_id', $userId)
                   ->where('is_read', false)
                   ->update([
                       'is_read' => true,
                       'read_at' => now()
                   ]);
    }

    public static function markAllAsUnreadForUser($userId)
    {
        return self::where('user_id', $userId)
                   ->where('is_read', true)
                   ->update([
                       'is_read' => false,
                       'read_at' => null
                   ]);
    }

    public static function deleteAllForUser($userId)
    {
        return self::where('user_id', $userId)->delete();
    }

    public static function getUnreadCountForUser($userId)
    {
        return self::where('user_id', $userId)
                   ->unread()
                   ->notExpired()
                   ->count();
    }

    // Clean up expired notifications
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
