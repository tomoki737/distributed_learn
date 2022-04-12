import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./Home.vue";
import Login from "./auth/Login.vue";
import Register from "./auth/Register.vue";
import About from "./auth/About.vue";
import QuestionCreate from "./question/QuestionCreate.vue";
import QuestionEdit from "./question/QuestionEdit.vue";
import QuestionIndex from "./question/QuestionIndex.vue";
import QuestionAnswer from "./question/QuestionAnswer.vue";
import TagsShow from "./tag/TagsShow.vue";

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

    {
        path: "/question/create",
        name: "question.create",
        component: QuestionCreate,
    },

    {
        path: "/question/:question_id/edit",
        name: "question.edit",
        component: QuestionEdit,
        props: true
    },

    {
        path: "/question/index",
        name: "question.index",
        component: QuestionIndex,
    },

    {
        path: "/question/answer",
        name: "question.answer",
        component: QuestionAnswer,
    },

    {
        path: "/tags/:name",
        name: "tags.show",
        component: TagsShow,
        props: true
    },

];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
