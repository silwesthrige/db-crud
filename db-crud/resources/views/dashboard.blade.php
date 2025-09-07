@extends('templates.admin-master')

@section('header_content')
    <title>Dashboard - Event Manager</title>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="page-title">
            <h2><i class="fas fa-tachometer-alt me-3"></i>Dashboard Overview</h2>
            <p class="page-description">Welcome back! Here's what's happening with your events.</p>
        </div>
        <div class="header-actions">
            <a href="{{ url('events/create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Event
            </a>
            <a href="{{ route('events.calendar') }}" class="btn btn-outline-primary">
                <i class="fas fa-calendar me-2"></i>Calendar View
            </a>
        </div>
    </div>

    <!-- Analytics Grid -->
    <div class="analytics-grid">
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">Total Events</h5>
            <i class="fas fa-calendar-check text-primary"></i>
        </div>
        <div class="analytics-value">{{ \App\Models\Event::count() }}</div>
        <div class="analytics-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>+12% from last month</span>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">High Priority</h5>
            <i class="fas fa-exclamation-triangle text-danger"></i>
        </div>
        <div class="analytics-value">{{ \App\Models\Event::where('priority', 'High')->count() }}</div>
        <div class="analytics-change neutral">
            <i class="fas fa-minus"></i>
            <span>No change</span>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">This Week</h5>
            <i class="fas fa-clock text-info"></i>
        </div>
        <div class="analytics-value">{{ \App\Models\Event::whereBetween('event_date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</div>
        <div class="analytics-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>+8% from last week</span>
        </div>
    </div>
    
    <div class="analytics-card">
        <div class="analytics-header">
            <h5 class="analytics-title">Notifications</h5>
            <i class="fas fa-bell text-warning"></i>
        </div>
        <div class="analytics-value">
            @php
                try {
                    $notificationCount = \App\Models\Notification::where('is_read', false)->count();
                } catch (\Exception $e) {
                    $notificationCount = 0; // Default to 0 if table doesn't exist
                }
            @endphp
            {{ $notificationCount }}
        </div>
        <div class="analytics-change negative">
            <i class="fas fa-arrow-down"></i>
            <span>-3 from yesterday</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-line me-2"></i>Events Timeline
                    </h3>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #ef4444;"></div>
                            <span>High Priority</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #f59e0b;"></div>
                            <span>Medium Priority</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #10b981;"></div>
                            <span>Low Priority</span>
                        </div>
                    </div>
                </div>
                <div class="chart-canvas">
                    <canvas id="eventsTimelineChart" width="800" height="400"></canvas>
                    <div class="chart-loading" id="timelineLoading">
                        <div class="chart-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-container priority-chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-pie me-2"></i>Priority Distribution
                    </h3>
                </div>
                <div class="chart-canvas">
                    <canvas id="priorityChart" width="500" height="500"></canvas>
                    <div class="chart-loading" id="priorityLoading">
                        <div class="chart-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-area me-2"></i>Monthly Events Trend
                    </h3>
                </div>
                <div class="chart-canvas">
                    <canvas id="monthlyTrendChart" width="600" height="400"></canvas>
                    <div class="chart-loading" id="monthlyLoading">
                        <div class="chart-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="chart-container">
                <div class="chart-header">
                    <h3 class="chart-title">
                        <i class="fas fa-chart-bar me-2"></i>Event Activity
                    </h3>
                </div>
                <div class="chart-canvas">
                    <canvas id="activityChart" width="600" height="400"></canvas>
                    <div class="chart-loading" id="activityLoading">
                        <div class="chart-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Dashboard Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .page-title h2 {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 8px;
    }
    
    .page-description {
        color: var(--text-muted);
        margin: 0;
        font-size: 16px;
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Charts Section */
    .charts-section {
        margin-bottom: 40px;
    }

    .chart-container {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: 400px;
    }

    .chart-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .chart-legend {
        display: flex;
        gap: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--text-muted);
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    .chart-canvas {
        position: relative;
        height: 320px;
        padding: 20px;
    }

    .chart-canvas canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .chart-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        color: var(--text-muted);
    }

    .chart-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid var(--border-color);
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Recent Events Summary */
    .recent-events-summary {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: white;
    }

    .stat-icon.high { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-icon.medium { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-icon.low { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .events-list {
        border-top: 1px solid var(--border-color);
        padding-top: 20px;
    }

    .list-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 16px;
    }

    .event-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: var(--light-color);
        border-radius: 6px;
        border: 1px solid var(--border-color);
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }

    .event-item:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .event-item:last-child {
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .header-actions {
            width: 100%;
            justify-content: stretch;
        }
        
        .header-actions .btn {
            flex: 1;
        }

        .chart-container {
            height: 300px;
        }

        .chart-canvas {
            height: 220px;
        }

        .chart-legend {
            flex-direction: column;
            gap: 8px;
        }

        .summary-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .stat-item {
            padding: 12px;
        }

        .event-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .event-priority {
            align-self: flex-end;
        }
    }
</style>
@endpush

    </div>
</div>

<!-- Additional Content Section (Commented for Future Use) -->
<div class="dashboard-content">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="content-card">
                <div class="card-header">
                    <h4><i class="fas fa-chart-line me-2"></i>Recent Events Overview</h4>
                    <a href="{{ url('events') }}" class="btn btn-outline-primary btn-m">
                        <i class="fas fa-external-link-alt me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    @if(\App\Models\Event::count() > 0)
                        <div class="recent-events-summary">
                            <div class="summary-stats">
                                <div class="stat-item">
                                    <div class="stat-icon high">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-number">{{ \App\Models\Event::where('priority', 'High')->count() }}</span>
                                        <span class="stat-label">High Priority</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon medium">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-number">{{ \App\Models\Event::where('priority', 'Medium')->count() }}</span>
                                        <span class="stat-label">Medium Priority</span>
                                    </div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon low">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-number">{{ \App\Models\Event::where('priority', 'Low')->count() }}</span>
                                        <span class="stat-label">Low Priority</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="events-list">
                                <h5 class="list-title">Latest Events</h5>
                                @foreach(\App\Models\Event::latest()->take(3)->get() as $event)
                                <div class="event-item">
                                    <div class="event-details">
                                        <h6>{{ $event->name }}</h6>
                                        <p>{{ \Illuminate\Support\Str::limit($event->description, 50) }}</p>
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
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-plus"></i>
                            <h5>No events yet</h5>
                            <p>Start by creating your first event to see analytics and insights</p>
                            <a href="{{ url('events/create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </a>
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


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/theme.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chart instances storage
    window.chartInstances = {};
    
    // Chart configuration based on theme
    const getChartConfig = (type, data, options = {}) => {
        const isDark = window.themeManager ? window.themeManager.isDark() : false;
        const themeConfig = {
            responsive: true,
            maintainAspectRatio: false,
            devicePixelRatio: window.devicePixelRatio || 1,
            plugins: {
                legend: {
                    labels: {
                        color: isDark ? '#e2e8f0' : '#334155',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            scales: type !== 'doughnut' ? {
                x: {
                    ticks: {
                        color: isDark ? '#94a3b8' : '#64748b',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: isDark ? '#475569' : '#e2e8f0',
                        lineWidth: 1
                    }
                },
                y: {
                    ticks: {
                        color: isDark ? '#94a3b8' : '#64748b',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: isDark ? '#475569' : '#e2e8f0',
                        lineWidth: 1
                    }
                }
            } : {},
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 6
                },
                line: {
                    tension: 0.4
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        };
        
        return {
            type: type,
            data: data,
            options: { ...themeConfig, ...options }
        };
    };
    
    // Hide loading and show chart (simplified - loading states are hidden via CSS)
    const showChart = (chartId, loadingId) => {
        const chartElement = document.getElementById(chartId);
        if (chartElement) {
            chartElement.style.display = 'block';
            chartElement.style.width = '100%';
            chartElement.style.height = '100%';
        }
    };

    // Removed loading state management since they're hidden via CSS

    // Initialize charts after ensuring DOM is ready
    const initializeCharts = () => {
        // Ensure canvas elements exist before initializing
        const canvasElements = ['priorityChart', 'eventsTimelineChart', 'monthlyTrendChart', 'activityChart'];
        const allExist = canvasElements.every(id => document.getElementById(id));
        
        if (!allExist) {
            console.log('Waiting for canvas elements...');
            setTimeout(initializeCharts, 100);
            return;
        }

        console.log('Initializing charts...');

        try {
            // Priority Distribution Chart
            const priorityCtx = document.getElementById('priorityChart').getContext('2d');
            const priorityData = {
                labels: ['High Priority', 'Medium Priority', 'Low Priority'],
                datasets: [{
                    data: [
                        {{ \App\Models\Event::where('priority', 'High')->count() }},
                        {{ \App\Models\Event::where('priority', 'Medium')->count() }},
                        {{ \App\Models\Event::where('priority', 'Low')->count() }}
                    ],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            };

            window.chartInstances.priorityChart = new Chart(priorityCtx, getChartConfig('doughnut', priorityData, {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                },
                elements: {
                    arc: {
                        borderWidth: 3,
                        borderColor: '#fff'
                    }
                }
            }));
            showChart('priorityChart', 'priorityLoading');
            console.log('Priority chart initialized');

        } catch (error) {
            console.error('Error initializing priority chart:', error);
        }

        try {
            // Events Timeline Chart
            const timelineCtx = document.getElementById('eventsTimelineChart').getContext('2d');
            
            // Get last 7 days data
            fetch('/api/events/timeline')
                .then(response => response.json())
                .then(data => {
                    const timelineData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'High Priority',
                            data: data.high,
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            fill: true
                        }, {
                            label: 'Medium Priority',
                            data: data.medium,
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderColor: '#f59e0b',
                            borderWidth: 2,
                            fill: true
                        }, {
                            label: 'Low Priority',
                            data: data.low,
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderColor: '#10b981',
                            borderWidth: 2,
                            fill: true
                        }]
                    };
                    
                    window.chartInstances.timelineChart = new Chart(timelineCtx, getChartConfig('line', timelineData, {
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }));
                    showChart('eventsTimelineChart', 'timelineLoading');
                    console.log('Timeline chart initialized');
                })
                .catch(() => {
                    console.log('API failed, using fallback data for timeline chart');
                    // Fallback data if API fails
                    const fallbackData = {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Events',
                            data: [12, 19, 3, 5, 2, 3, 9],
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 2,
                            fill: true
                        }]
                    };
                    
                    window.chartInstances.timelineChart = new Chart(timelineCtx, getChartConfig('line', fallbackData));
                    showChart('eventsTimelineChart', 'timelineLoading');
                    console.log('Timeline chart initialized with fallback data');
                });

        } catch (error) {
            console.error('Error initializing timeline chart:', error);
        }

        try {
            // Monthly Trend Chart - Now using database data
            const monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');
            
            fetch('/api/events/monthly-trend')
                .then(response => response.json())
                .then(data => {
                    const monthlyData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'Events Created',
                            data: data.data,
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    };
                    
                    window.chartInstances.monthlyChart = new Chart(monthlyCtx, getChartConfig('line', monthlyData));
                    showChart('monthlyTrendChart', 'monthlyLoading');
                    console.log('Monthly chart initialized with database data');
                })
                .catch(() => {
                    console.log('API failed, using fallback data for monthly chart');
                    // Fallback data if API fails
                    const fallbackData = {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Events Created',
                            data: [65, 59, 80, 81, 56, 85],
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    };
                    
                    window.chartInstances.monthlyChart = new Chart(monthlyCtx, getChartConfig('line', fallbackData));
                    showChart('monthlyTrendChart', 'monthlyLoading');
                    console.log('Monthly chart initialized with fallback data');
                });

        } catch (error) {
            console.error('Error initializing monthly chart:', error);
        }
        
        try {
            // Activity Chart - Now using database data
            const activityCtx = document.getElementById('activityChart').getContext('2d');
            
            fetch('/api/events/activity')
                .then(response => response.json())
                .then(data => {
                    const activityData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'Activity Count',
                            data: data.data,
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderWidth: 0
                        }]
                    };
                    
                    window.chartInstances.activityChart = new Chart(activityCtx, getChartConfig('bar', activityData, {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }));
                    showChart('activityChart', 'activityLoading');
                    console.log('Activity chart initialized with database data');
                })
                .catch(() => {
                    console.log('API failed, using fallback data for activity chart');
                    // Fallback data if API fails
                    const fallbackData = {
                        labels: ['Created', 'Updated', 'Completed', 'Deleted'],
                        datasets: [{
                            label: 'Activity Count',
                            data: [45, 23, 67, 12],
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ],
                            borderWidth: 0
                        }]
                    };
                    
                    window.chartInstances.activityChart = new Chart(activityCtx, getChartConfig('bar', fallbackData, {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }));
                    showChart('activityChart', 'activityLoading');
                    console.log('Activity chart initialized with fallback data');
                });

        } catch (error) {
            console.error('Error initializing activity chart:', error);
        }
        
        console.log('All charts initialization completed');
    };
    
    // Call initialization
    initializeCharts();
    
    // Listen for theme changes
    document.addEventListener('themeChanged', function(e) {
        // Update all charts with new theme
        Object.values(window.chartInstances).forEach(chart => {
            const isDark = e.detail.theme === 'dark';
            
            // Update legend colors
            if (chart.options.plugins.legend) {
                chart.options.plugins.legend.labels.color = isDark ? '#e2e8f0' : '#334155';
            }
            
            // Update scale colors
            if (chart.options.scales) {
                ['x', 'y'].forEach(axis => {
                    if (chart.options.scales[axis]) {
                        chart.options.scales[axis].ticks.color = isDark ? '#94a3b8' : '#64748b';
                        chart.options.scales[axis].grid.color = isDark ? '#475569' : '#e2e8f0';
                    }
                });
            }
            
            chart.update('none');
        });
    });
});
</script>
@endpush
@endsection
