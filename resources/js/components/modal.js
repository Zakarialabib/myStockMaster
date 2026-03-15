export default (config) => ({
    show: config.show,
    loading: config.lazy,
    cached: !config.cacheContent,
    scrollPosition: 0,

    // Enhanced focusable management
    focusables() {
        if (!this.cached) return [];
        let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
        return [...this.$el.querySelectorAll(selector)]
            .filter(el => !el.hasAttribute('disabled') && !el.hasAttribute('aria-hidden'));
    },

    firstFocusable() { return this.focusables()[0]; },
    lastFocusable() { return this.focusables().slice(-1)[0]; },
    nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
    prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
    nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
    prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; },

    // Performance optimizations
    init() {
        if (config.lazy) {
            this.$watch('show', (value) => {
                if (value && this.loading) {
                    this.loadContent();
                }
            });
        }
    },

    loadContent() {
        setTimeout(() => {
            this.loading = false;
            this.cached = true;
        }, 100);
    },

    getScrollbarWidth() {
        const outer = document.createElement('div');
        outer.style.visibility = 'hidden';
        outer.style.overflow = 'scroll';
        outer.style.msOverflowStyle = 'scrollbar';
        document.body.appendChild(outer);
        const inner = document.createElement('div');
        outer.appendChild(inner);
        const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
        outer.parentNode.removeChild(outer);
        return scrollbarWidth;
    },

    open() {
        if (config.restoreScroll) {
            this.scrollPosition = window.pageYOffset;
        }

        this.show = true;
        // document.body.classList.add('overflow-hidden');
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = this.getScrollbarWidth() + 'px';

        if (config.focusable) {
            this.$nextTick(() => {
                const firstFocusable = this.firstFocusable();
                if (firstFocusable) firstFocusable.focus();
            });
        }
    },

    close() {
        if (!config.closeable || config.persistent) return;

        this.show = false;
        // document.body.classList.remove('overflow-hidden');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        if (config.restoreScroll) {
            window.scrollTo(0, this.scrollPosition);
        }

        if (!config.cacheContent) {
            this.cached = false;
            this.loading = true;
        }
    }
});
