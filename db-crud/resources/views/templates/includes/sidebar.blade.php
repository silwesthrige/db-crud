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
                <a href="{{ url('events') }}" class="nav-link {{ request()->is('events') && !request()->is('events/calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-text">Events</span>
                    <span class="nav-badge">{{ \App\Models\Event::count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('events.calendar') }}" class="nav-link {{ request()->is('events/calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span class="nav-text">Calendar</span>
                </a>
            </li>
            
            @auth
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">User Management</span>
                        @php
                            try {
                                $pendingCount = \App\Models\User::where('status', 'pending')->count();
                            } catch (\Exception $e) {
                                $pendingCount = 0;
                            }
                        @endphp
                        @if($pendingCount > 0)
                            <span class="nav-badge bg-warning">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                @endif
            @endauth
           <!-- <li class="nav-item">
                <a href="{{ url('events/create') }}" class="nav-link {{ request()->is('events/create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span class="nav-text">Add Event</span>
                </a>
            </li> -->
        </ul>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <i class="fas fa-crown text-warning"></i>
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    @else
                        <i class="fas fa-user-slash"></i>
                    @endauth
                </div>
                <div class="user-details">
                    <span class="username">
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Guest User
                        @endauth
                    </span>
                    <span class="user-role">
                        @auth
                            @if(Auth::user()->isAdmin())
                                Super Admin
                            @else
                                User
                            @endif
                        @else
                            Not Logged In
                        @endauth
                    </span>
                </div>
            </div>
        </div>
    </nav>
</aside>