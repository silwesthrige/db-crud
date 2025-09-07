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
                    <button class="btn-profile dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=667eea&color=fff" alt="Admin" class="profile-img">
                        <span class="profile-name">Admin</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>