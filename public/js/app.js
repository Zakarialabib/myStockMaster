import './bootstrap';
import '../css/app.css'; 
import '../css/select.css'; 
import '../css/theme.css'; 
import "perfect-scrollbar/css/perfect-scrollbar.css";

import {livewire_hot_reload} from 'virtual:livewire-hot-reload'
livewire_hot_reload();

import swiper from 'swiper';
import 'swiper/css/bundle';
window.Swiper = swiper;

import "@fortawesome/fontawesome-free/css/all.css";

import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';
window.flatpickr = flatpickr;

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from "@alpinejs/focus";
import intersect from "@alpinejs/intersect";

import Sortable from 'sortablejs';
window.Sortable = Sortable;

Alpine.plugin(focus);
Alpine.plugin(intersect);

import PerfectScrollbar from "perfect-scrollbar";
window.PerfectScrollbar = PerfectScrollbar;

Alpine.data("mainState", () => {
    
    const loadingMask = {
        pageLoaded: false,
        init() {
            window.onload = (event) => {
                this.pageLoaded = true;
            };
        },
    };

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

    const enableTheme = (value) => {
        document.body.dir = value ? "rtl" : "ltr";
    };

    return {
        loadingMask,
        isDarkMode: getTheme(),
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            setTheme(this.isDarkMode);
        },
        isSidebarOpen: sessionStorage.getItem("sidebarOpen") === "true",
        handleSidebarToggle() {
            this.isSidebarOpen = !this.isSidebarOpen;
            sessionStorage.setItem("sidebarOpen", this.isSidebarOpen.toString());
        },
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