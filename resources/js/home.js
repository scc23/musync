require('./bootstrap');
window.Vue = require('vue');

Vue.component('home-component', require('./components/HomeComponent.vue'));

const homeApp = new Vue({
    el: "#home-app"
});
