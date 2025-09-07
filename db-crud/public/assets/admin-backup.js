// Admin Dashboard JavaScript
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

    // Notification System
    const notificationBtn = document.getElementById("notificationBtn");
    const notificationPanel = document.getElementById("notificationPanel");
    const notificationCount = document.getElementById("notificationCount");
    const notificationList = document.getElementById("notificationList");
    const markAllRead = document.getElementById("markAllRead");

    // Default notifications data
    const defaultNotifications = [
        {
            id: 1,
            message: "New event 'EAD Course work' was created",
            time: "2 minutes ago",
            type: "success",
            icon: "fas fa-plus-circle",
            unread: true,
            timestamp: Date.now() - 120000, // 2 minutes ago
        },
        {
            id: 2,
            message: "Event 'coursework' deadline approaching",
            time: "1 hour ago",
            type: "warning",
            icon: "fas fa-clock",
            unread: true,
            timestamp: Date.now() - 3600000, // 1 hour ago
        },
        {
            id: 3,
            message: "High priority event needs attention",
            time: "3 hours ago",
            type: "warning",
            icon: "fas fa-exclamation-triangle",
            unread: true,
            timestamp: Date.now() - 10800000, // 3 hours ago
        },
        {
            id: 4,
            message: "System backup completed successfully",
            time: "1 day ago",
            type: "info",
            icon: "fas fa-check-circle",
            unread: false,
            timestamp: Date.now() - 86400000, // 1 day ago
        },
        {
            id: 5,
            message: "Welcome to the admin dashboard!",
            time: "2 days ago",
            type: "info",
            icon: "fas fa-info-circle",
            unread: false,
            timestamp: Date.now() - 172800000, // 2 days ago
        },
    ];

    // Load notifications from localStorage or use defaults
    let notifications = loadNotifications();

    // Persistent storage functions
    function saveNotifications() {
        localStorage.setItem(
            "adminNotifications",
            JSON.stringify(notifications)
        );
    }

    function loadNotifications() {
        const saved = localStorage.getItem("adminNotifications");
        if (saved) {
            try {
                return JSON.parse(saved);
            } catch (e) {
                console.warn(
                    "Failed to load notifications from localStorage:",
                    e
                );
            }
        }
        return [...defaultNotifications];
    }

    function formatTime(timestamp) {
        const now = Date.now();
        const diff = now - timestamp;

        if (diff < 60000) return "Just now";
        if (diff < 3600000) return Math.floor(diff / 60000) + " minutes ago";
        if (diff < 86400000) return Math.floor(diff / 3600000) + " hours ago";
        if (diff < 604800000) return Math.floor(diff / 86400000) + " days ago";

        const date = new Date(timestamp);
        return date.toLocaleDateString();
    }

    // Update notification count
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

        // Sort notifications by timestamp (newest first)
        const sortedNotifications = [...notifications].sort(
            (a, b) => b.timestamp - a.timestamp
        );

        notificationList.innerHTML = sortedNotifications
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
                        <div class="notification-time">${formatTime(
                            notification.timestamp
                        )}</div>
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
    function toggleReadStatus(notificationId) {
        const notification = notifications.find((n) => n.id === notificationId);
        if (notification) {
            notification.unread = !notification.unread;
            saveNotifications();
            updateNotificationCount();
            renderNotifications();

            // Show feedback
            showNotificationFeedback(
                notification.unread ? "Marked as unread" : "Marked as read"
            );
        }
    }

    // Mark notification as read (legacy support)
    function markAsRead(notificationId) {
        const notification = notifications.find((n) => n.id === notificationId);
        if (notification && notification.unread) {
            notification.unread = false;
            saveNotifications();
            updateNotificationCount();
            renderNotifications();
        }
    }

    // Mark all notifications as read
    function markAllAsRead() {
        const unreadCount = notifications.filter((n) => n.unread).length;
        if (unreadCount === 0) {
            showNotificationFeedback("No unread notifications");
            return;
        }

        notifications.forEach((notification) => {
            notification.unread = false;
        });
        saveNotifications();
        updateNotificationCount();
        renderNotifications();
        showNotificationFeedback(`Marked ${unreadCount} notifications as read`);
    }

    // Mark all notifications as unread
    function markAllAsUnread() {
        const readCount = notifications.filter((n) => !n.unread).length;
        if (readCount === 0) {
            showNotificationFeedback("No read notifications");
            return;
        }

        notifications.forEach((notification) => {
            notification.unread = true;
        });
        saveNotifications();
        updateNotificationCount();
        renderNotifications();
        showNotificationFeedback(`Marked ${readCount} notifications as unread`);
    }

    // Delete notification
    function deleteNotification(notificationId) {
        const index = notifications.findIndex((n) => n.id === notificationId);
        if (index !== -1) {
            notifications.splice(index, 1);
            saveNotifications();
            updateNotificationCount();
            renderNotifications();
            showNotificationFeedback("Notification deleted");
        }
    }

    // Add new notification
    function addNotification(
        message,
        type = "info",
        icon = "fas fa-info-circle"
    ) {
        const newNotification = {
            id: Date.now(),
            message: message,
            time: "Just now",
            type: type,
            icon: icon,
            unread: true,
            timestamp: Date.now(),
        };
        notifications.unshift(newNotification);
        saveNotifications();
        updateNotificationCount();
        renderNotifications();
    }

    // Show notification feedback
    function showNotificationFeedback(message) {
        // Create a temporary toast notification
        const toast = document.createElement("div");
        toast.className = "notification-toast";
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 90px;
            right: 20px;
            background: var(--dark-color);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 9999;
            font-size: 14px;
            box-shadow: var(--shadow-lg);
            transform: translateX(100%);
            transition: transform 0.3s ease;
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
        }, 2000);
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
    const clearAllNotifications = document.getElementById(
        "clearAllNotifications"
    );
    if (clearAllNotifications) {
        clearAllNotifications.addEventListener("click", function (e) {
            e.preventDefault();
            if (
                confirm(
                    "Are you sure you want to delete all notifications? This action cannot be undone."
                )
            ) {
                notifications = [];
                saveNotifications();
                updateNotificationCount();
                renderNotifications();
                showNotificationFeedback("All notifications cleared");
            }
        });
    }

    // Initialize notifications
    updateNotificationCount();
    renderNotifications();

    // Simulate new notifications (for demo)
    setInterval(() => {
        if (Math.random() > 0.95) {
            // 5% chance every 30 seconds
            const messages = [
                "New system update available",
                "Database backup completed",
                "New user registered",
                "Security scan completed",
                "System maintenance scheduled",
                "New event created by user",
                "Performance report generated",
            ];

            const types = ["info", "success", "warning"];
            const icons = [
                "fas fa-download",
                "fas fa-check-circle",
                "fas fa-user-plus",
                "fas fa-shield-alt",
            ];

            const randomMessage =
                messages[Math.floor(Math.random() * messages.length)];
            const randomType = types[Math.floor(Math.random() * types.length)];
            const randomIcon = icons[Math.floor(Math.random() * icons.length)];

            addNotification(randomMessage, randomType, randomIcon);
        }
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
});
