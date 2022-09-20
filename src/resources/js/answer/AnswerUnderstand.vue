<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading">
      <v-card elevation="2" class="mt-10 mx-auto" width="600px" height="500px">
        <v-toolbar color="blue lighten-3" class="white--text" flat>
          <v-btn
            icon
            color="white"
            @click="question_change(show_question ? false : true)"
            v-show="show_answer"
            ><v-icon>mdi-sync</v-icon></v-btn
          >
          <h3 class="mx-auto">{{ show_question ? "問題" : "解答" }}</h3>
        </v-toolbar>
        <v-card-text class="text-center mt-3">
          <h3 v-show="show_question">{{ current_question.question }}</h3>
          <h3 v-show="!show_question">{{ current_question.answer }}</h3>
        </v-card-text>
      </v-card>
      <div class="text-center mt-7" v-if="!show_answer">
        <v-btn color="primary" @click="answer_change"> 答えを見る </v-btn>
      </div>
      <div class="text-center mt-7" v-else>
        <v-btn @click="next(current_question, true)">わかった</v-btn>
        <v-btn @click="next(current_question, false)">わからない</v-btn>
      </div>
    </v-container>
  </div>
</template>

<script>
import Loading from "../components/Loading.vue";
export default {
  components: { Loading },
  data() {
    return {
      number: 0,
      show_question: true,
      show_answer: false,
      questions: [],
      current_question: {},
      loading: true,
    };
  },

  methods: {
    getQuestions() {
      axios.get("/api/answer").then((response) => {
        this.questions = response.data.questions;
        this.current_question = this.questions[this.number];
        this.loading = false;
      });
    },

    question_change(bool) {
      console.log("question_changeが実行されました");
      this.show_question = bool;
    },

    answer_change() {
      this.show_answer = true;
      this.question_change(false);
    },

    next(question, bool) {
      console.log("nextが実行されました");
      console.log(this.current_question)
      this.answer(question, bool);
      console.log(this.questions.length)
      console.log(this.number + 1)
      if (this.questions.length === this.number + 1) {
        return this.$router.push("/");
      }

      this.number += 1;
      this.current_question = this.questions[this.number];
      this.show_answer = false;
      this.show_question = true;
    },

    async answer(question, bool) {
      const response = await axios.put(
        "/api/question/" + question.id + "/answer",
        {
          correct_answer: bool,
        }
      );
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
