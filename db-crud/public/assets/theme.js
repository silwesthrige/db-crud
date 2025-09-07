// Theme Management System
class ThemeManager {
    constructor() {
        this.currentTheme = localStorage.getItem("theme") || "light";
        this.themeToggle = document.getElementById("themeToggle");
        this.themeIcon = document.getElementById("themeIcon");

        this.init();
    }

    init() {
        // Set initial theme
        this.setTheme(this.currentTheme);

        // Add event listener to toggle button
        if (this.themeToggle) {
            this.themeToggle.addEventListener("click", () =>
                this.toggleTheme()
            );
        }

        // Also add event listener to any element with theme-toggle-btn class
        document.addEventListener("click", (e) => {
            if (e.target.closest(".theme-toggle-btn")) {
                this.toggleTheme();
            }
        });

        // Listen for system theme changes
        this.watchSystemTheme();

        // Add keyboard shortcut (Ctrl+Shift+T)
        document.addEventListener("keydown", (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === "T") {
                e.preventDefault();
                this.toggleTheme();
            }
        });
    }

    setTheme(theme) {
        this.currentTheme = theme;

        // Apply theme attributes to multiple elements
        document.documentElement.setAttribute("data-theme", theme);
        document.body.setAttribute("data-theme", theme);
        document.body.setAttribute("data-bs-theme", theme); // For Bootstrap 5.3+ compatibility
        document.body.className =
            document.body.className.replace(/theme-\w+/g, "") +
            ` theme-${theme}`;

        localStorage.setItem("theme", theme);

        // Force apply theme classes
        this.forceApplyTheme(theme);

        // Update icon
        this.updateIcon();

        // Update all theme toggle buttons
        this.updateAllToggleButtons();

        // Emit theme change event
        this.emitThemeChange();

        // Force repaint for better performance
        requestAnimationFrame(() => {
            document.body.style.display = "none";
            document.body.offsetHeight; // Trigger reflow
            document.body.style.display = "";
        });
    }

    forceApplyTheme(theme) {
        // Force remove any conflicting classes
        document.body.classList.remove("light-theme", "dark-theme");
        document.documentElement.classList.remove("light-theme", "dark-theme");

        // Add theme class
        document.body.classList.add(`${theme}-theme`);
        document.documentElement.classList.add(`${theme}-theme`);

        // Force CSS variable updates
        if (theme === "dark") {
            document.documentElement.style.setProperty("--body-bg", "#0f172a");
            document.documentElement.style.setProperty("--card-bg", "#1e293b");
            document.documentElement.style.setProperty(
                "--text-color",
                "#e2e8f0"
            );
            document.documentElement.style.setProperty(
                "--border-color",
                "#475569"
            );
        } else {
            document.documentElement.style.removeProperty("--body-bg");
            document.documentElement.style.removeProperty("--card-bg");
            document.documentElement.style.removeProperty("--text-color");
            document.documentElement.style.removeProperty("--border-color");
        }

        // Force background change
        if (theme === "dark") {
            document.body.style.background =
                "linear-gradient(135deg, #0f172a 0%, #1e293b 100%)";
            document.body.style.color = "#e2e8f0";
        } else {
            document.body.style.background =
                "linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%)";
            document.body.style.color = "#374151";
        }
    }

    toggleTheme() {
        const newTheme = this.currentTheme === "light" ? "dark" : "light";
        this.setTheme(newTheme);

        // Add animation effect
        this.animateToggle();

        // Provide user feedback
        this.showThemeChangeToast(newTheme);
    }

    updateIcon() {
        if (!this.themeIcon) return;

        if (this.currentTheme === "dark") {
            this.themeIcon.className = "fas fa-sun";
            this.themeIcon.style.color = "#fbbf24";
        } else {
            this.themeIcon.className = "fas fa-moon";
            this.themeIcon.style.color = "#64748b";
        }
    }

    updateAllToggleButtons() {
        // Update all theme toggle buttons
        const toggleButtons = document.querySelectorAll(".theme-toggle-btn");
        toggleButtons.forEach((button) => {
            const icon = button.querySelector("i");
            const text = button.querySelector("span");

            if (icon) {
                if (this.currentTheme === "dark") {
                    icon.className = "fas fa-sun";
                } else {
                    icon.className = "fas fa-moon";
                }
            }

            if (text) {
                text.textContent =
                    this.currentTheme === "dark" ? "Light Mode" : "Dark Mode";
            }
        });
    }

    animateToggle() {
        if (this.themeToggle) {
            this.themeToggle.style.transform = "rotate(360deg)";
            setTimeout(() => {
                this.themeToggle.style.transform = "rotate(0deg)";
            }, 300);
        }

        // Animate all toggle buttons
        const toggleButtons = document.querySelectorAll(".theme-toggle-btn");
        toggleButtons.forEach((button) => {
            button.style.transform = "scale(0.95)";
            setTimeout(() => {
                button.style.transform = "scale(1)";
            }, 150);
        });
    }

    showThemeChangeToast(theme) {
        // Create a simple toast notification
        const toast = document.createElement("div");
        toast.className = `theme-toast theme-toast-${theme}`;
        toast.innerHTML = `
            <i class="fas fa-${theme === "dark" ? "moon" : "sun"}"></i>
            <span>Switched to ${theme === "dark" ? "Dark" : "Light"} Mode</span>
        `;

        // Add toast styles
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${theme === "dark" ? "#1e293b" : "#ffffff"};
            color: ${theme === "dark" ? "#e2e8f0" : "#374151"};
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid ${theme === "dark" ? "#334155" : "#e5e7eb"};
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;

        document.body.appendChild(toast);

        // Trigger animation
        setTimeout(() => {
            toast.style.transform = "translateX(0)";
        }, 10);

        // Remove toast after 2 seconds
        setTimeout(() => {
            toast.style.transform = "translateX(100%)";
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 2000);
    }

    watchSystemTheme() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia(
                "(prefers-color-scheme: dark)"
            );
            mediaQuery.addEventListener("change", (e) => {
                // Only auto-change if user hasn't manually set a preference
                if (!localStorage.getItem("theme")) {
                    this.setTheme(e.matches ? "dark" : "light");
                }
            });
        }
    }

    emitThemeChange() {
        const event = new CustomEvent("themeChanged", {
            detail: { theme: this.currentTheme },
        });
        document.dispatchEvent(event);
    }

    getTheme() {
        return this.currentTheme;
    }

    isDark() {
        return this.currentTheme === "dark";
    }

    // Method to manually toggle theme from external scripts
    toggle() {
        this.toggleTheme();
    }
}

// Chart Theme Configuration
const getChartThemeConfig = (isDark) => {
    return {
        backgroundColor: isDark ? "#1e293b" : "#ffffff",
        color: isDark ? "#e2e8f0" : "#334155",
        grid: {
            color: isDark ? "#475569" : "#e2e8f0",
        },
        legend: {
            labels: {
                color: isDark ? "#e2e8f0" : "#334155",
            },
        },
        scales: {
            x: {
                ticks: {
                    color: isDark ? "#94a3b8" : "#64748b",
                },
                grid: {
                    color: isDark ? "#475569" : "#e2e8f0",
                },
            },
            y: {
                ticks: {
                    color: isDark ? "#94a3b8" : "#64748b",
                },
                grid: {
                    color: isDark ? "#475569" : "#e2e8f0",
                },
            },
        },
    };
};

// Chart Update Function
const updateChartsTheme = (isDark) => {
    // Update Chart.js instances if they exist
    if (window.chartInstances) {
        Object.values(window.chartInstances).forEach((chart) => {
            const themeConfig = getChartThemeConfig(isDark);

            // Update chart options
            Object.assign(chart.options, themeConfig);
            chart.update("none");
        });
    }

    // Update FullCalendar theme if it exists
    if (window.calendar) {
        window.calendar.render();
    }
};

// Initialize theme manager when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.themeManager = new ThemeManager();

    // Listen for theme changes to update charts
    document.addEventListener("themeChanged", (e) => {
        updateChartsTheme(e.detail.theme === "dark");
    });
});

// Export for use in other scripts
if (typeof module !== "undefined" && module.exports) {
    module.exports = { ThemeManager, getChartThemeConfig, updateChartsTheme };
}
