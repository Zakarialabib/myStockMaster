// Custom Alert System for myStockMaster
// Handles alert events dispatched from Livewire components

// Initialize alert system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Listen for Livewire alert events
    window.addEventListener('alert', function(event) {
        const { type, message, options = {} } = event.detail;
        showAlert(type, message, options);
    });
});

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
        ...options
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
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    
    // Get icon and color based on type
    const { icon, bgColor, textColor } = getAlertStyles(type);
    
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="${icon} me-2"></i>
            <div class="flex-grow-1">${message}</div>
            ${options.showCloseButton ? '<button type="button" class="btn-close alert-close" aria-label="Close"></button>' : ''}
        </div>
    `;
    
    // Apply custom styles
    alertDiv.style.cssText = `
        background-color: ${bgColor};
        color: ${textColor};
        border: none;
        border-radius: 8px;
        margin-bottom: 10px;
        padding: 12px 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
        word-wrap: break-word;
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
            bgColor: '#d4edda',
            textColor: '#155724'
        },
        error: {
            icon: 'fas fa-exclamation-circle',
            bgColor: '#f8d7da',
            textColor: '#721c24'
        },
        warning: {
            icon: 'fas fa-exclamation-triangle',
            bgColor: '#fff3cd',
            textColor: '#856404'
        },
        info: {
            icon: 'fas fa-info-circle',
            bgColor: '#d1ecf1',
            textColor: '#0c5460'
        }
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
            'bottom-left': 'position: fixed; bottom: 20px; left: 20px; z-index: 9999;'
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
    
    .alert-container {
        pointer-events: none;
    }
    
    .alert-container .alert {
        pointer-events: auto;
    }
`;
document.head.appendChild(style);

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { showAlert };
}

// Global function for manual alert triggering
window.showAlert = showAlert;