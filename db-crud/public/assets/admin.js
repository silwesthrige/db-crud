// Admin Dashboard JavaScript - Database Driven Notifications
document.addEventListener("DOMContentLoaded", function () {
    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("show");
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle("show");
            }
        });
    }

    // Close sidebar when clicking on overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function () {
            sidebar.classList.remove("show");
            sidebarOverlay.classList.remove("show");
        });
    }

    // Notification System - Database Driven
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationPanel = document.getElementById("notificationPanel");
    const notificationCount = document.getElementById("notificationCount");
    const notificationList = document.getElementById("notificationList");
    const markAllRead = document.getElementById("markAllRead");

    // Global notifications array
    let notifications = [];

    // Get default icon based on notification type
    function getDefaultIcon(type) {
        const iconMap = {
            success: "check-circle",
            info: "info-circle",
            warning: "exclamation-triangle",
            danger: "exclamation-circle",
            error: "times-circle",
        };
        return iconMap[type] || "bell";
    }

    // Format icon name with Font Awesome classes
    function formatIcon(iconName) {
        // If already has Font Awesome classes, return as is
        if (
            iconName &&
            (iconName.startsWith("fas ") ||
                iconName.startsWith("far ") ||
                iconName.startsWith("fab "))
        ) {
            return iconName;
        }
        // Otherwise add the default fas prefix
        return iconName ? `fas fa-${iconName}` : "fas fa-bell";
    }

    // API Helper Functions
    async function apiRequest(url, options = {}) {
        try {
            // Get CSRF token
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

            const response = await fetch(url, {
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken,
                    ...options.headers,
                },
                ...options,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("API request failed:", error);
            showNotificationFeedback(
                "Failed to communicate with server",
                "error"
            );
            throw error;
        }
    }

    // Load notifications from database
    async function loadNotifications() {
        try {
            const response = await apiRequest("/api/notifications");
            notifications = response.notifications;
            updateNotificationCount();
            renderNotifications();
        } catch (error) {
            console.error("Failed to load notifications:", error);
        }
    }

    // Get unread count from database
    async function getUnreadCount() {
        try {
            const response = await apiRequest(
                "/api/notifications/unread-count"
            );
            const count = response.unread_count;
            notificationCount.textContent = count;
            notificationCount.style.display = count > 0 ? "block" : "none";
        } catch (error) {
            console.error("Failed to get unread count:", error);
        }
    }

    // Update notification count (using local data)
    function updateNotificationCount() {
        const unreadCount = notifications.filter((n) => n.unread).length;
        notificationCount.textContent = unreadCount;
        notificationCount.style.display = unreadCount > 0 ? "block" : "none";
    }

    // Render notifications
    function renderNotifications() {
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="empty-notifications">
                    <i class="fas fa-bell-slash"></i>
                    <p>No notifications</p>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications
            .map(
                (notification) => `
            <div class="notification-item ${
                notification.unread ? "unread" : "read"
            }" data-id="${notification.id}">
                <div class="notification-content">
                    <div class="notification-icon ${notification.type}">
                        <i class="${
                            formatIcon(notification.icon) ||
                            formatIcon(getDefaultIcon(notification.type))
                        }"></i>
                    </div>
                    <div class="notification-details">
                        <div class="notification-message">${
                            notification.message
                        }</div>
                        <div class="notification-time">${
                            notification.time
                        }</div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-notification-action toggle-read" 
                                data-id="${notification.id}" 
                                title="${
                                    notification.unread
                                        ? "Mark as read"
                                        : "Mark as unread"
                                }">
                            <i class="fas ${
                                notification.unread
                                    ? "fa-envelope-open"
                                    : "fa-envelope"
                            }"></i>
                        </button>
                        <button class="btn-notification-action delete-notification" 
                                data-id="${notification.id}" 
                                title="Delete notification">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `
            )
            .join("");

        // Add click handlers to notification actions
        document.querySelectorAll(".toggle-read").forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                const notificationId = parseInt(this.dataset.id);
                toggleReadStatus(notificationId);
            });
        });

        document.querySelectorAll(".delete-notification").forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                const notificationId = parseInt(this.dataset.id);
                deleteNotification(notificationId);
            });
        });

        // Add click handlers to notification items (for quick read toggle)
        document.querySelectorAll(".notification-item").forEach((item) => {
            item.addEventListener("click", function (e) {
                // Don't trigger if clicking on action buttons
                if (!e.target.closest(".notification-actions")) {
                    const notificationId = parseInt(this.dataset.id);
                    toggleReadStatus(notificationId);
                }
            });
        });
    }

    // Toggle read/unread status
    async function toggleReadStatus(notificationId) {
        try {
            const response = await apiRequest(
                `/api/notifications/${notificationId}/toggle-read`,
                {
                    method: "PATCH",
                }
            );

            if (response.success) {
                // Update local notification
                const notification = notifications.find(
                    (n) => n.id === notificationId
                );
                if (notification) {
                    notification.unread = !response.is_read;
                }

                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback(response.message);
            }
        } catch (error) {
            console.error("Failed to toggle read status:", error);
        }
    }

    // Mark all notifications as read
    async function markAllAsRead() {
        try {
            const response = await apiRequest(
                "/api/notifications/mark-all-read",
                {
                    method: "POST",
                }
            );

            if (response.success) {
                // Update local notifications
                notifications.forEach((notification) => {
                    notification.unread = false;
                });

                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback(response.message);
            }
        } catch (error) {
            console.error("Failed to mark all as read:", error);
        }
    }

    // Mark all notifications as unread
    async function markAllAsUnread() {
        try {
            const response = await apiRequest(
                "/api/notifications/mark-all-unread",
                {
                    method: "POST",
                }
            );

            if (response.success) {
                // Update local notifications
                notifications.forEach((notification) => {
                    notification.unread = true;
                });

                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback(response.message);
            }
        } catch (error) {
            console.error("Failed to mark all as unread:", error);
        }
    }

    // Delete notification
    async function deleteNotification(notificationId) {
        try {
            const response = await apiRequest(
                `/api/notifications/${notificationId}`,
                {
                    method: "DELETE",
                }
            );

            if (response.success) {
                // Remove from local array
                notifications = notifications.filter(
                    (n) => n.id !== notificationId
                );

                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback(response.message);
            }
        } catch (error) {
            console.error("Failed to delete notification:", error);
        }
    }

    // Clear all notifications
    async function clearAllNotifications() {
        if (
            confirm(
                "Are you sure you want to delete all notifications? This action cannot be undone."
            )
        ) {
            try {
                const response = await apiRequest("/api/notifications", {
                    method: "DELETE",
                });

                if (response.success) {
                    notifications = [];
                    updateNotificationCount();
                    renderNotifications();
                    showNotificationFeedback(response.message);
                }
            } catch (error) {
                console.error("Failed to clear all notifications:", error);
            }
        }
    }

    // Create a new notification (for testing)
    async function createNotification(type, title, message, options = {}) {
        try {
            const response = await apiRequest("/api/notifications", {
                method: "POST",
                body: JSON.stringify({
                    type: type,
                    title: title,
                    message: message,
                    ...options,
                }),
            });

            if (response.success) {
                // Add to local array
                notifications.unshift(response.notification);
                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback("New notification created");
            }
        } catch (error) {
            console.error("Failed to create notification:", error);
        }
    }

    // Show notification feedback
    function showNotificationFeedback(message, type = "success") {
        // Create a temporary toast notification
        const toast = document.createElement("div");
        toast.className = `notification-toast ${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 90px;
            right: 20px;
            background: ${
                type === "error" ? "var(--danger-color)" : "var(--dark-color)"
            };
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            font-size: 14px;
            box-shadow: var(--shadow-lg);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 300px;
            word-wrap: break-word;
        `;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = "translateX(0)";
        }, 100);

        // Animate out and remove
        setTimeout(() => {
            toast.style.transform = "translateX(100%)";
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    // Toggle notification panel
    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener("click", function (e) {
            e.stopPropagation();
            notificationPanel.classList.toggle("show");
        });

        // Close notification panel when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !notificationPanel.contains(e.target) &&
                !notificationBtn.contains(e.target)
            ) {
                notificationPanel.classList.remove("show");
            }
        });
    }

    // Mark all read functionality
    if (markAllRead) {
        markAllRead.addEventListener("click", function (e) {
            e.preventDefault();
            markAllAsRead();
        });
    }

    // Mark all unread functionality
    const markAllUnread = document.getElementById("markAllUnread");
    if (markAllUnread) {
        markAllUnread.addEventListener("click", function (e) {
            e.preventDefault();
            markAllAsUnread();
        });
    }

    // Clear all notifications functionality
    const clearAllNotificationsBtn = document.getElementById(
        "clearAllNotifications"
    );
    if (clearAllNotificationsBtn) {
        clearAllNotificationsBtn.addEventListener("click", function (e) {
            e.preventDefault();
            clearAllNotifications();
        });
    }

    // Initialize notifications on page load
    loadNotifications();

    // Refresh notifications every 30 seconds
    setInterval(() => {
        loadNotifications();
    }, 30000);

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll(".alert.alert-dismissible");
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const closeBtn = alert.querySelector(".btn-close");
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });

    // Smooth animations for stat cards
    const statCards = document.querySelectorAll(".stat-card");
    statCards.forEach(function (card, index) {
        setTimeout(function () {
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, index * 100);
    });

    // Initialize stat cards animation
    statCards.forEach(function (card) {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "all 0.5s ease";
    });

    // Global function for testing - create sample notifications
    window.createTestNotification = function () {
        const types = ["success", "warning", "info", "danger"];
        const messages = [
            "Test notification created",
            "System update available",
            "New user registered",
            "Backup completed",
            "Security alert",
        ];

        const randomType = types[Math.floor(Math.random() * types.length)];
        const randomMessage =
            messages[Math.floor(Math.random() * messages.length)];

        createNotification(randomType, "Test Notification", randomMessage);
    };

    // Profile Dropdown Functions
    initializeProfileDropdown();

    // Debug: Test if dropdown button exists
    const dropdownBtn = document.getElementById("profileDropdownBtn");
    if (dropdownBtn) {
        console.log("Profile dropdown button found!");

        // Add click event listener for debugging
        dropdownBtn.addEventListener("click", function (e) {
            console.log("Profile dropdown clicked!");

            // Manual toggle fallback if Bootstrap fails
            const dropdownMenu = dropdownBtn.nextElementSibling;
            if (
                dropdownMenu &&
                dropdownMenu.classList.contains("dropdown-menu")
            ) {
                console.log("Manual dropdown toggle");
                const isOpen = dropdownMenu.classList.contains("show");
                if (isOpen) {
                    dropdownMenu.classList.remove("show");
                    dropdownBtn.setAttribute("aria-expanded", "false");
                } else {
                    dropdownMenu.classList.add("show");
                    dropdownBtn.setAttribute("aria-expanded", "true");
                }
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (e) {
            const dropdownMenu = dropdownBtn.nextElementSibling;
            if (
                dropdownMenu &&
                !dropdownBtn.contains(e.target) &&
                !dropdownMenu.contains(e.target)
            ) {
                dropdownMenu.classList.remove("show");
                dropdownBtn.setAttribute("aria-expanded", "false");
            }
        });
    } else {
        console.error("Profile dropdown button not found!");
    }

    // Debug: Check if Bootstrap dropdown is working
    setTimeout(() => {
        const dropdown = document.querySelector('[data-bs-toggle="dropdown"]');
        if (dropdown) {
            console.log("Bootstrap dropdown element found:", dropdown);
            // Test Bootstrap dropdown instance
            try {
                const bsDropdown = new bootstrap.Dropdown(dropdown);
                console.log("Bootstrap dropdown instance created successfully");
            } catch (error) {
                console.error("Error creating Bootstrap dropdown:", error);
            }
        } else {
            console.error("No Bootstrap dropdown elements found");
        }
    }, 1000);
});

// Profile Dropdown Functionality
function initializeProfileDropdown() {
    console.log("Initializing profile dropdown...");

    // First, ensure Bootstrap dropdown is working
    const dropdownBtn = document.getElementById("profileDropdownBtn");
    if (dropdownBtn) {
        console.log("Profile dropdown button found, initializing...");

        // Initialize Bootstrap dropdown manually if needed
        try {
            const dropdown = new bootstrap.Dropdown(dropdownBtn);
            console.log("Bootstrap dropdown initialized successfully");
        } catch (error) {
            console.warn("Bootstrap dropdown initialization warning:", error);
        }
    }

    // View Profile
    const viewProfileBtn = document.getElementById("viewProfile");
    if (viewProfileBtn) {
        viewProfileBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("View Profile clicked");
            showProfileModal();
        });
        console.log("View Profile event listener added");
    } else {
        console.warn("View Profile button not found");
    }

    // Edit Profile
    const editProfileBtn = document.getElementById("editProfile");
    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Edit Profile clicked");
            showEditProfileModal();
        });
        console.log("Edit Profile event listener added");
    } else {
        console.warn("Edit Profile button not found");
    }

    // Change Password
    const changePasswordBtn = document.getElementById("changePassword");
    if (changePasswordBtn) {
        changePasswordBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Change Password clicked");
            showChangePasswordModal();
        });
        console.log("Change Password event listener added");
    } else {
        console.warn("Change Password button not found");
    }

    // Settings (Note: HTML has 'adminSettings' ID)
    const settingsBtn = document.getElementById("adminSettings");
    if (settingsBtn) {
        settingsBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Settings clicked");
            showSettingsModal();
        });
        console.log("Settings event listener added");
    } else {
        console.warn("Settings button not found");
    }

    // System Info
    const systemInfoBtn = document.getElementById("systemInfo");
    if (systemInfoBtn) {
        systemInfoBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("System Info clicked");
            showSystemInfoModal();
        });
        console.log("System Info event listener added");
    } else {
        console.warn("System Info button not found");
    }

    // Activity Log
    const activityLogBtn = document.getElementById("activityLog");
    if (activityLogBtn) {
        activityLogBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Activity Log clicked");
            showActivityLogModal();
        });
        console.log("Activity Log event listener added");
    } else {
        console.warn("Activity Log button not found");
    }

    // Help & Support
    const helpSupportBtn = document.getElementById("helpSupport");
    if (helpSupportBtn) {
        helpSupportBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Help & Support clicked");
            showHelpSupportModal();
        });
        console.log("Help & Support event listener added");
    } else {
        console.warn("Help & Support button not found");
    }

    // Logout (Note: HTML has 'logoutBtn' ID)
    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Logout clicked");
            showLogoutConfirmation();
        });
        console.log("Logout event listener added");
    } else {
        console.warn("Logout button not found");
    }

    console.log("Profile dropdown initialization completed");
}

// Profile Modal Functions
function showProfileModal() {
    const modalHtml = `
        <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-user-circle me-2"></i>User Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                                <h6>Admin User</h6>
                                <small class="text-muted">Administrator</small>
                            </div>
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Full Name:</strong></div>
                                    <div class="col-sm-8">Admin User</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">admin@example.com</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Role:</strong></div>
                                    <div class="col-sm-8"><span class="badge bg-primary">Administrator</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Status:</strong></div>
                                    <div class="col-sm-8"><span class="badge bg-success">Active</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Last Login:</strong></div>
                                    <div class="col-sm-8">${new Date().toLocaleString()}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Member Since:</strong></div>
                                    <div class="col-sm-8">January 1, 2024</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="showEditProfileModal()">Edit Profile</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "profileModal");
}

function showEditProfileModal() {
    // Close existing modal first
    const existingModal = document.getElementById("profileModal");
    if (existingModal) {
        const modal = bootstrap.Modal.getInstance(existingModal);
        if (modal) modal.hide();
    }

    const modalHtml = `
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProfileForm">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                                    <button type="button" class="btn btn-outline-primary btn-sm">Change Photo</button>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="Admin User">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="admin@example.com">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" placeholder="Enter phone number">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bio</label>
                                        <textarea class="form-control" rows="3" placeholder="Tell us about yourself"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveProfile()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "editProfileModal");
}

function showChangePasswordModal() {
    const modalHtml = `
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" required>
                            </div>
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password must be at least 8 characters with uppercase, lowercase, and numbers.
                                </small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "changePasswordModal");
}

function showSettingsModal() {
    const modalHtml = `
        <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-cog me-2"></i>Settings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="nav flex-column nav-pills" id="settings-tab" role="tablist">
                                    <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#general">General</button>
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#notifications">Notifications</button>
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#privacy">Privacy</button>
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#appearance">Appearance</button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="general">
                                        <h6>General Settings</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="autoSave">
                                            <label class="form-check-label" for="autoSave">Auto-save changes</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="confirmActions">
                                            <label class="form-check-label" for="confirmActions">Confirm destructive actions</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="notifications">
                                        <h6>Notification Preferences</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="emailNotifs" checked>
                                            <label class="form-check-label" for="emailNotifs">Email notifications</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="browserNotifs" checked>
                                            <label class="form-check-label" for="browserNotifs">Browser notifications</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="privacy">
                                        <h6>Privacy Settings</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="profilePublic">
                                            <label class="form-check-label" for="profilePublic">Public profile</label>
                                        </div>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="activityTracking">
                                            <label class="form-check-label" for="activityTracking">Activity tracking</label>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="appearance">
                                        <h6>Appearance</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Theme</label>
                                            <select class="form-select">
                                                <option>Light</option>
                                                <option>Dark</option>
                                                <option>Auto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveSettings()">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "settingsModal");
}

function showSystemInfoModal() {
    const modalHtml = `
        <div class="modal fade" id="systemInfoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>System Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Application Info</h6>
                                <table class="table table-sm">
                                    <tr><td><strong>Version:</strong></td><td>1.0.0</td></tr>
                                    <tr><td><strong>Environment:</strong></td><td>Production</td></tr>
                                    <tr><td><strong>Debug Mode:</strong></td><td>Disabled</td></tr>
                                    <tr><td><strong>Timezone:</strong></td><td>UTC</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Server Info</h6>
                                <table class="table table-sm">
                                    <tr><td><strong>PHP Version:</strong></td><td>8.2.0</td></tr>
                                    <tr><td><strong>Laravel Version:</strong></td><td>12.x</td></tr>
                                    <tr><td><strong>Database:</strong></td><td>MySQL 8.0</td></tr>
                                    <tr><td><strong>Cache Driver:</strong></td><td>File</td></tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <h6>System Health</h6>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="badge bg-success fs-6 p-2 w-100">Database</div>
                                <small class="text-muted">Connected</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-success fs-6 p-2 w-100">Cache</div>
                                <small class="text-muted">Working</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-success fs-6 p-2 w-100">Queue</div>
                                <small class="text-muted">Running</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-success fs-6 p-2 w-100">Storage</div>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "systemInfoModal");
}

function showActivityLogModal() {
    const modalHtml = `
        <div class="modal fade" id="activityLogModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-history me-2"></i>Activity Log</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${new Date().toLocaleString()}</td>
                                        <td><i class="fas fa-sign-in-alt text-success me-1"></i>Login</td>
                                        <td>User logged in successfully</td>
                                        <td>192.168.1.100</td>
                                        <td><span class="badge bg-success">Success</span></td>
                                    </tr>
                                    <tr>
                                        <td>${new Date(
                                            Date.now() - 3600000
                                        ).toLocaleString()}</td>
                                        <td><i class="fas fa-edit text-info me-1"></i>Update</td>
                                        <td>Profile information updated</td>
                                        <td>192.168.1.100</td>
                                        <td><span class="badge bg-info">Info</span></td>
                                    </tr>
                                    <tr>
                                        <td>${new Date(
                                            Date.now() - 7200000
                                        ).toLocaleString()}</td>
                                        <td><i class="fas fa-cog text-warning me-1"></i>Settings</td>
                                        <td>Notification preferences changed</td>
                                        <td>192.168.1.100</td>
                                        <td><span class="badge bg-warning">Warning</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Export Log</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "activityLogModal");
}

function showHelpSupportModal() {
    const modalHtml = `
        <div class="modal fade" id="helpSupportModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i>Help & Support</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-book fa-3x text-primary mb-3"></i>
                                        <h6>Documentation</h6>
                                        <p class="text-muted">Access comprehensive guides and tutorials</p>
                                        <button class="btn btn-outline-primary btn-sm">View Docs</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-headset fa-3x text-success mb-3"></i>
                                        <h6>Contact Support</h6>
                                        <p class="text-muted">Get help from our support team</p>
                                        <button class="btn btn-outline-success btn-sm">Contact Us</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-video fa-3x text-info mb-3"></i>
                                        <h6>Video Tutorials</h6>
                                        <p class="text-muted">Watch step-by-step video guides</p>
                                        <button class="btn btn-outline-info btn-sm">Watch Videos</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-comments fa-3x text-warning mb-3"></i>
                                        <h6>Community Forum</h6>
                                        <p class="text-muted">Join discussions with other users</p>
                                        <button class="btn btn-outline-warning btn-sm">Visit Forum</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <h6>Quick Contact</h6>
                            <p><i class="fas fa-envelope me-2"></i>support@example.com</p>
                            <p><i class="fas fa-phone me-2"></i>+94 76 123 4567</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "helpSupportModal");
}

function showLogoutConfirmation() {
    const modalHtml = `
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-sign-out-alt me-2"></i>Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <!--<i class="fas fa-question-circle fa-3x text-warning mb-3"></i>-->
                        <h6>Are you sure you want to logout?</h6>
                        <p class="text-muted">You will be redirected to the login page.</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="performLogout()">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    showModal(modalHtml, "logoutModal");
}

// Helper Functions
function showModal(modalHtml, modalId) {
    // Remove existing modal if present
    const existingModal = document.getElementById(modalId);
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML("beforeend", modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();

    // Remove modal from DOM when hidden
    document
        .getElementById(modalId)
        .addEventListener("hidden.bs.modal", function () {
            this.remove();
        });
}

function saveProfile() {
    // Simulate API call
    showToast(
        "success",
        "Profile Updated",
        "Your profile has been updated successfully."
    );
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("editProfileModal")
    );
    modal.hide();
}

function changePassword() {
    // Simulate password change
    showToast(
        "success",
        "Password Changed",
        "Your password has been updated successfully."
    );
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("changePasswordModal")
    );
    modal.hide();
}

function saveSettings() {
    // Simulate settings save
    showToast(
        "success",
        "Settings Saved",
        "Your settings have been saved successfully."
    );
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("settingsModal")
    );
    modal.hide();
}

function performLogout() {
    // Show loading state
    showToast("info", "Logging out...", "Please wait while we log you out.");

    // Simulate logout process
    setTimeout(() => {
        // In a real application, you would make an API call to logout
        // For now, we'll just redirect to a login page or reload
        window.location.href = "/login"; // or window.location.reload();
    }, 1500);
}
