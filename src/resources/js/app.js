import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import VueRouter from "vue-router";
import router from "./router";
import store from "./store";
import App from "./App.vue";
import VueLoaders from 'vue-loaders';
import VueLoading from 'vue-loading-template';

Vue.use(VueRouter, Vuetify, VueLoaders, VueLoading);

const app = async () => {
    await store.dispatch('auth/currentUser');
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
