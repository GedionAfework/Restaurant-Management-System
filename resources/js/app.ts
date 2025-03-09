import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h, reactive } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';

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

// 🔹 Authentication state
export const auth = reactive({
    user: null,
    async fetchUser() {
        try {
            const response = await fetch('/user', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`, // Ensure token is sent
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });

            if (response.ok) {
                this.user = await response.json();
            } else {
                this.user = null;
            }
        } catch (error) {
            console.error('Failed to fetch user:', error);
            this.user = null;
        }
    }
});

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

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

// 🔹 Fetch user data when the app starts
auth.fetchUser();
