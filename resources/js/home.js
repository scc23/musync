require('./bootstrap');
window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core';
import { faLock, faUnlock } from '@fortawesome/free-solid-svg-icons';
library.add(faLock, faUnlock);

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

const homeApp = new Vue({
    el: "#home-app",
    components: {
        'home-component': require('./components/HomeComponent.vue')
    }
});
