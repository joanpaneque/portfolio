import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from './i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Definir los idiomas soportados
type SupportedLocale = 'es' | 'ca' | 'en' | 'fr' | 'de' | 'it' | 'pt';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
            ),
        setup({ App, props, plugin }) {
            // Configuramos el idioma según lo recibido del servidor
            const locale = page.props.locale as string;
            if (locale && ['es', 'ca', 'en', 'fr', 'de', 'it', 'pt'].includes(locale)) {
                i18n.global.locale.value = locale as SupportedLocale;
            }

            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                })
                .use(i18n);
        },
    }),
);
