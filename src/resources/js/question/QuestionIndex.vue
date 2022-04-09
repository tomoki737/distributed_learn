<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading">
      <div v-for="question in questions" :key="question.id">
        <question-index-card :question="question" @get ="getQuestions"></question-index-card>
      </div>
    </v-container>
  </div>
</template>
<script>
import Loading from "../components/Loading.vue";
import QuestionIndexCard from "../components/QuestionIndexCard.vue";
export default {
  components: {
    Loading,
    QuestionIndexCard,
  },

  data() {
    return {
      questions: [],
      loading: true,
    };
  },

  methods: {
    getQuestions() {
      axios.get("/api/question").then((response) => {
        this.questions = response.data.questions;
        this.loading = false;
      });
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
