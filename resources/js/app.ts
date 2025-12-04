import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h, reactive } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { Link, usePage } from '@inertiajs/vue3'; // Import Link component

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>; 
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// 🔹 Authentication state - Using Inertia's shared props instead of API call
export const auth = reactive({
    user: null,
    // User data comes from Inertia's shared props, no need for API call
    setUser(user: any) {
        this.user = user;
    }
});

createInertiaApp({
    resolve: name =>  resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ 
            render: () => {
                // 🔹 Set user from Inertia's shared props on each render
                const page = props.initialPage?.props;
                if (page?.auth?.user) {
                    auth.setUser(page.auth.user);
                } else {
                    auth.setUser(null);
                }
                return h(App, props);
            }
        })
            .use(plugin)
            .use(ZiggyVue);

        // 🔹 Register Link component globally
        vueApp.component('Link', Link); // This line registers Link globally

        // 🔹 Provide `auth` globally so components can access it
        vueApp.provide('auth', auth);

        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light/dark mode on page load...
initializeTheme();
