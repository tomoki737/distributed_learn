import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import VueRouter from "vue-router";
import router from "./router";
import store from "./store";
import App from "./App.vue";
import VueLoaders from "vue-loaders";
import VueLoading from "vue-loading-template";
import axios from "axios";

Vue.use(VueRouter, Vuetify, VueLoaders, VueLoading);

const app = async () => {
    const res = await axios.get("/api/me").catch(() => {
        console.log("a")
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
    });
    const user = res.data.user;
    console.log(user)
    store.commit("auth/setUser", user);

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
