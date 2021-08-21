
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

// window.Vue = require('vue');

import Vue from 'vue';
import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css';
import VueRouter from 'vue-router';
import Home from './vue/pages/home/Home.vue';
import Mensa from './vue/pages/home/components/MensaItem.vue';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);
Vue.use(Vuetify);

const routes = [
    { path: '/', component: Home },
    { path: '/mensa', component: Mensa }
]

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const vuetify = new Vuetify({
    theme: {
        dark: true
    }
});

const app = new Vue({
    vuetify,
    router
}).$mount('#app');
