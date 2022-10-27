import Axios from "axios";

const state = {
    category: "",
    question: "",
    answer: "",
    tags: "",
    share: true,
};

const getters = {
    create_input: (state: any) => {
        return {
            category: state.category,
            question: state.question,
            tags: state.tags,
            answer: state.answer,
            share: state.share,
        };
    },
};

const mutations = {
    setInput(state: any, input: any) {
        state.category = input.category;
        state.question = input.question;
        state.tags = input.tags;
        state.answer = input.answer;
        state.share = input.share;
    },
};

// const actions = {
//     currentUser(context) {
//         Axios.get("/api/me").then((response) => {
//             const user = response.data;
//             context.commit("setUser", user);
//         });
//     },
// };

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    // actions,
};
