
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('home-component', require('./components/HomeComponent.vue'));
Vue.component('search-component', require('./components/SearchComponent.vue'));
Vue.component('spotify-player-component', require('./components/SpotifyPlayerComponent.vue'));
Vue.component('playlist-component', require('./components/PlaylistComponent.vue'));
Vue.component('user-list-component', require('./components/UserListComponent.vue'));
Vue.component('chat-messages-component', require('./components/ChatMessagesComponent.vue'));
Vue.component('chat-form-component', require('./components/ChatFormComponent.vue'));

const app = new Vue({
    el: '#app',
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
