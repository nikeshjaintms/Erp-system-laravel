/**
 * Custom Bootstrap 5 Admin Theme JavaScript for ERP System
 * Handles Sidebar toggle, Theme switcher (dark/light), tooltips, search shortcut, and UX animations
 */

(function () {
    'use strict';

    // Theme Management (Light / Dark)
    const THEME_STORAGE_KEY = 'erp-theme-mode';
    const SIDEBAR_STORAGE_KEY = 'erp-sidebar-state';

    function initTheme() {
        const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const activeTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

        applyTheme(activeTheme);

        const themeToggleBtn = document.getElementById('themeToggleBtn');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function () {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyTheme(newTheme);
                localStorage.setItem(THEME_STORAGE_KEY, newTheme);
            });
        }
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        const themeIcon = document.getElementById('themeIcon');
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-moon-stars-fill text-warning';
            } else {
                themeIcon.className = 'bi bi-sun-fill text-warning';
            }
        }
    }

    // Sidebar Management
    function initSidebar() {
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const savedSidebarState = localStorage.getItem(SIDEBAR_STORAGE_KEY);

        // Apply saved desktop state
        if (window.innerWidth >= 992 && savedSidebarState === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (window.innerWidth < 992) {
                    // Mobile Drawer
                    document.body.classList.toggle('sidebar-mobile-show');
                } else {
                    // Desktop Collapse
                    document.body.classList.toggle('sidebar-collapsed');
                    const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                    localStorage.setItem(SIDEBAR_STORAGE_KEY, isCollapsed ? 'collapsed' : 'expanded');
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                document.body.classList.remove('sidebar-mobile-show');
            });
        }

        // Close mobile sidebar on window resize up
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                document.body.classList.remove('sidebar-mobile-show');
            }
        });
    }

    // Initialize Bootstrap 5 Tooltips & Popovers
    function initBootstrapComponents() {
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    }

    // Search Input Keyboard Shortcut (Ctrl+K or Cmd+K)
    function initSearchShortcut() {
        const searchInput = document.getElementById('globalSearchInput');
        if (!searchInput) return;

        window.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
    }

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function () {
        initTheme();
        initSidebar();
        initBootstrapComponents();
        initSearchShortcut();
    });
})();
