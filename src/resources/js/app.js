import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import VueRouter from "vue-router";
import router from "./router";
import store from "./store";
import App from "./App.vue";

Vue.use(VueRouter, Vuetify);

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
