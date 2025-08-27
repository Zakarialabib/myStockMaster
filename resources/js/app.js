import './bootstrap';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import flatpickr from "flatpickr";
window.flatpickr = flatpickr;

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";
window.PerfectScrollbar = PerfectScrollbar;

import './alerts';

import swal from 'sweetalert2';
window.Swal = swal;

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import Sortable from 'sortablejs';
window.Sortable = Sortable;

Alpine.data("mainState", () => {
    
    let lastScrollTop = 0;
    
    const toggleFullscreen = (elem) => {
        elem = elem || document.documentElement;
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
        }
    };
    
    const init = function () {
        window.addEventListener("scroll", () => {
            let st =
                window.pageYOffset || document.documentElement.scrollTop;
            if (st > lastScrollTop) {
                // downscroll
                this.scrollingDown = true;
                this.scrollingUp = false;
            } else {
                // upscroll
                this.scrollingDown = false;
                this.scrollingUp = true;
                if (st == 0) {
                    //  reset
                    this.scrollingDown = false;
                    this.scrollingUp = false;
                }
            }
            lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
        });
    };    

    Alpine.data("loadingMask", () => ({
        pageLoaded: false,
        init() {
            window.onload = (event) => {
                this.pageLoaded = true
            };
        }
    }));

    const getTheme = () => {
        if (window.localStorage.getItem("dark")) {
            return JSON.parse(window.localStorage.getItem("dark"));
        }
        // Default to light mode instead of system preference
        return false;
    };
    const setTheme = (value) => {
        window.localStorage.setItem("dark", value);
    };

    const RTL = () => {
        if (window.localStorage.getItem("rtl")) {
            return JSON.parse(window.localStorage.getItem("rtl"));
          }
          return false;
    }

    const enableTheme = (isRtl) => {
        if (isRtl) {
          document.body.dir = "rtl";
        } else {
          document.body.dir = "ltr";
        }
      };
      
      enableTheme(false); // sets document.body.dir to "ltr"      

    return {
        init,
        isDarkMode: getTheme(),
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            setTheme(this.isDarkMode);
        },
        isRtl : RTL(),
        toggleRtl() {
            this.isRtl = !this.isRtl;
            enableTheme(this.isRtl);
            window.localStorage.setItem("rtl", this.isRtl);
       },
        isSidebarOpen: window.innerWidth > 1024,
        isSidebarHovered: false,
        handleSidebarHover(value) {
            if (window.innerWidth < 1024) {
                return;
            }
            this.isSidebarHovered = value;
        },
        handleWindowResize() {
            if (window.innerWidth <= 1024) {
                this.isSidebarOpen = false;
            } else {
                this.isSidebarOpen = true;
            }
        },
        scrollingDown: false,
        scrollingUp: false,
        toggleFullscreen,
    };
});

Livewire.start();