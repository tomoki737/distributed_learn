<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container style="max-width: 1000px">
      <div v-show="!loading">
        <v-card elevation="2" class="mt-3 mb-7" min-height="250px">
          <v-toolbar color="blue lighten-3" class="white--text" flat>
            <v-btn
              icon
              color="white"
              @click="question_change(show_question ? false : true)"
              v-show="show_answer_button"
              ><v-icon>mdi-sync</v-icon></v-btn
            >
            <h3 class="mx-auto">{{ show_question ? "解答" : "問題" }}</h3>
          </v-toolbar>
          <v-card-text class="text-center mt-3">
            <h3 v-show="!show_question">{{ current_question.question }}</h3>
            <h3 v-show="show_question">{{ current_question.answer }}</h3>
          </v-card-text>
        </v-card>
        <div class="text-center mt-7" v-if="!show_answer_button">
          <v-btn color="primary" class="px-15" @click="answer_change"> 答えを見る </v-btn>
        </div>
        <div class="text-center mt-7" v-else>
          <v-btn class="px-15 mr-4 white--text" color="red" @click="next(current_question, true)"> わかった </v-btn>
          <v-btn class="px-15" color="primary" @click="next(current_question, false)"> わからない </v-btn>
        </div>
      </div>
    </v-container>
  </div>
</template>

<script lang="ts">
import Loading from "../components/Loading.vue";
import axios from "axios";
import { Component, Vue } from "vue-property-decorator";

interface Question {
  id: number;
  question: String;
  answer: String;
  category: { name: String };
  tags: { name: String }[];
}

@Component({
  components: { Loading },
})
export default class AnswerUnderstand extends Vue {
  number: number = 0;
  show_question: Boolean = false;
  show_answer_button: Boolean = false;
  questions: Question[];
  current_question: Question = {
    question: "",
    id: 0,
    answer: "",
    category: { name: "" },
    tags: [{ name: "" }],
  };
  loading: Boolean = true;
  $router: any;

  getQuestions() {
    axios.get("/api/answer").then((response) => {
      this.questions = response.data.questions;
      this.current_question = this.questions[this.number];
      this.loading = false;
    });
  }

  question_change(bool: Boolean) {
    this.show_question = bool;
  }

  answer_change() {
    this.show_answer_button = true;
    this.question_change(true);
  }

  async next(question: Question, bool: Boolean) {
    await this.answer(question, bool);
    if (this.questions.length === this.number + 1) {
      return this.$router.push("/");
    }
    console.log(this.number);
    this.number += 1;
    this.current_question = this.questions[this.number];
    this.show_answer_button = false;
    this.show_question = true;
  }

  async answer(question: Question, bool: Boolean) {
    const response = await axios.put(
      "/api/question/" + question.id + "/answer",
      {
        correct_answer: bool,
      }
    );
  }

  mounted() {
    this.getQuestions();
  }
}
</script>
