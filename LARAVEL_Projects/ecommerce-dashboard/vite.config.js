import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/Http/Controllers/**',
                'app/Models/**',
                'app/Http/Requests/**',
            ],
        }),
        react({
            jsxImportSource: 'react',
            jsxRuntime: 'automatic',
            exclude: [/node_modules/, /\.config\..*/],
            babel: {
                babelrc: false,
                configFile: false,
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    server: {
        middlewareMode: false,
        hmr: {
            host: 'localhost',
            port: 5173,
            protocol: 'ws',
        },
    },
    optimizeDeps: {
        include: ['react', 'react-dom', '@inertiajs/react'],
    },
});
