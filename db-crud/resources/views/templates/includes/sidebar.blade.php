<aside class="admin-sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand">
           <!-- <i class="fas fa-cube"></i> -->
            <span class="brand-text">Event Manager</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('events') }}" class="nav-link {{ request()->is('events*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-text">Events</span>
                    <span class="nav-badge">{{ \App\Models\Event::count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('events/create') }}" class="nav-link {{ request()->is('events/create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span class="nav-text">Add Event</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <span class="username">Administrator</span>
                    <span class="user-role">Super Admin</span>
                </div>
            </div>
        </div>
    </nav>
</aside>