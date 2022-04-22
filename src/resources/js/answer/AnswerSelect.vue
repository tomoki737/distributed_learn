<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading">
      <v-card elevation="2" class="mt-10 mx-auto" width="600px" height="500px">
        <v-toolbar color="blue lighten-3" class="white--text" flat>
          <h3 class="mx-auto">問題</h3>
        </v-toolbar>
        <v-card-text class="text-center mt-3">
          <h3>{{ questions[number].question }}</h3>
        </v-card-text>
      </v-card>
      <answer-select-button
        :select_questions="select_questions"
        :current_question="current_question"
        :check_answer="check_answer"
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
      select_questions: [],
      current_question: {},
      check_answer: "",
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
        this.all_questions = response.data.all_questions;
        this.createSelectQuestion(this.questions[0]);
        this.loading = false;
      });
    },

    next() {
      if (this.questions.length === this.number + 1) {
        this.dialog = false;
        return this.$router.push("/");
      }
      this.number += 1;
      this.createSelectQuestion(this.questions[this.number]);
      this.dialog = false;
    },

    async answer(question) {
      this.check_answer = this.checkAnswer(question.answer);
      this.your_answer = question.answer;
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: this.check_answer,
        }
      );
      if (this.check_answer) {
        setTimeout(() => {
          this.next();
        }, 500);
      }
    },

    checkAnswer(answer) {
      return this.current_question.answer === answer ? true : false;
    },

    createSelectQuestion(current_question) {
      this.current_question = current_question;
      const removed_question = this.removeQuestion(
        this.all_questions,
        current_question
      );
      const shuffle_question = this.shuffle(removed_question);
      const slice_questions = shuffle_question.slice(0, 3);
      this.addQuestion(slice_questions, current_question);
      this.select_questions = this.shuffle(slice_questions);
    },

    shuffle(questions) {
      questions.map(function () {
        for (let i = questions.length - 1; i >= 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [questions[i], questions[j]] = [questions[j], questions[i]];
        }
      });
      return questions;
    },

    addQuestion(questions, add_question) {
      return questions.push(add_question);
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
