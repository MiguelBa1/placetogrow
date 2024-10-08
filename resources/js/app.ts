import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18nInstance } from '@/locales';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import { toastOptions } from '@/Lib/toast';
import { VueQueryPlugin } from '@tanstack/vue-query'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const i18n = createI18nInstance(props.initialPage.props.locale as string);

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .use(Toast, toastOptions)
            .use(VueQueryPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
