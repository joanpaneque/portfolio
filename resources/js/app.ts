import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { RouteExtenderPlugin } from './utils/routeExtender.js';

// Declare window with $trans property
declare global {
    interface Window {
        $trans: (key: string, params?: { [key: string]: any }) => any;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        const translations = props.initialPage.props.trans as { [key: string]: any };

        function trans(key: string, params?: { [key: string]: any }): any {
            // split the key by . and try to get the value from the translations object
            const keys = key.split('.');
            let value: any = translations;
            for (const k of keys) {
                if (value === undefined || value === null) {
                    return key;
                }
                value = value[k];
            }
            
            // If no translation found, return the key
            if (value === null || value === undefined) {
                return key;
            }
            
            // If params are provided and value is a string, replace placeholders
            if (params && typeof value === 'string') {
                return value.replace(/\{\s*(\w+)\s*\}/g, (match, paramKey) => {
                    return params[paramKey] !== undefined ? params[paramKey] : match;
                });
            }
            
            return value;
        }

        app.config.globalProperties.$trans = trans;
        window.$trans = trans;

        // Use the inertia plugin
        app.use(plugin);

        // First register ZiggyVue to set up the normal route functionality
        app.use(ZiggyVue);

        // Then use our plugin to override with our extended version
        app.use(RouteExtenderPlugin);

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
