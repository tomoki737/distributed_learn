import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import VueRouter from "vue-router";
import router from "./router";
import App from "./App.vue";

Vue.use(VueRouter, Vuetify);

const app = async () => {
    new Vue({
        el: "#app",
        router,
        vuetify: Vuetify,
        components: {
            App,
        },
        template: "<App/>",
    });
};

app();
