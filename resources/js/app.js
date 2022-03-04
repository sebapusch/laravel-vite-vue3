import '../scss/bootstrap/_cutom.scss';
import '../css/app.css';
import './bootstrap';

import 'bootstrap';

import { createApp, defineAsyncComponent } from 'vue';

createApp({
    provide: {
        __
    },
    components: {
        Welcome: defineAsyncComponent(() => import('../vue-components/pages/Welcome.vue')),
    }
}).mount('#app');
