import './bootstrap';
import '../css/app.css'; 
import "../css/theme.css";
import "../css/select.css";
import "perfect-scrollbar/css/perfect-scrollbar.css";
import "select2";
import "select2/dist/css/select2.min.css";

// import "./chart-config";
// require('../../vendor/bastinald/laravel-livewire-modals/resources/js/modals');

import swal from 'sweetalert2';

window.Swal = swal;

// import {livewire_hot_reload} from 'virtual:livewire-hot-reload'

// livewire_hot_reload();

import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import focus from "@alpinejs/focus";
import intersect from "@alpinejs/intersect";

Alpine.plugin(focus);
Alpine.plugin(intersect);

import PerfectScrollbar from "perfect-scrollbar";
window.PerfectScrollbar = PerfectScrollbar;

Alpine.data("mainState", () => {
    
    let lastScrollTop = 0;
    
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
        return (
            !!window.matchMedia &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
        );
    };
    const setTheme = (value) => {
        window.localStorage.setItem("dark", value);
    };
    return {
        init,
        isDarkMode: getTheme(),
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            setTheme(this.isDarkMode);
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
    };
});


Alpine.plugin(collapse)

window.Alpine = Alpine;

Alpine.start();