<header class="admin-topbar">
    <div class="topbar-content">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="admin-title">
               <!-- <i class="fas fa-tachometer-alt me-2"></i> -->
                Admin Dashboard
            </h1>
        </div>
        <div class="topbar-right">
            <div class="admin-actions">
                <!-- Dark Mode Toggle -->
                <button class="btn-icon" id="themeToggle" title="Toggle Dark Mode">
                    <i class="fas fa-moon" id="themeIcon"></i>
                </button>
                
                <div class="notification-dropdown">
                    <button class="btn-icon" id="notificationBtn" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge" id="notificationCount">0</span>
                    </button>
                    <div class="notification-panel" id="notificationPanel">
                        <div class="notification-header">
                            <h6 class="notification-title">Notifications</h6>
                            <div class="notification-header-actions">
                                <a href="#" class="mark-all-read" id="markAllRead" title="Mark all as read">
                                    <i class="fas fa-envelope-open"></i>
                                </a>
                                <a href="#" class="mark-all-unread" id="markAllUnread" title="Mark all as unread">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <a href="#" class="clear-all-notifications" id="clearAllNotifications" title="Clear all notifications">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <!-- Notifications will be loaded here -->
                        </div>
                      <!--  <div class="notification-footer">
                            <a href="#" class="view-all-notifications">View all notifications</a>
                        </div> -->
                    </div>
                </div>
                <div class="admin-profile dropdown">
                    <button class="btn-profile dropdown-toggle" type="button" id="profileDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="@auth https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&size=36 @else https://ui-avatars.com/api/?name=Guest&background=6c757d&color=fff&size=36 @endauth" alt="Profile" class="profile-img" id="profileImg">
                        <div class="profile-info">
                            <span class="profile-name" id="profileName">
                                @auth
                                    {{ Auth::user()->name }}
                                @else
                                    Guest User
                                @endauth
                            </span>
                            <span class="profile-role" id="profileRole">
                                @auth
                                    @if(Auth::user()->isAdmin())
                                        Administrator
                                    @else
                                        User
                                    @endif
                                @else
                                    Not Logged In
                                @endauth
                            </span>
                        </div>
                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="profileDropdownBtn">
                        @auth
                        <li class="dropdown-header">
                            <div class="user-info-header">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&size=48" alt="{{ Auth::user()->name }}" class="header-avatar">
                                <div class="header-info">
                                    <strong>{{ Auth::user()->name }}</strong>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                    <span class="badge badge-success">
                                        @if(Auth::user()->isAdmin())
                                            Admin
                                        @else
                                            User
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="viewProfile"><i class="fas fa-user me-2"></i>View Profile</a></li>
                        <li><a class="dropdown-item" href="#" id="editProfile"><i class="fas fa-edit me-2"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#" id="changePassword"><i class="fas fa-key me-2"></i>Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        
                        @if(Auth::user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i>User Management</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        
                        <li><a class="dropdown-item" href="#" id="adminSettings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="#" id="helpSupport"><i class="fas fa-question-circle me-2"></i>Help & Support</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;" onclick="return confirm('Are you sure you want to logout?')">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                        @else
                        <li class="dropdown-header">
                            <div class="user-info-header">
                                <img src="https://ui-avatars.com/api/?name=Guest&background=6c757d&color=fff&size=48" alt="Guest" class="header-avatar">
                                <div class="header-info">
                                    <strong>Guest User</strong>
                                    <small class="text-muted">Please login to continue</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
// Handle logout form styling
document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.querySelector('#logoutForm button');
    if (logoutButton) {
        // Add hover effects
        logoutButton.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
        });
        logoutButton.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    }
});
</script>