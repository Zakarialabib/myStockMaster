import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;

import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';
window.PerfectScrollbar = PerfectScrollbar;

import './alerts';

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import Sortable from 'sortablejs';
window.Sortable = Sortable;

import './theme-generator';

import appModal from './components/modal';
Alpine.data('appModal', appModal);

import appDatepicker from './components/datepicker';
Alpine.data('appDatepicker', appDatepicker);

// Theme Utilities
const Theme = {
    getDark() {
        return window.localStorage.getItem('dark')
            ? JSON.parse(window.localStorage.getItem('dark'))
            : false;
    },
    setDark(value) {
        window.localStorage.setItem('dark', value);
        document.documentElement.classList.toggle('dark', value);
    },
    getRtl() {
        return window.localStorage.getItem('rtl')
            ? JSON.parse(window.localStorage.getItem('rtl'))
            : false;
    },
    setRtl(value) {
        window.localStorage.setItem('rtl', value);
        document.documentElement.dir = value ? 'rtl' : 'ltr';
        document.body.dir = value ? 'rtl' : 'ltr';
    },
};

// Initialize Theme
if (window.localStorage.getItem('dark') !== null) {
    Theme.setDark(Theme.getDark());
}
if (window.localStorage.getItem('rtl') !== null) {
    Theme.setRtl(Theme.getRtl());
}

// Livewire Navigation Hooks - Ensure dir attribute stays in sync
document.addEventListener('livewire:navigated', () => {
    const isRtl = Theme.getRtl();
    document.documentElement.dir = isRtl ? 'rtl' : 'ltr';
    document.documentElement.classList.toggle('rtl', isRtl);
    if (window.PerfectScrollbar) {
        window.dispatchEvent(new CustomEvent('ps-reinitialize'));
    }
}, { passive: true });

// Global Alpine Components
Alpine.data('loadingMask', () => ({
    pageLoaded: false,
    init() {
        // Initial load
        this.$nextTick(() => {
            if (document.readyState === 'complete') {
                this.pageLoaded = true;
            } else {
                window.addEventListener('load', () => {
                    this.pageLoaded = true;
                });
            }
        });

        // Livewire navigation hooks
        document.addEventListener('livewire:navigating', () => {
            this.pageLoaded = false;
        });

        document.addEventListener('livewire:navigated', () => {
            this.pageLoaded = true;
        });
    },
}));

Alpine.data('mainState', (backendRtl = false) => {
    let lastScrollTop = 0;
    const hasLocalRtl = window.localStorage.getItem('rtl') !== null;
    const initialRtl = hasLocalRtl ? Theme.getRtl() : backendRtl;

    return {
        isDarkMode: Theme.getDark(),
        isRtl: initialRtl,
        isSidebarOpen: window.innerWidth > 1024,
        isSidebarHovered: false,
        scrollingDown: false,
        scrollingUp: false,

        init() {
            Theme.setRtl(this.isRtl);
            window.addEventListener('scroll', () => {
                let st = window.pageYOffset || document.documentElement.scrollTop;
                if (st > lastScrollTop) {
                    this.scrollingDown = true;
                    this.scrollingUp = false;
                } else {
                    this.scrollingDown = false;
                    this.scrollingUp = true;
                    if (st === 0) {
                        this.scrollingDown = false;
                        this.scrollingUp = false;
                    }
                }
                lastScrollTop = st <= 0 ? 0 : st;
            });

            window.addEventListener('resize', () => this.handleWindowResize());
            this.handleWindowResize();
        },

        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            Theme.setDark(this.isDarkMode);
        },

        toggleRtl() {
            this.isRtl = !this.isRtl;
            Theme.setRtl(this.isRtl);
        },

        handleSidebarHover(value) {
            if (window.innerWidth < 1024) return;
            this.isSidebarHovered = value;
        },

        handleWindowResize() {
            this.isSidebarOpen = window.innerWidth > 1024;
        },

        toggleFullscreen(elem = document.documentElement) {
            if (
                !document.fullscreenElement &&
                !document.mozFullScreenElement &&
                !document.webkitFullscreenElement &&
                !document.msFullscreenElement
            ) {
                if (elem.requestFullscreen) elem.requestFullscreen();
                else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
                else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
                else if (elem.webkitRequestFullscreen)
                    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else {
                if (document.exitFullscreen) document.exitFullscreen();
                else if (document.msExitFullscreen) document.msExitFullscreen();
                else if (document.mozCancelFullScreen) document.mozCancelFullScreen();
                else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            }
        },
    };
});

Livewire.start();
