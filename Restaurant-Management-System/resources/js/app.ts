// import '../css/app.css';

// import { createInertiaApp } from '@inertiajs/vue3';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import type { DefineComponent } from 'vue';
// import { createApp, h } from 'vue';
// import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// import { initializeTheme } from './composables/useAppearance';

// // Extend ImportMeta interface for Vite...
// declare module 'vite/client' {
//     interface ImportMetaEnv {
//         readonly VITE_APP_NAME: string;
//         [key: string]: string | boolean | undefined;
//     }

//     interface ImportMeta {
//         readonly env: ImportMetaEnv;
//         readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
//     }
// }

// const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// createInertiaApp({
//     title: (title) => `${title} - ${appName}`,
//     resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
//     setup({ el, App, props, plugin }) {
//         createApp({ render: () => h(App, props) })
//             .use(plugin)
//             .use(ZiggyVue)
//             .mount(el);
//     },
//     progress: {
//         color: '#4B5563',
//     },
// });

// // This will set light / dark mode on page load...
// initializeTheme();


// import { createApp, h } from 'vue'
// import { createInertiaApp } from '@inertiajs/vue3'

// createInertiaApp({
//   resolve: name => {
//     const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
//     return pages[`./Pages/${name}.vue`]
//   },
//   setup({ el, App, props, plugin }) {
//     createApp({ render: () => h(App, props) })
//       .use(plugin)
//       .mount(el)
//   },
// })

import { createApp, h, DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import '../css/app.css';

createInertiaApp({
  resolve: async (name: string): Promise<DefineComponent> => {
    const pages = import.meta.glob<{ default: DefineComponent }>("./Pages/**/*.vue");
    
    const pageImport = pages[`./Pages/${name}.vue`];

    if (!pageImport) {
      throw new Error(`Page ${name} not found`);
    }

    const page = await pageImport();
    return page.default;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el);
  },
});

