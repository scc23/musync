require('./bootstrap');
window.Vue = require('vue');

const homeApp = new Vue({
    el: "#home-app",
    components: {
        'home-component': require('./components/HomeComponent.vue')
    }
});
