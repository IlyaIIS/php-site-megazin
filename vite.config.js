import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/popupCloseEvents.js',
                'resources/js/popupOpenEvents.js',
                'resources/js/categorySelection.js',
                'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
