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
        hide-details
      >
        <template v-slot:append>
          <v-btn color="primary" text @click="answerShow">わからない</v-btn>
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
      is_answer: "",
      your_answer: "",
      text_field_color: "black",
      text_color: "black",
      answer_text: "",
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
        return this.$router.push("/");
      }
      this.reset();
      this.number += 1;
      this.current_question = this.questions[this.number];
    },

    async answer() {
        if(this.your_answer === "") {
            return;
        }
      this.is_answer = this.checkAnswer();
      this.text_field_color = this.is_answer ? "green" : "red";
      this.text_color = this.is_answer ? "green--text" : "red--text";
      this.answer_text = this.is_answer ? "正解" : "不正解";

      if (!this.is_answer) {
        this.your_answer = this.current_question.answer;
        setTimeout(() => {
          this.reset();
        }, 2000);
        return;
      }

      this.next();
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: this.is_answer,
        }
      );
    },

    checkAnswer() {
      return this.current_question.answer === this.your_answer ? true : false;
    },

    answerShow() {
      this.your_answer = this.current_question.answer;
      setTimeout(() => {
        this.reset();
      }, 2000);
    },

    reset() {
      this.text_field_color = "black";
      this.your_answer = "";
      this.answer_text = "";
    },
  },
  mounted() {
    this.getQuestions();
  },
};
</script>
