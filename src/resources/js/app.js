import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import VueRouter from "vue-router";
import router from "./router";
import store from "./store";
import App from "./App.vue";
import VueLoaders from "vue-loaders";
import VueLoading from "vue-loading-template";
import VueAxios from 'vue-axios';
import VueSocialauth from 'vue-social-auth';

Vue.use(VueRouter, Vuetify, VueLoaders, VueLoading,VueAxios,VueSocialauth);
Vue.use(VueSocialauth, {
    providers: {
        google: {
            clientId: '501138471648-pnl9724o0lrpus1o10htrblsv9j01qfs.apps.googleusercontent.com',
            client_secret: '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',
            redirectUri: 'http://localhost'
        }
    }
});
const app = async () => {
    await store.dispatch("auth/currentUser");
    new Vue({
        el: "#app",
        router,
        store,
        vuetify: Vuetify,
        components: {
            App,
        },
        template: "<App/>",
    });
};

app();
