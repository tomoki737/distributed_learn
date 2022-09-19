import axios from "axios";

const state = {
    user: null,
};

const getters = {
    check: (state) => (state.user ? true : false),
    user: (state) => (state.user ? state.user : ""),
    id: (state) => (state.user ? state.user.id : ""),
    name: (state) => (state.user ? state.user.name : ""),
    email: (state) => (state.user ? state.user.email : ""),
};
const mutations = {
    setUser(state, user) {
        state.user = user;
    },
};

const actions = {
    async currentUser(context) {
        const response = await axios.get("/api/me");
        const user = response.data;
        context.commit("setUser", user);
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
