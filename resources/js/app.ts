import './bootstrap';
import '../css/app.css';
import 'font-awesome/css/font-awesome.min.css'

import {createApp, DefineComponent, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import {aliases, fa} from 'vuetify/iconsets/fa4'

import { Chart as ChartJS ,ArcElement,Tooltip, Legend } from 'chart.js'

const vuetify = createVuetify({
    components,
    directives,
    theme:{
      defaultTheme: 'dark',
    },
    icons: {
        defaultSet: 'fa',
        aliases,
        sets: {
            fa,
        },
    },
})

ChartJS.register(ArcElement,Tooltip, Legend)


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(vuetify)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

