<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width:1000px">
      <v-card elevation="2" class="mt-10" height="400px">
        <v-toolbar color="blue lighten-3" class="white--text" flat>
          <h3 class="mx-auto">問題</h3>
        </v-toolbar>
        <v-card-text class="text-center mt-3">
          <h3>{{ questions[number].question }}</h3>
        </v-card-text>
      </v-card>
      <answer-select-button
        :select_answers="select_answers"
        :current_question="current_question"
        :is_answer="is_answer"
        :your_answer="your_answer"
        :dialog="dialog"
        @answer="answer"
        @next="next"
      ></answer-select-button>
    </v-container>
  </div>
</template>

<script>
import Loading from "../components/Loading.vue";
import AnswerSelectButton from "../components/AnswerSelectButton.vue";
export default {
  components: { Loading, AnswerSelectButton },
  data() {
    return {
      number: 0,
      questions: [],
      all_questions: [],
      select_answers: [],
      current_question: {},
      is_answer: "",
      your_answer: "",
      tags: [],
      dialog: false,
      loading: true,
    };
  },
  methods: {
    getQuestions() {
      axios.get("/api/answer/select").then((response) => {
        this.questions = response.data.questions;
        if (!this.questions) {
          this.$router.push("/");
        }
        this.all_questions = response.data.all_questions;
        this.createSelectAnswer(this.questions[0]);
        this.loading = false;
      });
    },

    next() {
      if (this.questions.length === this.number + 1) {
        this.dialog = false;
        return this.$router.push("/");
      }

      this.number += 1;
      this.createSelectAnswer(this.questions[this.number]);
      this.dialog = false;
    },

    async answer(answer) {
      this.is_answer = this.checkAnswer(answer);
      this.your_answer = answer;
      if (this.is_answer) {
        setTimeout(() => {
          this.next();
        }, 1000);
      }
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: this.is_answer,
        }
      );
    },

    checkAnswer(answer) {
      return this.current_question.answer === answer ? true : false;
    },

    createSelectAnswer(current_question) {
      this.current_question = current_question;
      const removed_question = this.removeQuestion(
        this.all_questions,
        current_question
      );

      let shuffled_question = this.shuffleQuestion(removed_question);
      let answers = this.createAnswer(shuffled_question);
      let set_answers = new Set(answers);

      let array_answers = Array.from(set_answers);
      let slice_answers = array_answers.slice(0, 3);
      this.addAnswer(slice_answers, current_question.answer);
      this.select_answers = slice_answers;
    },

    shuffleQuestion(questions) {
      questions.map(function () {
        for (let i = questions.length - 1; i >= 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [questions[i], questions[j]] = [questions[j], questions[i]];
        }
      });
      return questions;
    },

    createAnswer(quesitons) {
      let answer = [];
      quesitons.forEach((question) => {
        answer.push(question.answer);
      });
      return answer;
    },

    addAnswer(answers, add_answer) {
      return answers.push(add_answer);
    },

    removeQuestion(allQuestions, exceptQuestion) {
      return allQuestions.filter(
        (question) => !(question.answer == exceptQuestion.answer)
      );
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
