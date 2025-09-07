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
                    <button class="btn-profile dropdown-toggle" type="button" id="profileDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=667eea&color=fff&size=36" alt="Admin" class="profile-img" id="profileImg">
                        <div class="profile-info">
                            <span class="profile-name" id="profileName">Admin User</span>
                            <span class="profile-role" id="profileRole">Administrator</span>
                        </div>
                        <i class="fas fa-chevron-down profile-arrow"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="profileDropdownBtn">
                        <li class="dropdown-header">
                            <div class="user-info-header">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=667eea&color=fff&size=48" alt="Admin" class="header-avatar">
                                <div class="header-info">
                                    <strong>Admin User</strong>
                                    <small class="text-muted">admin@example.com</small>
                                    <span class="badge badge-success">Online</span>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="viewProfile"><i class="fas fa-user me-2"></i>View Profile</a></li>
                        <li><a class="dropdown-item" href="#" id="editProfile"><i class="fas fa-edit me-2"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#" id="changePassword"><i class="fas fa-key me-2"></i>Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="adminSettings"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item" href="#" id="systemInfo"><i class="fas fa-info-circle me-2"></i>System Info</a></li>
                        <li><a class="dropdown-item" href="#" id="activityLog"><i class="fas fa-history me-2"></i>Activity Log</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="helpSupport"><i class="fas fa-question-circle me-2"></i>Help & Support</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>