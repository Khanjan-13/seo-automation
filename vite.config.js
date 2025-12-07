import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/normal/dashboard.css', 'resources/js/normal/dashboard.js', 'resources/css/normal/document.css', 'resources/js/normal/document.js'],
            refresh: true,
        }),
    ],
});
