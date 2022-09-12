import Vue from "vue";
import VueRouter from "vue-router";
import Home from "./Home.vue";
import Login from "./auth/Login.vue";
import Register from "./auth/Register.vue";
import About from "./auth/About.vue";
import QuestionCreate from "./question/QuestionCreate.vue";
import QuestionSearch from "./question/QuestionSearch.vue";
import QuestionEdit from "./question/QuestionEdit.vue";
import QuestionIndex from "./question/QuestionIndex.vue";
import AnswerSelect from "./answer/AnswerSelect.vue";
import AnswerUnderstand from "./answer/AnswerUnderstand.vue";
import AnswerWriting from "./answer/AnswerWriting.vue";
import TagsShow from "./tag/TagsShow.vue";
import store from "./store";

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
        props: true,
    },

    {
        path: "/question/index",
        name: "question.index",
        component: QuestionIndex,
    },

    {
        path: "/question/search",
        name: "question.search",
        component: QuestionSearch,
    },

    {
        path: "/answer/understand",
        name: "answer.understand",
        component: AnswerUnderstand,
    },
    {
        path: "/answer/select",
        name: "answer.select",
        component: AnswerSelect,
    },

    {
        path: "/answer/writing",
        name: "answer.writing",
        component: AnswerWriting,
    },

    {
        path: "/tags/:name",
        name: "tags.show",
        component: TagsShow,
        props: true,
    },
];
const router = new VueRouter({
    mode: "history",
    routes,
});

router.beforeEach((to, from, next) => {
    if (!store.getters["auth/check"] && to.name !== "login" && to.name !== "register") {
            next({ name: "login" });
    } else {
        next();
    }
});
export default router;
