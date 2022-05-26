<template>
  <div>
    <v-container style="max-width: 1000px">
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
        v-model="your_answer"
        :color="text_field_color"
        @keyup.enter="answer"
        :disabled="disabled"
        hide-details
      >
        <template v-slot:append>
          <v-btn
            color="primary"
            text
            @click="answerShow"
            v-if="!answer_show_button"
            >わからない</v-btn
          >
          <v-btn
            color="primary"
            text
            v-if="your_correct_button"
            @click="you_correct"
            >私の回答が正しい</v-btn
          >
        </template>
      </v-text-field>
      <p :class="text_color">{{ answer_text }}</p>
    </v-container>
  </div>
</template>
<script>
export default {
  data() {
    return {
      questions: {},
      number: 0,
      current_question: {},
      your_answer: "",
      text_field_color: "black",
      text_color: "black",
      answer_text: "",
      answer_show_again: false,
      disabled: false,
      answer_show_button: false,
      your_correct_button: false,
      storeAnswer: false,
      answer_again: false,
    };
  },

  methods: {
    async getQuestions() {
      const response = await axios.get("/api/answer");
      this.questions = response.data.questions;
      this.current_question = this.questions[0];
    },

    next() {
      if (this.questions.length === this.number + 1) {
        this.$router.push("/");
      }
      this.reset();
      this.answer_show_again = false;
      this.answer_show_button = false;
      this.number += 1;
      this.current_question = this.questions[this.number];
    },

    async answer() {
      if (this.your_answer === "") {
        return;
      }
      const is_answer = this.checkAnswer();
      this.text_field_color = is_answer ? "green" : "red";
      this.text_color = is_answer ? "green--text" : "red--text";
      this.answer_text = is_answer ? "正解" : "不正解";
      this.answer_show_button = true;

      if (!this.answer_again) {
        this.storeAnswer = this.checkAnswer();
      }

      if (is_answer) {
        this.answerStore();
        setTimeout(() => {
          this.next();
        }, 1000);
      } else {
        this.your_answer = this.current_question.answer;
        this.your_correct_button = true;
        setTimeout(() => {
          this.reset();
        }, 2000);
      }

      if (!is_answer) {
        this.answer_again = true;
      }
    },
    async answerStore() {
      console.log("store");
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: this.storeAnswer,
        }
      );
    },

    checkAnswer() {
      return this.current_question.answer === this.your_answer ? true : false;
    },

    you_correct() {
      this.storeAnswer = true;
      this.answer_again = false;
      this.answer();
    },

    answerShow() {
      this.answer_show_button = true;
      this.your_answer = this.current_question.answer;
      this.answer_again = true;
      this.storeAnswer = false;
      this.answer_show_again = true;
      this.disabled = true;
      setTimeout(() => {
        this.reset();
        this.disabled = false;
        this.answer_show_button = false;
      }, 2000);
    },

    reset() {
      this.text_field_color = "black";
      this.your_answer = "";
      this.answer_text = "";
      this.answer_show_button = false;
      this.your_correct_button = false;
    },
  },
  mounted() {
    this.getQuestions();
  },
};
</script>
