import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
//import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/dashboard.css',
                'resources/css/org-detail.css', // 👈 page-specific
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        //tailwindcss(),
    ],
});
