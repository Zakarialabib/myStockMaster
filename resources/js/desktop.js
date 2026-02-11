/**
 * Desktop-specific JavaScript functionality for MyStockMaster
 */

// Desktop-specific JavaScript functionality for MyStockMaster
class DesktopApp {
    constructor() {
        this.isDesktop = this.detectDesktopEnvironment();
        this.shortcuts = {};
        this.notifications = [];
        this.init();
    }

    init() {
        if (!this.isDesktop) return;

        this.setupKeyboardShortcuts();
        this.setupDesktopFeatures();
        this.registerGlobalShortcuts();
        this.setupNotificationSystem();
        this.setupWindowManagement();
        this.setupFormEnhancements();
        this.setupLoadingStates();
        this.setupErrorHandling();
        
        console.log('Desktop app initialized');
    }

    /**
     * Setup comprehensive error handling
     */
    setupErrorHandling() {
        // Global error handler for uncaught JavaScript errors
        window.addEventListener('error', (event) => {
            this.handleJavaScriptError({
                message: event.message,
                source: event.filename,
                line: event.lineno,
                column: event.colno,
                stack: event.error ? event.error.stack : null,
                userAgent: navigator.userAgent,
                url: window.location.href
            });
        });

        // Promise rejection handler
        window.addEventListener('unhandledrejection', (event) => {
            this.handleJavaScriptError({
                message: `Unhandled Promise Rejection: ${event.reason}`,
                source: 'Promise',
                stack: event.reason && event.reason.stack ? event.reason.stack : null,
                userAgent: navigator.userAgent,
                url: window.location.href
            });
        });

        // Livewire error handler
        if (window.Livewire) {
            window.Livewire.on('error', (error) => {
                this.handleJavaScriptError({
                    message: `Livewire Error: ${error.message || error}`,
                    source: 'Livewire',
                    stack: error.stack || null,
                    userAgent: navigator.userAgent,
                    url: window.location.href
                });
            });
        }
    }

    /**
     * Handle JavaScript errors by sending them to the server
     */
    async handleJavaScriptError(errorData) {
        try {
            await fetch('/desktop/errors/js', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(errorData)
            });
        } catch (e) {
            console.error('Failed to log JavaScript error to server:', e);
        }
    }

    detectDesktopEnvironment() {
        return window.nativephp || 
               window.electronAPI || 
               navigator.userAgent.includes('Electron') ||
               window.location.protocol === 'file:' ||
               document.body.classList.contains('desktop-app');
    }

    async registerGlobalShortcuts() {
        try {
            const response = await fetch('/desktop/shortcuts/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            const result = await response.json();
            if (result.success) {
                console.log('Desktop shortcuts registered successfully');
            }
        } catch (error) {
            console.error('Failed to register desktop shortcuts:', error);
        }
    }

    setupKeyboardShortcuts() {
        const shortcuts = {
            // Navigation shortcuts
            'ctrl+shift+d': () => this.toggleDevTools(),
            'ctrl+r': () => this.refreshPage(),
            'f5': () => this.refreshPage(),
            'ctrl+shift+r': () => this.hardRefresh(),
            'f11': () => this.toggleFullscreen(),
            'alt+f4': () => this.closeWindow(),
            'ctrl+m': () => this.minimizeWindow(),
            'ctrl+shift+m': () => this.maximizeWindow(),
            
            // Application shortcuts
            'ctrl+shift+s': () => this.syncData(),
            'ctrl+shift+o': () => this.toggleOfflineMode(),
            'ctrl+shift+n': () => this.showNotifications(),
            'ctrl+shift+c': () => this.clearCache(),
            'ctrl+shift+l': () => this.showLogs(),
            
            // POS shortcuts
            'ctrl+shift+p': () => this.openPOS(),
            'ctrl+shift+i': () => this.addProduct(),
            'ctrl+n': () => this.newSale(),
            'ctrl+shift+q': () => this.newQuotation(),
            'ctrl+shift+b': () => this.showBarcode(),
            
            // Quick actions
            'ctrl+shift+u': () => this.showUsers(),
            'ctrl+shift+w': () => this.showWarehouses(),
            'ctrl+shift+e': () => this.exportData(),
            
            // System shortcuts
            'ctrl+shift+?': () => this.showHelp(),
            'ctrl+shift+a': () => this.showAbout(),
            'escape': () => this.closeModal(),
        };

        document.addEventListener('keydown', (e) => {
            const key = this.getKeyCombo(e);
            if (shortcuts[key]) {
                e.preventDefault();
                this.executeShortcut(key, shortcuts[key]);
            }
        });

        this.shortcuts = shortcuts;
    }

    getKeyCombo(event) {
        const keys = [];
        
        if (event.ctrlKey) keys.push('ctrl');
        if (event.altKey) keys.push('alt');
        if (event.shiftKey) keys.push('shift');
        if (event.metaKey) keys.push('meta');
        
        const key = event.key.toLowerCase();
        if (key === 'escape') keys.push('escape');
        else if (key === 'f5') keys.push('f5');
        else if (key === 'f11') keys.push('f11');
        else if (key === 'f4') keys.push('f4');
        else if (key.length === 1) keys.push(key);
        
        return keys.join('+');
    }

    async executeShortcut(shortcut, action) {
        try {
            // Execute local action first
            if (typeof action === 'function') {
                action();
            }

            // Send to server for logging and additional processing
            const response = await fetch('/desktop/shortcut/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ shortcut })
            });

            const result = await response.json();
            if (result.success && result.action) {
                this.handleServerAction(result);
            }
        } catch (error) {
            console.error('Shortcut execution failed:', error);
            this.showNotification('Error', 'Shortcut execution failed', 'error');
        }
    }

    handleServerAction(result) {
        switch (result.action) {
            case 'navigate':
                if (result.url) {
                    window.location.href = result.url;
                }
                break;
            case 'showModal':
                if (result.modal) {
                    this.showModal(result.modal);
                }
                break;
            case 'toggleDevTools':
                this.toggleDevTools();
                break;
            case 'refresh':
                window.location.reload();
                break;
            case 'hardRefresh':
                window.location.reload(true);
                break;
            case 'closeModal':
                this.closeModal();
                break;
            default:
                if (result.message) {
                    this.showNotification('Action Complete', result.message, 'success');
                }
        }
    }

    // Notification system
    setupNotificationSystem() {
        // Listen for global notification events
        window.addEventListener('desktop-notification', (e) => {
            this.showNotification(e.detail.title, e.detail.message, e.detail.type);
        });

        // Integration with Livewire notifications
        if (window.Livewire) {
            window.Livewire.on('desktop-notify', (data) => {
                this.showNotification(data.title, data.message, data.type);
            });
        }
    }

    showNotification(title, message, type = 'info') {
        // Try to use native desktop notifications first
        if (window.nativephp && window.nativephp.showNotification) {
            window.nativephp.showNotification({
                title: title,
                body: message,
                icon: this.getNotificationIcon(type)
            });
            return;
        }

        // Fallback to browser notifications
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: this.getNotificationIcon(type)
            });
            return;
        }

        // Fallback to in-app notification
        this.showInAppNotification(title, message, type);
    }

    showInAppNotification(title, message, type) {
        // Trigger Livewire notification component if available
        if (window.Livewire) {
            window.Livewire.dispatch('addNotification', {
                title: title,
                message: message,
                type: type
            });
        }
    }

    getNotificationIcon(type) {
        const icons = {
            'success': '/images/icons/success.png',
            'error': '/images/icons/error.png',
            'warning': '/images/icons/warning.png',
            'info': '/images/icons/info.png'
        };
        return icons[type] || icons.info;
    }

    // Window management functions
    toggleDevTools() {
        if (window.nativephp) {
            window.nativephp.toggleDevTools();
        } else if (window.electronAPI) {
            window.electronAPI.toggleDevTools();
        } else {
            // Fallback for web environment
            console.log('Developer tools toggle requested');
        }
    }

    refreshPage() {
        window.location.reload();
    }

    hardRefresh() {
        // Clear cache and reload
        if ('caches' in window) {
            caches.keys().then(names => {
                names.forEach(name => caches.delete(name));
            });
        }
        window.location.reload(true);
    }

    toggleFullscreen() {
        if (document.fullscreenElement) {
            document.exitFullscreen();
        } else {
            document.documentElement.requestFullscreen();
        }
    }

    closeWindow() {
        if (window.nativephp) {
            window.nativephp.close();
        } else {
            window.close();
        }
    }

    minimizeWindow() {
        if (window.nativephp) {
            window.nativephp.minimize();
        }
    }

    maximizeWindow() {
        if (window.nativephp) {
            window.nativephp.maximize();
        }
    }

    // Application functions
    async syncData() {
        this.showLoadingState('Syncing data...');
        
        try {
            if (window.Livewire) {
                // Trigger Livewire sync if available
                window.Livewire.emit('syncData');
            }
            
            this.showNotification('Sync Complete', 'Data synchronized successfully', 'success');
        } catch (error) {
            this.showNotification('Sync Failed', 'Failed to sync data', 'error');
        } finally {
            this.hideLoadingState();
        }
    }

    toggleOfflineMode() {
        const isOffline = document.body.classList.toggle('offline-mode');
        const message = isOffline ? 'Switched to offline mode' : 'Switched to online mode';
        this.showNotification('Mode Changed', message, 'info');
        
        // Update UI elements
        this.updateOfflineIndicators(isOffline);
    }

    updateOfflineIndicators(isOffline) {
        const indicators = document.querySelectorAll('[data-offline-indicator]');
        indicators.forEach(indicator => {
            indicator.textContent = isOffline ? 'Offline' : 'Online';
            indicator.className = isOffline ? 'text-red-500' : 'text-green-500';
        });
    }

    showNotifications() {
        const panel = document.querySelector('[data-notifications-panel]');
        if (panel) {
            panel.classList.toggle('hidden');
        }
    }

    async clearCache() {
        this.showLoadingState('Clearing cache...');
        
        try {
            // Clear browser cache
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                await Promise.all(cacheNames.map(name => caches.delete(name)));
            }
            
            // Clear localStorage
            localStorage.clear();
            sessionStorage.clear();
            
            this.showNotification('Cache Cleared', 'All caches cleared successfully', 'success');
        } catch (error) {
            this.showNotification('Clear Failed', 'Failed to clear cache', 'error');
        } finally {
            this.hideLoadingState();
        }
    }

    showLogs() {
        window.location.href = '/admin/logs';
    }

    // Navigation functions
    openPOS() {
        window.location.href = '/pos';
    }

    addProduct() {
        window.location.href = '/admin/products/create';
    }

    newSale() {
        window.location.href = '/admin/sales/create';
    }

    newQuotation() {
        window.location.href = '/admin/quotations/create';
    }

    showBarcode() {
        window.location.href = '/admin/barcodes';
    }

    showUsers() {
        window.location.href = '/admin/users';
    }

    showWarehouses() {
        window.location.href = '/admin/warehouses';
    }

    exportData() {
        this.showModal('export-data');
    }

    showHelp() {
        this.showModal('help');
    }

    showAbout() {
        this.showModal('about');
    }

    // Modal management
    showModal(modalId) {
        const modal = document.querySelector(`[data-modal="${modalId}"]`);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            // Create dynamic modal if it doesn't exist
            this.createDynamicModal(modalId);
        }
    }

    closeModal() {
        const modals = document.querySelectorAll('[data-modal]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    createDynamicModal(modalId) {
        const modalContent = this.getModalContent(modalId);
        if (!modalContent) return;

        const modal = document.createElement('div');
        modal.setAttribute('data-modal', modalId);
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = modalContent;

        document.body.appendChild(modal);

        // Add close functionality
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeModal();
            }
        });
    }

    getModalContent(modalId) {
        const contents = {
            'help': `
                <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
                    <h2 class="text-xl font-bold mb-4">Keyboard Shortcuts</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <h3 class="font-semibold mb-2">Navigation</h3>
                            <ul class="space-y-1">
                                <li><kbd>Ctrl+Shift+D</kbd> - Toggle Dev Tools</li>
                                <li><kbd>Ctrl+R</kbd> - Refresh Page</li>
                                <li><kbd>F11</kbd> - Toggle Fullscreen</li>
                                <li><kbd>Alt+F4</kbd> - Close Window</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Application</h3>
                            <ul class="space-y-1">
                                <li><kbd>Ctrl+Shift+S</kbd> - Sync Data</li>
                                <li><kbd>Ctrl+Shift+O</kbd> - Toggle Offline</li>
                                <li><kbd>Ctrl+Shift+P</kbd> - Open POS</li>
                                <li><kbd>Ctrl+N</kbd> - New Sale</li>
                            </ul>
                        </div>
                    </div>
                    <button onclick="window.desktopApp.closeModal()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Close</button>
                </div>
            `,
            'about': `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h2 class="text-xl font-bold mb-4">About MyStockMaster</h2>
                    <p class="mb-4">Desktop version of MyStockMaster inventory management system.</p>
                    <p class="text-sm text-gray-600 mb-4">Version: 1.0.0</p>
                    <button onclick="window.desktopApp.closeModal()" class="px-4 py-2 bg-blue-500 text-white rounded">Close</button>
                </div>
            `,
            'export-data': `
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h2 class="text-xl font-bold mb-4">Export Data</h2>
                    <div class="space-y-3">
                        <button class="w-full px-4 py-2 bg-green-500 text-white rounded">Export Products</button>
                        <button class="w-full px-4 py-2 bg-blue-500 text-white rounded">Export Sales</button>
                        <button class="w-full px-4 py-2 bg-purple-500 text-white rounded">Export Customers</button>
                    </div>
                    <button onclick="window.desktopApp.closeModal()" class="mt-4 w-full px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                </div>
            `
        };

        return contents[modalId] || null;
    }

    // Window management
    setupWindowManagement() {
        // Handle window focus/blur
        window.addEventListener('focus', () => {
            document.body.classList.add('window-focused');
        });

        window.addEventListener('blur', () => {
            document.body.classList.remove('window-focused');
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            this.handleWindowResize();
        });
    }

    handleWindowResize() {
        // Adjust UI elements based on window size
        const width = window.innerWidth;
        const height = window.innerHeight;

        if (width < 768) {
            document.body.classList.add('mobile-view');
        } else {
            document.body.classList.remove('mobile-view');
        }

        // Emit resize event for Livewire components
        if (window.Livewire) {
            window.Livewire.dispatch('windowResized', { width, height });
        }
    }

    // Form enhancements
    setupFormEnhancements() {
        // Auto-focus first input in modals
        document.addEventListener('DOMNodeInserted', (e) => {
            if (e.target.classList && e.target.classList.contains('modal')) {
                const firstInput = e.target.querySelector('input, select, textarea');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });

        // Enhanced tab navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }

    handleTabNavigation(e) {
        const focusableElements = document.querySelectorAll(
            'input, select, textarea, button, [tabindex]:not([tabindex="-1"])'
        );
        
        const focusedIndex = Array.from(focusableElements).indexOf(document.activeElement);
        
        if (e.shiftKey) {
            // Shift+Tab - go backwards
            if (focusedIndex === 0) {
                e.preventDefault();
                focusableElements[focusableElements.length - 1].focus();
            }
        } else {
            // Tab - go forwards
            if (focusedIndex === focusableElements.length - 1) {
                e.preventDefault();
                focusableElements[0].focus();
            }
        }
    }

    // Loading states
    setupLoadingStates() {
        // Global loading indicator
        this.loadingIndicator = document.createElement('div');
        this.loadingIndicator.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded shadow-lg z-50 hidden';
        this.loadingIndicator.innerHTML = '<div class="flex items-center"><div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div><span>Loading...</span></div>';
        document.body.appendChild(this.loadingIndicator);
    }

    showLoadingState(message = 'Loading...') {
        this.loadingIndicator.querySelector('span').textContent = message;
        this.loadingIndicator.classList.remove('hidden');
    }

    hideLoadingState() {
        this.loadingIndicator.classList.add('hidden');
    }

    // Desktop features setup
    setupDesktopFeatures() {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Setup drag and drop for file handling
        this.setupDragAndDrop();

        // Setup context menus
        this.setupContextMenus();
    }

    setupDragAndDrop() {
        document.addEventListener('dragover', (e) => {
            e.preventDefault();
            document.body.classList.add('drag-over');
        });

        document.addEventListener('dragleave', (e) => {
            if (e.clientX === 0 && e.clientY === 0) {
                document.body.classList.remove('drag-over');
            }
        });

        document.addEventListener('drop', (e) => {
            e.preventDefault();
            document.body.classList.remove('drag-over');
            
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                this.handleFileDrop(files);
            }
        });
    }

    async handleFileDrop(files) {
        const fileData = files.map(file => ({
            name: file.name,
            size: file.size,
            type: file.type
        }));

        try {
            const response = await fetch('/desktop/action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    action: 'handle_file_drop',
                    params: { files: fileData }
                })
            });

            const result = await response.json();
            if (result.success) {
                this.showNotification('Files Processed', `${files.length} files processed successfully`, 'success');
            }
        } catch (error) {
            this.showNotification('File Error', 'Failed to process dropped files', 'error');
        }
    }

    setupContextMenus() {
        document.addEventListener('contextmenu', (e) => {
            // Custom context menu for desktop app
            if (this.isDesktop) {
                e.preventDefault();
                this.showContextMenu(e.clientX, e.clientY);
            }
        });
    }

    showContextMenu(x, y) {
        // This would show a custom context menu
        console.log('Context menu requested at:', x, y);
    }
}

// Initialize desktop app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.desktopApp = new DesktopApp();
});

// Export for global access
window.DesktopApp = DesktopApp;