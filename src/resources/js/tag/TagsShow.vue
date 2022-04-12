<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading">
      <v-card class="mt-10 mx-auto" width="600px">
        <v-card-title
          ><h2  class="ml-6">{{ name }}</h2> <span class="ml-2">({{ questions_length }})</span></v-card-title
        ></v-card
      >
      <div v-for="question in questions" :key="question.id">
        <question-index-card
          :question="question"
          @get="getQuestions"
        ></question-index-card>
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

  props: {
    name: {
      type: String,
    },
  },

  data() {
    return {
      questions: [],
      questions_length: 0,
      loading: true,
    };
  },

  methods: {
    getQuestions() {
      axios.get("/api/tags/" + this.name).then((response) => {
        this.questions = response.data.tag.questions;
        this.questions_length = response.data.tag.questions.length;
        this.loading = false;
      });
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
