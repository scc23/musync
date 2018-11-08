require('./bootstrap');
window.Vue = require('vue');

Vue.component('search-component', require('./components/SearchComponent.vue'));
Vue.component('spotify-player-component', require('./components/SpotifyPlayerComponent.vue'));
Vue.component('playlist-component', require('./components/PlaylistComponent.vue'));
Vue.component('user-list-component', require('./components/UserListComponent.vue'));
Vue.component('chat-messages-component', require('./components/ChatMessagesComponent.vue'));
Vue.component('chat-form-component', require('./components/ChatFormComponent.vue'));

import { library } from '@fortawesome/fontawesome-svg-core';
import { faPlayCircle, faPauseCircle } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faPlayCircle, faPauseCircle);
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

const roomApp = new Vue({
    el: '#room-app',
    created() {
        Echo.channel('test-event')
            .listen('SpotifyPlayerAction', (e) => {
                console.log(e.user);
                console.log(e.request);
            });
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
