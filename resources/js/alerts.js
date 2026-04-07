// Custom Alert System for myStockMaster
// Handles alert events dispatched from Livewire components

// Initialize alert system when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Listen for Livewire alert events
    window.addEventListener('alert', function (event) {
        const { type, message, options = {} } = event.detail;
        showAlert(type, message, options);
    });

    // Listen for Livewire confirm events
    window.addEventListener('confirm', function (event) {
        const { message, options = {} } = event.detail;

        showConfirm({
            title: options.title || 'Are you sure?',
            text: message,
            icon: options.icon || 'warning',
            confirmButtonText: options.confirmButtonText || 'Yes, delete it!',
            cancelButtonText: options.cancelButtonText || 'Cancel',
            confirmButtonColor: options.confirmButtonColor,
            cancelButtonColor: options.cancelButtonColor,
            ...options,
        }).then((result) => {
            if (result.isConfirmed) {
                // If options.onConfirmed is a string, dispatch it as an event
                if (typeof options.onConfirmed === 'string') {
                    // Check if it should be dispatched to component or browser
                    if (options.to) {
                        Livewire.dispatchTo(options.to, options.onConfirmed, options.params || {});
                    } else {
                        Livewire.dispatch(options.onConfirmed, options.params || {});
                    }
                }
            }
        });
    });
});

/**
 * Show confirmation dialog
 * @param {object} options - Confirmation options
 * @returns {Promise} Resolves with { isConfirmed: boolean }
 */
function showConfirm(options = {}) {
    return new Promise((resolve) => {
        const {
            title = 'Are you sure?',
            text = '',
            icon = 'warning',
            confirmButtonText = 'Confirm',
            cancelButtonText = 'Cancel',
            confirmButtonColor = null,
            cancelButtonColor = null,
        } = options;

        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.className =
            'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm animate-fade-in';

        // Create modal
        const modal = document.createElement('div');
        modal.className =
            'bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden animate-scale-up';

        // Get icon
        let iconHtml = '';
        if (icon === 'warning') {
            iconHtml = `
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-warning-50 mb-4">
                    <i class="fas fa-exclamation-triangle text-warning-600 text-2xl"></i>
                </div>`;
        } else if (icon === 'error') {
            iconHtml = `
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-error-50 mb-4">
                    <i class="fas fa-times-circle text-error-600 text-2xl"></i>
                </div>`;
        } else if (icon === 'info') {
            iconHtml = `
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-50 mb-4">
                    <i class="fas fa-info-circle text-primary-600 text-2xl"></i>
                </div>`;
        } else if (icon === 'success') {
            iconHtml = `
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-success-50 mb-4">
                    <i class="fas fa-check-circle text-success-600 text-2xl"></i>
                </div>`;
        }

        // Determine button styles
        const confirmBtnClass =
            confirmButtonColor && confirmButtonColor.startsWith('bg-')
                ? `px-6 py-2.5 rounded-lg text-white font-semibold transition-all hover:brightness-110 active:scale-95 ${confirmButtonColor}`
                : `px-6 py-2.5 rounded-lg text-white font-semibold transition-all hover:brightness-110 active:scale-95 bg-primary-600`;

        const confirmBtnStyle =
            confirmButtonColor && !confirmButtonColor.startsWith('bg-')
                ? `background-color: ${confirmButtonColor};`
                : '';

        const cancelBtnClass =
            cancelButtonColor && cancelButtonColor.startsWith('bg-')
                ? `px-6 py-2.5 rounded-lg font-semibold transition-all hover:brightness-110 active:scale-95 ${cancelButtonColor}`
                : `px-6 py-2.5 rounded-lg text-gray-600 bg-gray-100 font-semibold transition-all hover:bg-gray-200 active:scale-95`;

        const cancelBtnStyle =
            cancelButtonColor && !cancelButtonColor.startsWith('bg-')
                ? `background-color: ${cancelButtonColor};`
                : '';

        modal.innerHTML = `
            <div class="p-8 text-center">
                ${iconHtml}
                <h3 class="text-2xl font-bold text-gray-900 mb-2">${title}</h3>
                <p class="text-gray-500 text-lg leading-relaxed">${text}</p>
            </div>
            <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 flex flex-row-reverse gap-3">
                <button id="confirm-btn" class="${confirmBtnClass}" style="${confirmBtnStyle}">
                    ${confirmButtonText}
                </button>
                <button id="cancel-btn" class="${cancelBtnClass}" style="${cancelBtnStyle}">
                    ${cancelButtonText}
                </button>
            </div>
        `;

        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);

        const confirmBtn = modal.querySelector('#confirm-btn');
        const cancelBtn = modal.querySelector('#cancel-btn');

        const close = (confirmed) => {
            backdrop.classList.remove('animate-fade-in');
            backdrop.classList.add('animate-fade-out');
            modal.classList.remove('animate-scale-up');
            modal.classList.add('animate-scale-down');

            setTimeout(() => {
                if (backdrop.parentNode) {
                    document.body.removeChild(backdrop);
                }
                resolve({ isConfirmed: confirmed });
            }, 200);
        };

        confirmBtn.addEventListener('click', () => close(true));
        cancelBtn.addEventListener('click', () => close(false));

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) close(false);
        });

        const handleEsc = (e) => {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', handleEsc);
                close(false);
            }
        };
        document.addEventListener('keydown', handleEsc);

        // Focus confirm button by default
        setTimeout(() => confirmBtn.focus(), 100);
    });
}

/**
 * Show alert notification
 * @param {string} type - Alert type (success, error, warning, info)
 * @param {string} message - Alert message
 * @param {object} options - Additional options
 */
function showAlert(type, message, options = {}) {
    // Default options
    const defaultOptions = {
        duration: 5000,
        position: 'top-right',
        showCloseButton: true,
        ...options,
    };

    // Create alert element
    const alertElement = createAlertElement(type, message, defaultOptions);

    // Add to DOM
    const container = getOrCreateAlertContainer(defaultOptions.position);
    container.appendChild(alertElement);

    // Auto-remove after duration
    if (defaultOptions.duration > 0) {
        setTimeout(() => {
            removeAlert(alertElement);
        }, defaultOptions.duration);
    }

    // Add close button functionality
    if (defaultOptions.showCloseButton) {
        const closeButton = alertElement.querySelector('.alert-close');
        if (closeButton) {
            closeButton.addEventListener('click', () => removeAlert(alertElement));
        }
    }
}

/**
 * Create alert HTML element
 * @param {string} type - Alert type
 * @param {string} message - Alert message
 * @param {object} options - Alert options
 * @returns {HTMLElement} Alert element
 */
function createAlertElement(type, message, options) {
    const alertDiv = document.createElement('div');
    const { icon, bgClass, textClass, borderClass } = getAlertStyles(type);

    alertDiv.className = `alert alert-${type} flex items-center p-4 mb-3 rounded-xl shadow-soft border-l-4 animate-slide-in-right max-w-md w-full pointer-events-auto ${bgClass} ${textClass} ${borderClass}`;
    alertDiv.setAttribute('role', 'alert');

    alertDiv.innerHTML = `
        <div class="flex items-center w-full">
            <div class="flex-shrink-0">
                <i class="${icon} text-lg"></i>
            </div>
            <div class="ml-3 flex-grow font-medium leading-relaxed">
                ${message}
            </div>
            ${
                options.showCloseButton
                    ? `
                <button type="button" class="alert-close ml-4 -mr-1 p-1.5 rounded-lg transition-colors hover:bg-black/5 inline-flex items-center justify-center" aria-label="Close">
                    <i class="fas fa-times text-sm opacity-50"></i>
                </button>
            `
                    : ''
            }
        </div>
    `;

    return alertDiv;
}

/**
 * Get alert styles based on type
 * @param {string} type - Alert type
 * @returns {object} Style configuration
 */
function getAlertStyles(type) {
    const styles = {
        success: {
            icon: 'fas fa-check-circle',
            bgClass: 'bg-success-50',
            textClass: 'text-success-700',
            borderClass: 'border-success-500',
        },
        error: {
            icon: 'fas fa-exclamation-circle',
            bgClass: 'bg-error-50',
            textClass: 'text-error-700',
            borderClass: 'border-error-500',
        },
        warning: {
            icon: 'fas fa-exclamation-triangle',
            bgClass: 'bg-warning-50',
            textClass: 'text-warning-700',
            borderClass: 'border-warning-500',
        },
        info: {
            icon: 'fas fa-info-circle',
            bgClass: 'bg-primary-50',
            textClass: 'text-primary-700',
            borderClass: 'border-primary-500',
        },
    };

    return styles[type] || styles.info;
}

/**
 * Get or create alert container
 * @param {string} position - Container position
 * @returns {HTMLElement} Container element
 */
function getOrCreateAlertContainer(position) {
    const containerId = `alert-container-${position}`;
    let container = document.getElementById(containerId);

    if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        container.className = 'alert-container';

        // Position styles
        const positionStyles = {
            'top-right': 'position: fixed; top: 20px; right: 20px; z-index: 9999;',
            'top-left': 'position: fixed; top: 20px; left: 20px; z-index: 9999;',
            'bottom-right': 'position: fixed; bottom: 20px; right: 20px; z-index: 9999;',
            'bottom-left': 'position: fixed; bottom: 20px; left: 20px; z-index: 9999;',
        };

        container.style.cssText = positionStyles[position] || positionStyles['top-right'];
        document.body.appendChild(container);
    }

    return container;
}

/**
 * Remove alert with animation
 * @param {HTMLElement} alertElement - Alert element to remove
 */
function removeAlert(alertElement) {
    if (alertElement && alertElement.parentNode) {
        alertElement.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.parentNode.removeChild(alertElement);
            }
        }, 300);
    }
}

// CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes scaleUp {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @keyframes scaleDown {
        from { transform: scale(1); opacity: 1; }
        to { transform: scale(0.95); opacity: 0; }
    }

    .animate-fade-in { animation: fadeIn 0.2s ease-out; }
    .animate-fade-out { animation: fadeOut 0.2s ease-in; }
    .animate-scale-up { animation: scaleUp 0.2s ease-out; }
    .animate-scale-down { animation: scaleDown 0.2s ease-in; }
    .animate-slide-in-right { animation: slideInRight 0.3s ease-out; }
    .animate-slide-out-right { animation: slideOutRight 0.3s ease-in; }
    
    .alert-container {
        pointer-events: none;
    }
    
    .alert-container .alert {
        pointer-events: auto;
    }
`;
document.head.appendChild(style);

// Global functions for manual triggering
window.showAlert = showAlert;
window.showConfirm = showConfirm;

export { showAlert, showConfirm };
