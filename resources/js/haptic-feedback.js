/**
 * Haptic Feedback Manager
 * Memberikan sensasi getaran halus untuk interaksi UI di mobile
 */

class HapticFeedback {
    constructor() {
        this.isSupported = this.checkSupport();
        this.isEnabled = true;

        if (this.isSupported) {
            this.init();
        }
    }

    /**
     * Check if Vibration API is supported
     */
    checkSupport() {
        return ('vibrate' in navigator) && (
            ('ontouchstart' in window) ||
            ((navigator.maxTouchPoints || 0) > 0) ||
            /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        );
    }

    /**
     * Initialize haptic feedback
     */
    init() {
        // Setup event listeners for interactive elements
        this.setupListeners();

        // Listen for new elements added to DOM (untuk content dinamis)
        this.observeDOM();
    }

    /**
     * Different types of haptic patterns
     */
    patterns = {
        // Soft tap untuk button dan link biasa
        tap: 10,

        // Medium feedback untuk menu navigation
        navigate: [15, 10],

        // Double tap untuk action penting
        select: [20, 30, 20],

        // Long press feedback
        longPress: 50,

        // Success feedback
        success: [10, 50, 10],

        // Error feedback
        error: [30, 10, 30, 10, 30],

        // Warning feedback
        warning: [25, 25],

        // Scroll feedback (sangat halus)
        scroll: 5,

        // Swipe feedback
        swipe: 8,

        // Toggle switch
        toggle: [10, 5, 10]
    };

    /**
     * Execute haptic feedback
     */
    vibrate(pattern = 'tap') {
        if (!this.isSupported || !this.isEnabled) return;

        const vibrationPattern = this.patterns[pattern] || this.patterns.tap;

        try {
            navigator.vibrate(vibrationPattern);
        } catch (e) {
            console.log('Haptic feedback not available:', e);
        }
    }

    /**
     * Setup event listeners for all interactive elements
     */
    setupListeners() {
        // Buttons & Links
        document.addEventListener('touchstart', (e) => {
            const target = e.target.closest('button, a, [role="button"], .btn, .clickable');
            if (target && !target.disabled) {
                // Check element type for appropriate feedback
                if (target.closest('.bottom-nav, nav')) {
                    this.vibrate('navigate');
                } else if (target.classList.contains('btn-primary')) {
                    this.vibrate('select');
                } else {
                    this.vibrate('tap');
                }
            }
        }, { passive: true });

        // Form inputs
        document.addEventListener('focus', (e) => {
            if (e.target.matches('input, textarea, select')) {
                this.vibrate('tap');
            }
        }, true);

        // Checkboxes & Radio buttons
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[type="checkbox"], input[type="radio"]')) {
                this.vibrate('toggle');
            }
        });

        // Dropdown & Select menus
        document.addEventListener('change', (e) => {
            if (e.target.matches('select')) {
                this.vibrate('select');
            }
        });

        // Modal open/close
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-dismiss="modal"], [x-on\\:click*="Modal"]')) {
                this.vibrate('navigate');
            }
        });

        // Tab switches
        document.addEventListener('click', (e) => {
            if (e.target.closest('[role="tab"], .tab-item')) {
                this.vibrate('navigate');
            }
        });

        // Long press detection
        let pressTimer = null;
        let longPressTriggered = false;

        document.addEventListener('touchstart', (e) => {
            longPressTriggered = false;
            pressTimer = setTimeout(() => {
                const target = e.target.closest('button, a, [role="button"], .long-press');
                if (target) {
                    this.vibrate('longPress');
                    longPressTriggered = true;
                }
            }, 500);
        }, { passive: true });

        document.addEventListener('touchend', () => {
            clearTimeout(pressTimer);
        }, { passive: true });

        document.addEventListener('touchcancel', () => {
            clearTimeout(pressTimer);
        }, { passive: true });

        // Swipe gestures
        let touchStartX = 0;
        let touchStartY = 0;

        document.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        document.addEventListener('touchend', (e) => {
            if (!e.changedTouches.length) return;

            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;

            const deltaX = touchEndX - touchStartX;
            const deltaY = touchEndY - touchStartY;

            // Detect swipe (minimum 50px movement)
            if (Math.abs(deltaX) > 50 || Math.abs(deltaY) > 50) {
                const target = e.target.closest('.swipeable, .carousel-item');
                if (target) {
                    this.vibrate('swipe');
                }
            }
        }, { passive: true });

        // Pull to refresh feedback
        let lastScrollTop = 0;
        let pullTriggered = false;

        document.addEventListener('touchmove', (e) => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop === 0 && e.touches[0].clientY > touchStartY + 50 && !pullTriggered) {
                this.vibrate('scroll');
                pullTriggered = true;
            }

            if (scrollTop > 0) {
                pullTriggered = false;
            }

            lastScrollTop = scrollTop;
        }, { passive: true });

        // Success/Error feedback for forms
        document.addEventListener('submit', (e) => {
            if (e.target.matches('form')) {
                // Check if form is valid
                if (e.target.checkValidity()) {
                    this.vibrate('success');
                } else {
                    this.vibrate('error');
                }
            }
        });

        // Alpine.js integration
        if (window.Alpine) {
            // Listen for Alpine modal/dropdown toggles
            document.addEventListener('alpine:initialized', () => {
                // Re-setup listeners for Alpine components
                this.setupAlpineListeners();
            });
        }
    }

    /**
     * Setup Alpine.js specific listeners
     */
    setupAlpineListeners() {
        // Alpine click handlers (hindari selector tidak valid di desktop)
        try {
            document.querySelectorAll('[x-on\\:click]').forEach((element) => {
                element.addEventListener('touchstart', () => {
                    this.vibrate('tap');
                }, { passive: true });
            });
        } catch (e) {
            // ignore selector errors
        }
    }

    /**
     * Observe DOM for dynamically added elements
     */
    observeDOM() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    // Re-setup Alpine listeners for new elements
                    setTimeout(() => {
                        this.setupAlpineListeners();
                    }, 100);
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Enable/disable haptic feedback
     */
    toggle(enabled = true) {
        this.isEnabled = enabled;
    }
}

// Initialize haptic feedback when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.hapticFeedback = new HapticFeedback();
    });
} else {
    window.hapticFeedback = new HapticFeedback();
}

// Export for use in other scripts
export default HapticFeedback;
