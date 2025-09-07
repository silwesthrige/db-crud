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
                        <i class="${notification.icon}"></i>
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
});
