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
      <div class="text-center mt-7">
        <div v-for="(select_question, index) in select_questions" :key="index">
          <v-btn
            @click="answer(select_question)"
            style="text-transform: none"
            >{{ select_question.answer }}</v-btn
          >
        </div>
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
      questions: [],
      all_questions: [],
      select_questions: [],
      current_question: {},
      tags: [],
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
        return this.$router.push("/");
      }

      this.number += 1;
      this.createSelectQuestion(this.questions[this.number]);
    },

    async answer(question) {
      const check_answer = this.checkAnswer(question.answer);
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: check_answer,
        }
      );
      this.next();
    },

    checkAnswer(answer) {
      if (this.current_question.answer === answer) {
        return true;
      } else {
        return false;
      }
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
