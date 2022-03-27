import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./Home.vue";
import Login from "./auth/Login.vue";
import Register from "./auth/Register.vue";
import About from "./auth/About.vue";
Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/register",
        name: "register",
        component: Register,
    },
    {
        path: "/about",
        name: "about",
        component: About,
    },
];
const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
