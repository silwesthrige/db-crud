@extends('templates.admin-master')
@section('header_content')
<title>Event List</title>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="card-title mb-1">
                        Manage Events
                    </h2>
                    <p class="text-muted mb-0">Create, edit, and manage your events</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ url('/events/import') }}" class="btn btn-outline-info">
                        <i class="fas fa-upload me-2"></i>Import CSV
                    </a>
                    <a href="{{ url('/events/export') }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-2"></i>Export CSV
                    </a>
                    <a href="{{url('/events/create')}}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Event
                    </a>
                </div>
            </div>

            <!-- Advanced Search & Filtering -->
            <div class="search-filters-card mb-4">
                <div class="card border-0 bg-light">
                    <div class="card-body py-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-search me-1"></i>Search Events
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchInput" 
                                           placeholder="Search by name or description..." 
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-filter me-1"></i>Priority Filter
                                </label>
                                <select class="form-select" id="priorityFilter">
                                    <option value="">All</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1"></i>Date Filter
                                </label>
                                <input type="date" class="form-control" id="dateFilter">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-outline-secondary flex-fill" id="clearFilters" title="Clear Filters">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-info dropdown-toggle" type="button" 
                                                id="viewOptions" data-bs-toggle="dropdown">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" id="tableView">
                                                <i class="fas fa-table me-2"></i>Table View
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" id="cardView">
                                                <i class="fas fa-th-large me-2"></i>Card View
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <span id="eventCount">{{ count($events) }}</span> event(s) found
                                    </small>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="sortOrder" id="sortAsc" autocomplete="off" checked>
                                        <label class="btn btn-outline-secondary" for="sortAsc" title="Sort Ascending">
                                            <i class="fas fa-sort-alpha-down"></i>
                                        </label>
                                        <input type="radio" class="btn-check" name="sortOrder" id="sortDesc" autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="sortDesc" title="Sort Descending">
                                            <i class="fas fa-sort-alpha-up"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive" id="tableView">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $events as $event )
                        <tr>
                            <td><strong>{{ $event->name }}</strong></td>
                            <td>{{ Str::limit($event->description, 50) }}</td>
                            <td>
                                @if($event->priority == 'High')
                                    <span class="badge badge-priority badge-high">{{ $event->priority }}</span>
                                @elseif($event->priority == 'Medium')
                                    <span class="badge badge-priority badge-medium">{{ $event->priority }}</span>
                                @else
                                    <span class="badge badge-priority badge-low">{{ $event->priority }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ url('/events/update/'.$event->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Edit Event">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ url('/events/delete/'.$event->id) }}" class="btn btn-sm btn-outline-danger" title="Delete Event" onclick="return confirm('Are you sure you want to delete this event?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card View (Initially Hidden) -->
            <div class="card-view-container d-none" id="cardViewContainer">
                <div class="row g-3" id="eventsCardContainer">
                    @foreach ($events as $event)
                    <div class="col-md-6 col-lg-4 event-card-item" 
                         data-name="{{ strtolower($event->name) }}" 
                         data-description="{{ strtolower($event->description) }}" 
                         data-priority="{{ $event->priority }}" 
                         data-date="{{ $event->event_date }}">
                        <div class="card h-100 event-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">{{ $event->name }}</h6>
                                @if($event->priority == 'High')
                                    <span class="badge badge-priority badge-high">{{ $event->priority }}</span>
                                @elseif($event->priority == 'Medium')
                                    <span class="badge badge-priority badge-medium">{{ $event->priority }}</span>
                                @else
                                    <span class="badge badge-priority badge-low">{{ $event->priority }}</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="card-text text-muted">{{ Str::limit($event->description, 100) }}</p>
                                <div class="d-flex align-items-center text-muted mb-3">
                                    <i class="fas fa-calendar me-2"></i>
                                    <small>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</small>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex gap-2">
                                    <a href="{{ url('/events/update/'.$event->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <a href="{{ url('/events/delete/'.$event->id) }}" class="btn btn-sm btn-outline-danger flex-fill" onclick="return confirm('Are you sure you want to delete this event?')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('optional_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all necessary elements
    const searchInput = document.getElementById('searchInput');
    const priorityFilter = document.getElementById('priorityFilter');
    const dateFilter = document.getElementById('dateFilter');
    const clearFilters = document.getElementById('clearFilters');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    const tableContainer = document.querySelector('.table-responsive');
    const cardContainer = document.getElementById('cardViewContainer');
    const eventCount = document.getElementById('eventCount');
    const sortAsc = document.getElementById('sortAsc');
    const sortDesc = document.getElementById('sortDesc');

    let allEvents = [];
    let currentView = 'table';

    // Initialize events data
    function initializeEvents() {
        const tableRows = document.querySelectorAll('tbody tr');
        const cardItems = document.querySelectorAll('.event-card-item');
        
        allEvents = Array.from(tableRows).map((row, index) => {
            const cells = row.querySelectorAll('td');
            const cardItem = cardItems[index];
            
            return {
                name: cells[0].textContent.trim(),
                description: cells[1].textContent.trim(),
                priority: cells[2].querySelector('.badge').textContent.trim(),
                date: cells[3].textContent.trim(),
                dateValue: cardItem ? cardItem.dataset.date : '',
                tableRow: row,
                cardItem: cardItem
            };
        });
    }

    // Filter and search functionality
    function filterEvents() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedPriority = priorityFilter.value;
        const selectedDate = dateFilter.value;
        
        let filteredEvents = allEvents.filter(event => {
            const matchesSearch = !searchTerm || 
                event.name.toLowerCase().includes(searchTerm) || 
                event.description.toLowerCase().includes(searchTerm);
            
            const matchesPriority = !selectedPriority || event.priority === selectedPriority;
            
            const matchesDate = !selectedDate || event.dateValue === selectedDate;
            
            return matchesSearch && matchesPriority && matchesDate;
        });

        // Sort events
        const isAscending = sortAsc.checked;
        filteredEvents.sort((a, b) => {
            const comparison = a.name.localeCompare(b.name);
            return isAscending ? comparison : -comparison;
        });

        // Update display
        updateEventDisplay(filteredEvents);
        updateEventCount(filteredEvents.length);
    }

    // Update event display based on current view
    function updateEventDisplay(filteredEvents) {
        // Hide all events first
        allEvents.forEach(event => {
            event.tableRow.style.display = 'none';
            if (event.cardItem) event.cardItem.style.display = 'none';
        });

        // Show filtered events
        filteredEvents.forEach(event => {
            if (currentView === 'table') {
                event.tableRow.style.display = '';
            } else {
                if (event.cardItem) event.cardItem.style.display = '';
            }
        });
    }

    // Update event count
    function updateEventCount(count) {
        eventCount.textContent = count;
    }

    // Switch between table and card view
    function switchView(view) {
        currentView = view;
        
        if (view === 'table') {
            tableContainer.classList.remove('d-none');
            cardContainer.classList.add('d-none');
            tableView.classList.add('active');
            cardView.classList.remove('active');
        } else {
            tableContainer.classList.add('d-none');
            cardContainer.classList.remove('d-none');
            cardView.classList.add('active');
            tableView.classList.remove('active');
        }
        
        filterEvents(); // Reapply filters for new view
    }

    // Clear all filters
    function clearAllFilters() {
        searchInput.value = '';
        priorityFilter.value = '';
        dateFilter.value = '';
        sortAsc.checked = true;
        filterEvents();
    }

    // Event listeners
    searchInput.addEventListener('input', filterEvents);
    priorityFilter.addEventListener('change', filterEvents);
    dateFilter.addEventListener('change', filterEvents);
    sortAsc.addEventListener('change', filterEvents);
    sortDesc.addEventListener('change', filterEvents);
    clearFilters.addEventListener('click', clearAllFilters);
    
    if (tableView) tableView.addEventListener('click', (e) => {
        e.preventDefault();
        switchView('table');
    });
    
    if (cardView) cardView.addEventListener('click', (e) => {
        e.preventDefault();
        switchView('card');
    });

    // Initialize
    initializeEvents();
    
    // Add search highlighting
    function highlightSearchTerm(text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    // Enhanced search with highlighting
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        allEvents.forEach(event => {
            // Reset highlighting
            const nameCell = event.tableRow.querySelector('td:first-child strong');
            const descCell = event.tableRow.querySelector('td:nth-child(2)');
            
            if (nameCell && descCell) {
                const originalName = nameCell.dataset.original || nameCell.textContent;
                const originalDesc = descCell.dataset.original || descCell.textContent;
                
                nameCell.dataset.original = originalName;
                descCell.dataset.original = originalDesc;
                
                if (searchTerm) {
                    nameCell.innerHTML = highlightSearchTerm(originalName, searchTerm);
                    descCell.innerHTML = highlightSearchTerm(originalDesc, searchTerm);
                } else {
                    nameCell.innerHTML = originalName;
                    descCell.innerHTML = originalDesc;
                }
            }
        });
        
        filterEvents();
    });
});
</script>
@endsection

