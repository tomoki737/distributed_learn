<template>
  <div>
    <loading :loading="loading" />
    <v-container style="max-width: 1000px">
      <div v-show="!loading">
        <v-card elevation="2" class="mt-3 mb-7" min-height="250px">
          <v-toolbar color="blue lighten-3" class="white--text" flat>
            <h3 class="mx-auto">問題</h3>
          </v-toolbar>
          <v-card-text class="text-center mt-3">
            <h3>{{ current_question.question }}</h3>
          </v-card-text>
        </v-card>
        <v-text-field
          label="答えを入力してください"
          v-model="your_writing_answer"
          :color="text_field_color"
          @keyup.enter="answer"
          :text_fileld_disabled="text_fileld_disabled"
          hide-details
        >
          <template v-slot:append>
            <v-btn
              color="primary"
              text
              @click="answerShow"
              v-if="!answer_show_button_show"
              >わからない</v-btn
            >
            <v-btn
              color="primary"
              text
              v-if="your_correct_button_show"
              @click="you_correct"
              >私の回答が正しい</v-btn
            >
          </template>
        </v-text-field>
        <p :class="label_color">{{ label_text }}</p>
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
export default class AnswerWriting extends Vue {
  questions: Question[];
  number: number = 0;
  current_question: Question = {
    question: "",
    id: 0,
    answer: "",
    category: { name: "" },
    tags: [{ name: "" }],
  };
  your_writing_answer: String = "";
  text_field_color: String = "black";
  text_fileld_disabled: Boolean = false;
  label_color: String = "black";
  label_text: String = "";
  answer_again: Boolean = false;
  answer_show_again: Boolean = false;
  answer_show_button_show: Boolean = false;
  your_correct_button_show: Boolean = false;
  storeAnswer: Boolean = false;
  $router: any;
  loading = true;

  async getQuestions() {
    const response = await axios.get("/api/answer");
    this.questions = response.data.questions;
    this.current_question = this.questions[0];
    this.loading = false;
  }

  next() {
    if (this.questions.length === this.number + 1) {
      this.$router.push("/");
    }
    this.reset();
    this.answer_show_again = false;
    this.answer_show_button_show = false;
    this.number += 1;
    this.current_question = this.questions[this.number];
  }

  async answer() {
    if (this.your_writing_answer === "") {
      return;
    }
    const is_correct_answer = this.isCorrectAnswer();
    this.text_field_color = is_correct_answer ? "green" : "red";
    this.label_color = is_correct_answer ? "green--text" : "red--text";
    this.label_text = is_correct_answer ? "正解" : "不正解";
    this.answer_show_button_show = true;

    if (!this.answer_again) {
      this.storeAnswer = this.isCorrectAnswer();
    }

    if (is_correct_answer) {
      this.answerStore();
      setTimeout(() => {
        this.next();
      }, 1000);
    } else {
      this.your_writing_answer = this.current_question.answer;
      this.your_correct_button_show = true;
      setTimeout(() => {
        this.reset();
      }, 2000);
    }

    if (!is_correct_answer) {
      this.answer_again = true;
    }
  }
  async answerStore() {
    const response = await axios.put(
      "/api/question/" + this.current_question.id + "/answer",
      {
        correct_answer: this.storeAnswer,
      }
    );
  }

  isCorrectAnswer(): Boolean {
    return this.current_question.answer === this.your_writing_answer
      ? true
      : false;
  }

  you_correct() {
    this.storeAnswer = true;
    this.answer_again = false;
    this.answer();
  }

  answerShow() {
    this.answer_show_button_show = true;
    this.your_writing_answer = this.current_question.answer;
    this.answer_again = true;
    this.storeAnswer = false;
    this.answer_show_again = true;
    this.text_fileld_disabled = true;
    setTimeout(() => {
      this.reset();
      this.text_fileld_disabled = false;
      this.answer_show_button_show = false;
    }, 2000);
  }

  reset() {
    this.text_field_color = "black";
    this.your_writing_answer = "";
    this.label_text = "";
    this.answer_show_button_show = false;
    this.your_correct_button_show = false;
  }
  mounted() {
    this.getQuestions();
  }
}
</script>
