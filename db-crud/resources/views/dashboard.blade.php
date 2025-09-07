@extends('templates.admin-master')

@section('header_content')
    <title>Dashboard - Event Manager</title>
@endsection

@section('content')
<div class="dashboard-header">
    <div class="page-title">
        <h2>Dashboard Overview</h2>
        <p class="page-description">Welcome back! Here's what's happening with your events.</p>
    </div>
    <div class="header-actions">
        <a href="{{ url('events/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Event
        </a>
    </div>
</div>

<div class="dashboard-stats">
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card total-events">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ \App\Models\Event::count() }}</h3>
                    <p>Total Events</p>
                    <small class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i> +12% from last month
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card high-priority">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ \App\Models\Event::where('priority', 'High')->count() }}</h3>
                    <p>High Priority</p>
                    <small class="stat-trend neutral">
                        <i class="fas fa-minus"></i> No change
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card medium-priority">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ \App\Models\Event::where('priority', 'Medium')->count() }}</h3>
                    <p>Medium Priority</p>
                    <small class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i> +8% this week
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="stat-card low-priority">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ \App\Models\Event::where('priority', 'Low')->count() }}</h3>
                    <p>Low Priority</p>
                    <small class="stat-trend negative">
                        <i class="fas fa-arrow-down"></i> -5% this week
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-line me-2"></i>Recent Events</h4>
                    <a href="{{ url('events') }}" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    @if(\App\Models\Event::count() > 0)
                        <div class="recent-events">
                            @foreach(\App\Models\Event::latest()->take(5)->get() as $event)
                            <div class="event-item">
                                <div class="event-details">
                                    <h6>{{ $event->name }}</h6>
                                    <p>{{ \Illuminate\Support\Str::limit($event->description, 60) }}</p>
                                    <small class="event-date">
                                        <i class="fas fa-calendar me-1"></i>{{ $event->event_date }}
                                    </small>
                                </div>
                                <div class="event-priority">
                                    <span class="priority-badge priority-{{ strtolower($event->priority) }}">
                                        {{ $event->priority }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-plus"></i>
                            <h5>No events yet</h5>
                            <p>Start by creating your first event</p>
                            <a href="{{ url('events/create') }}" class="btn btn-primary">Create Event</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="content-card">
                <div class="card-header">
                    <h4><i class="fas fa-tasks me-2"></i>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ url('events/create') }}" class="action-btn">
                            <i class="fas fa-plus-circle"></i>
                            <span>Create New Event</span>
                        </a>
                        <a href="{{ url('events') }}" class="action-btn">
                            <i class="fas fa-list"></i>
                            <span>View All Events</span>
                        </a>
                        <a href="#" class="action-btn">
                            <i class="fas fa-download"></i>
                            <span>Export Data</span>
                        </a>
                        <a href="#" class="action-btn">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="content-card mt-3">
                <div class="card-header">
                    <h4><i class="fas fa-info-circle me-2"></i>System Info</h4>
                </div>
                <div class="card-body">
                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">Laravel Version</span>
                            <span class="info-value">{{ app()->version() }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">PHP Version</span>
                            <span class="info-value">{{ phpversion() }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Database</span>
                            <span class="info-value">MySQL</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
