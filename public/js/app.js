import './bootstrap';
import '../css/app.css'; 
import '../css/select.css'; 
import '../css/theme.css'; 
import "perfect-scrollbar/css/perfect-scrollbar.css";

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import swiper from 'swiper';
import 'swiper/css/bundle';
window.Swiper = swiper;

import "@fortawesome/fontawesome-free/css/all.css";

import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';
window.flatpickr = flatpickr;

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

    return {
        loadingMask,
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


Livewire.start();