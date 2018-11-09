require('./bootstrap');
window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core';
import { faPlayCircle, faPauseCircle } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faPlayCircle, faPauseCircle);
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

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
