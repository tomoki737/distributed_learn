import "./bootstrap";
import Vue from "vue";
import Vuetify from "./vuetify";
import router from "./router";
import store from "./store";
import App from "./App.vue";
import VueLoaders from "vue-loaders";
import VueLoading from "vue-loading-template";
import axios, { AxiosResponse } from "axios";
import moment from "moment";

Vue.use(VueLoaders, VueLoading, moment);

const app = async () => {
    type APIResult = {
        user: User;
        message: string;
        result: boolean;
    };

    type User = {
        id: string;
        name: string;
    };
    await axios
        .get("/api/me")
        .then((res: AxiosResponse<APIResult>) => {
            const user = res.data.user;
            store.commit("auth/setUser", user);
        })
        .catch(() => {
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
