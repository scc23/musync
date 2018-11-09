require('./bootstrap');
window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core';
import { faLock, faPlayCircle, faPauseCircle } from '@fortawesome/free-solid-svg-icons';
library.add(faLock, faPlayCircle, faPauseCircle);

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

const homeApp = new Vue({
    el: "#home-app",
    components: {
        'home-component': require('./components/HomeComponent.vue')
    }
});

const roomApp = new Vue({
    el: '#room-app',
    components: {
        'room-component': require('./components/RoomComponent.vue')
    },
    created() {
    },
    data: {
        messages: []
    },
    /* going to fix with issue #16
    created() {
        this.fetchMessages();
    },

    methods: {
        fetchMessages() {
            axios.get('/room/{id}').then(response => {
                this.messages = response.data;
            });
        },

        addMessage(message) {
            this.messages.push(message);

            axios.post('/room/{id}', message).then(response => {
              console.log(response.data);
            });
        }
    }
    */
});
