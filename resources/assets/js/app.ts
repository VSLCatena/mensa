
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import Vue from 'vue';
import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css';
import VueRouter from 'vue-router';
import App from "./presentation/App.vue";
import Home from './presentation/pages/home/Home.vue';
import Mensa from './presentation/components/mensa/MensaItem.vue';
import lang, { translate } from "./presentation/lang/Language";
import GetDarkMode from "./domain/storage/usecase/GetDarkMode";
import LoginToken from "./presentation/pages/login/LoginToken.vue";
import GetSelf from "./domain/user/usecase/GetSelf";
import {defaultData} from "./Local";

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);
Vue.use(Vuetify);

const routes = [
    { path: '/', component: Home },
    { path: '/mensa', component: Mensa },
    { path: '/login/token', component: LoginToken }
];

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const vuetify = new Vuetify({
    theme: {
        dark: GetDarkMode()
    }
});


Vue.prototype.$local = Vue.observable(defaultData());
Vue.prototype.$lang = lang;
Vue.prototype.$ll = translate;

const app = new Vue({
    vuetify,
    router,
    components: {App},
}).$mount('#app');

GetSelf().then(user => {app.$local.user = user;});