import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            server: {
                host: 'http://127.0.0.1:8000', // <— your custom local domain
                port: 8000,                                   // <— the port you want
                strictPort: true,                             // fail if 5173 is busy
                https: false,                                  // if you have a local SSL cert for .test
                cors: true,                                   // allow cross-origin loads
                hmr: {
                    protocol: 'wss',
                    host: 'http://127.0.0.1:8000',
                    port: 8000,
                },
            },
        }),
    ],
});
