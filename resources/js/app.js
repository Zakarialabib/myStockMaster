import './bootstrap';
import '../css/app.css'; 
import "../css/theme.css";
import "../css/font.css";
import "perfect-scrollbar/css/perfect-scrollbar.css";
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

import swal from 'sweetalert2';
window.Swal = swal;

import "@fortawesome/fontawesome-free/css/all.css";

import ApexCharts from 'apexcharts';
window.ApexCharts = ApexCharts;

import Sortable from 'sortablejs';
window.Sortable = Sortable;


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
    };
});

Livewire.start();