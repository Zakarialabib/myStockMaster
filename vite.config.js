import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    cacheDir: 'node_modules/.vite',
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Livewire/**/*.php',
                'app/Actions/**/*.php',
                'routes/**/*.php',
                '!storage/**',
                '!vendor/**',
            ],
        }),
    ],
    server: {
        hmr: { overlay: false },
        watch: {
            usePolling: false,
            ignored: [
                '**/storage/**',
                '**/bootstrap/cache/**',
                '**/vendor/**',
                '**/node_modules/**',
            ],
        },
    },
    build: {
        target: 'es2022',
        chunkSizeWarningLimit: 1000,
        rollupOptions: {
            output: {
                manualChunks: {
                    charts: ['apexcharts'],
                    realtime: ['pusher-js', 'laravel-echo'],
                },
            },
        },
    },
    envPrefix: ['VITE_'],
});
