
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
import {LanguageText} from "./presentation/lang/LanguageText";
import lang, {CurrentLanguage, translatedText} from "./presentation/lang/Language";
import { AnonymousUser, AuthUser } from "./domain/common/model/User";
import Language from "./domain/common/model/Language";
import GetDarkMode from "./domain/storage/usecase/GetDarkMode";

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
        dark: GetDarkMode()
    }
});


Vue.prototype.$user = Vue.observable(AnonymousUser as AuthUser);
Vue.prototype.$lang = lang;
Vue.prototype.$currentLanguage = CurrentLanguage;
Vue.prototype.$ll = function(
    text: LanguageText,
    capitalize: boolean = false,
    language: Language = CurrentLanguage.language
): string {
    let txt = translatedText(language, text);
    if (capitalize) txt = txt.charAt(0).toUpperCase() + txt.slice(1);
    return txt;
}

const app = new Vue({
    vuetify,
    router,
    components: {App},
}).$mount('#app');
