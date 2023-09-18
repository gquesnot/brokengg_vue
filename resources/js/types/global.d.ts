import {PageProps as InertiaPageProps} from '@inertiajs/core';
import {AxiosInstance} from 'axios';
import ziggyRoute, {Config as ZiggyConfig} from 'ziggy-js';
import {PageProps as AppPageProps} from './';
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import {PropType} from "vue";
declare global {
    interface Window {
        axios: AxiosInstance;
        Echo: Echo.prototype;
        Pusher: Pusher.prototype;
    }

    var route: typeof ziggyRoute;
    var Ziggy: ZiggyConfig;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {
    }
}
