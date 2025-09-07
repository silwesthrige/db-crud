@extends('templates.admin-master')

@section('header_content')
    <title>Calendar - Event Manager</title>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="card-title mb-1">Events Calendar</h2>
                <p class="text-muted mb-0">View and manage events in calendar format</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" id="todayBtn">Today</button>
                <button class="btn btn-outline-primary" id="monthView">Month</button>
                <button class="btn btn-outline-primary" id="weekView">Week</button>
                <button class="btn btn-outline-primary" id="dayView">Day</button>
                <a href="{{ route('events.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Event
                </a>
            </div>
        </div>
        
        <!-- Calendar Legend -->
        <div class="calendar-legend p-3 mb-3 bg-light rounded">
            <div class="d-flex align-items-center gap-4">
                <span class="text-muted me-3">Priority:</span>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger">High</span>
                    <span class="badge bg-warning">Medium</span>
                    <span class="badge bg-success">Low</span>
                </div>
            </div>
        </div>
        
        <!-- FullCalendar Container -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade event-modal" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5 class="modal-title" id="eventModalTitle">Event Details</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="priority-badge" id="eventPriority"></span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="text-muted mb-2">Description</h6>
                        <p id="eventDescription" class="mb-3"></p>
                        
                        <h6 class="text-muted mb-2">Date & Time</h6>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-calendar text-primary"></i>
                                <span id="eventDate"></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-clock text-primary"></i>
                                <span id="eventTime"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Actions</h6>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="editEventBtn">
                                        <i class="fas fa-edit me-2"></i>Edit Event
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="deleteEventBtn">
                                        <i class="fas fa-trash me-2"></i>Delete Event
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" id="duplicateEventBtn">
                                        <i class="fas fa-copy me-2"></i>Duplicate Event
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="calendarLoading" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="z-index: 9999; background: rgba(0,0,0,0.5);">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status"></div>
            <div>Loading events...</div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<style>
    .calendar-legend {
        background: var(--light-color);
    }
    
    :root[data-theme="dark"] .calendar-legend {
        background: var(--card-bg);
        border-color: var(--border-color) !important;
    }
    
    .fc-theme-standard .fc-scrollgrid {
        border-color: var(--border-color);
    }
    
    .fc-col-header-cell {
        background-color: var(--light-color);
    }
    
    :root[data-theme="dark"] .fc-col-header-cell {
        background-color: var(--card-bg);
    }
    
    .fc-daygrid-day:hover {
        background-color: rgba(99, 102, 241, 0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let currentEvent = null;
    
    // Initialize FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false, // We use custom buttons
        height: 'auto',
        events: '/events/calendar-data',
        eventDidMount: function(info) {
            // Add priority class to events
            const priority = info.event.extendedProps.priority;
            if (priority) {
                info.el.classList.add(`priority-${priority.toLowerCase()}`);
            }
            
            // Add tooltip
            info.el.setAttribute('title', info.event.title + '\n' + info.event.extendedProps.description);
        },
        eventClick: function(info) {
            currentEvent = info.event;
            showEventModal(info.event);
        },
        dateClick: function(info) {
            // Navigate to create event page with selected date
            const createUrl = new URL('{{ route('events.create') }}', window.location.origin);
            createUrl.searchParams.set('date', info.dateStr);
            window.location.href = createUrl.toString();
        },
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-main-frame">
                        <div class="fc-event-title-container">
                            <div class="fc-event-title fc-sticky">${arg.event.title}</div>
                        </div>
                       </div>`
            };
        }
    });
    
    calendar.render();
    
    // Store calendar instance globally
    window.calendar = calendar;
    
    // Custom navigation buttons
    document.getElementById('todayBtn').addEventListener('click', () => {
        calendar.today();
    });
    
    document.getElementById('monthView').addEventListener('click', () => {
        calendar.changeView('dayGridMonth');
        updateActiveView('monthView');
    });
    
    document.getElementById('weekView').addEventListener('click', () => {
        calendar.changeView('timeGridWeek');
        updateActiveView('weekView');
    });
    
    document.getElementById('dayView').addEventListener('click', () => {
        calendar.changeView('timeGridDay');
        updateActiveView('dayView');
    });
    
    function updateActiveView(activeBtn) {
        document.querySelectorAll('#monthView, #weekView, #dayView').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(activeBtn).classList.add('active');
    }
    
    // Set initial active view
    updateActiveView('monthView');
    
    // Event modal functions
    function showEventModal(event) {
        const modal = new bootstrap.Modal(document.getElementById('eventModal'));
        
        // Populate modal data
        document.getElementById('eventModalTitle').textContent = event.title;
        document.getElementById('eventDescription').textContent = event.extendedProps.description || 'No description provided';
        document.getElementById('eventDate').textContent = formatDate(event.start);
        document.getElementById('eventTime').textContent = formatTime(event.start);
        
        // Set priority badge
        const priority = event.extendedProps.priority || 'medium';
        const priorityBadge = document.getElementById('eventPriority');
        priorityBadge.textContent = priority.charAt(0).toUpperCase() + priority.slice(1);
        priorityBadge.className = `priority-badge badge-${priority.toLowerCase()}`;
        
        // Setup action buttons
        document.getElementById('editEventBtn').onclick = () => editEvent(event.id);
        document.getElementById('deleteEventBtn').onclick = () => deleteEvent(event.id);
        document.getElementById('duplicateEventBtn').onclick = () => duplicateEvent(event.id);
        
        modal.show();
    }
    
    function formatDate(date) {
        return new Intl.DateTimeFormat('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    }
    
    function formatTime(date) {
        return new Intl.DateTimeFormat('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        }).format(new Date(date));
    }
    
    function editEvent(eventId) {
        window.location.href = `/events/${eventId}/edit`;
    }
    
    function deleteEvent(eventId) {
        if (confirm('Are you sure you want to delete this event?')) {
            showLoading();
            
            fetch(`/events/${eventId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    calendar.refetchEvents();
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    showAlert('Event deleted successfully!', 'success');
                } else {
                    showAlert('Failed to delete event', 'danger');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('An error occurred', 'danger');
            });
        }
    }
    
    function duplicateEvent(eventId) {
        const createUrl = new URL('{{ route('events.create') }}', window.location.origin);
        createUrl.searchParams.set('duplicate', eventId);
        window.location.href = createUrl.toString();
    }
    
    function showLoading() {
        document.getElementById('calendarLoading').classList.remove('d-none');
    }
    
    function hideLoading() {
        document.getElementById('calendarLoading').classList.add('d-none');
    }
    
    function showAlert(message, type) {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 5000);
    }
    
    // Listen for theme changes to update calendar
    document.addEventListener('themeChanged', function(e) {
        calendar.render();
    });
});
</script>
@endpush
