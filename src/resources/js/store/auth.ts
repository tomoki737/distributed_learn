import axios from "axios";
const state = {
    user: null,
};

const getters = {
    check: (state: any) => (state.user ? true : false),
    user: (state: any) => (state.user ? state.user : ""),
    id: (state: any) => (state.user ? state.user.id : ""),
    name: (state: any) => (state.user ? state.user.name : ""),
    email: (state: any) => (state.user ? state.user.email : ""),
};
const mutations = {
    setUser(state: any, user: object) {
        state.user = user;
    },
};

const actions = {
    async currentUser(context: any) {
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
