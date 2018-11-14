require('./bootstrap');
window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core';
import { faPlayCircle, faPauseCircle, faStepForward, faMusic } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faPlayCircle, faPauseCircle, faStepForward, faMusic);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

const roomApp = new Vue({
    el: '#room-app',
    components: {
        'room-component': require('./components/RoomComponent.vue')
    },
    
    created() {
    },
});
