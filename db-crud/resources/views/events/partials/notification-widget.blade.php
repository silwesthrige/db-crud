@php
    $eventNotifications = \App\Models\Notification::where('data->system_notification', false)
        ->orWhere('data', 'like', '%event%')
        ->orWhere('message', 'like', '%event%')
        ->latest()
        ->take(5)
        ->get();
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-header bg-gradient-primary text-white">
        <h6 class="mb-0">
            <i class="fas fa-bell me-2"></i>
            Recent Event Notifications
        </h6>
    </div>
    <div class="card-body p-0">
        @if($eventNotifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($eventNotifications as $notification)
                <div class="list-group-item d-flex align-items-center {{ $notification->is_read ? '' : 'bg-light' }}">
                    <div class="notification-icon me-3">
                        <i class="fas fa-{{ $notification->icon ?? 'bell' }} text-{{ $notification->type == 'success' ? 'success' : ($notification->type == 'danger' ? 'danger' : ($notification->type == 'warning' ? 'warning' : 'info')) }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 text-sm">{{ $notification->title }}</h6>
                        <p class="mb-1 text-muted small">{{ $notification->message }}</p>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @if(!$notification->is_read)
                        <span class="badge bg-primary">New</span>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-bell-slash text-muted fa-2x mb-2"></i>
                <p class="text-muted mb-0">No event notifications yet</p>
                <small class="text-muted">Notifications will appear here when you create, edit, or delete events</small>
            </div>
        @endif
    </div>
    @if($eventNotifications->count() > 0)
    <div class="card-footer text-center">
        <small class="text-muted">
            Showing {{ $eventNotifications->count() }} recent notifications
        </small>
    </div>
    @endif
</div>
