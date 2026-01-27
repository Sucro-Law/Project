import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
//import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [

                'resources/css/admin.css',
                'resources/css/app.css',
                'resources/css/dashboard.css',
                'resources/css/event.css',
                'resources/css/forgotpass.css',
                'resources/css/orgdesc.css',
                'resources/css/profile.css',
                'resources/css/settings.css',
                'resources/css/signin.css',
                'resources/css/signup.css',

                /*'resources/css/layout.css',*/
                /*'resources/css/pages.css',*/

                'resources/js/app.js',
                
            ],
            refresh: true,
        }),
        //tailwindcss(),
    ],
});