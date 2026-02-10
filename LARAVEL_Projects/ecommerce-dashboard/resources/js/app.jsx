import './bootstrap';
import 'nprogress/nprogress.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Alpine from 'alpinejs';
import NProgress from 'nprogress';

// Configure NProgress
NProgress.configure({ 
    showSpinner: true,
    trickleSpeed: 200,
    minimum: 0.3
});

// Setup progress bar listeners
router.on('start', () => NProgress.start());
router.on('finish', () => NProgress.done());

// Disable Inertia default confirmation dialogs
router.on('before', (event) => {
    // Allow all requests without confirmation
    return true;
});

// Keep Alpine for simple interactivity
window.Alpine = Alpine;
Alpine.start();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
});
