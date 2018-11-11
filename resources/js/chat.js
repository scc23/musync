require('./bootstrap');
window.Vue = require('vue');


const chatApp = new Vue({
    el: "#chat-app",

    components: {
        'chat-component': require('./components/ChatComponent.vue'),
        'chat-messages-component': require('./ChatComponent.vue'),
        'chat-form-component': require('./ChatFormComponent.vue')
    }
    
    /* TODO: Issue #37
    data: {
        messages: []
    },
    
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