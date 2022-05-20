import Axios from "axios";

const state = {
    user: null,
};

const getters = {
    check: (state) => !!state.user,
    user: (state) => (state.user ? state.user : ""),
};
const mutations = {
    setUser(state, user) {
        state.user = user;
    },
};

const actions = {
    currentUser(context) {
        axios.get("/api/me").then((response) => {
            const user = response.data;
            context.commit("setUser", user);
        });
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
