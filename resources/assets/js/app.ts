import 'reflect-metadata';
import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import VueRouter from 'vue-router';
import App from './presentation/App.vue';
import HomePage from './presentation/pages/home/HomePage.vue';
import {Language as Lang, translate} from './presentation/lang/Language';
import {GetDarkMode} from './domain/storage/usecase/GetDarkMode';
import LoginTokenPage from './presentation/pages/login/LoginTokenPage.vue';
import {GetSelf} from './domain/user/usecase/GetSelf';
import {GetDefaultData} from './Local';
import {GetAppConfig} from './domain/appconfig/usecase/GetAppConfig';
import FaqPage from './presentation/pages/faq/FaqPage.vue';
import VueDi from '@rhangai/vue-di';
import {container, injectable} from 'tsyringe';
import DependencyContainer from 'tsyringe/dist/typings/types/dependency-container';
import {configureContainer} from "./di/ConfigureContainer";

@injectable()
class AppSetup {
  constructor(
    private readonly getDarkMode: GetDarkMode,
    private readonly getDefaultData: GetDefaultData,
    private readonly getAppConfig: GetAppConfig,
    private readonly getSelf: GetSelf
  ) {}

  setup(container: DependencyContainer) {
    Vue.use(VueRouter);
    Vue.use(Vuetify);
    Vue.use(VueDi, {container});

    const routes = [
      {path: '/', component: HomePage},
      {path: '/faq', component: FaqPage},
      {path: '/login/token', component: LoginTokenPage},
    ];

    const router = new VueRouter({
      mode: 'history',
      routes,
    });

    const vuetify = new Vuetify({
      theme: {
        dark: this.getDarkMode.execute(),
      },
    });

    Vue.prototype.$local = Vue.observable(this.getDefaultData.get());
    Vue.prototype.$lang = Lang;
    Vue.prototype.$ll = translate;

    const app = new Vue({
      vuetify,
      router,
      components: {App},
    }).$mount('#app');

    void this.getAppConfig.execute();
    void this.getSelf.execute().then(user => {
      app.$local.user = user;
    });
  }
}

const configuredContainer = configureContainer(container);

const appSetup = configuredContainer.resolve(AppSetup);
appSetup.setup(configuredContainer);
