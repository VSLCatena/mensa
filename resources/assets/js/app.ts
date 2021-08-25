
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
import {LanguageText} from "./lang/LanguageTypes";
import lang, {CurrentLanguage, Language} from "./lang/Language";
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

Vue.prototype.$ll = function(
    text: LanguageText,
    capitalize: boolean = false,
    language: Language = CurrentLanguage.language
): string {
    let txt = language.getText(text);
    if (capitalize) txt = txt.charAt(0).toUpperCase() + txt.slice(1);
    return txt;
}

Vue.prototype.$lang = lang;

const app = new Vue({
    vuetify,
    router,
    methods: {
        $toggleDarkMode: function() {
            this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
        },
        $toggleLanguage: function() {
            CurrentLanguage.language = new Language(CurrentLanguage.language.language == "nl" ? "en": "nl")
        }
    },
    computed: {
        $isDarkMode: function(): boolean {
            return this.$vuetify.theme.dark;
        }
    }
}).$mount('#app');
