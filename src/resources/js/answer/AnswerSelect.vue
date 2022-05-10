<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width: 1000px">
      <v-card elevation="2" class="mt-3" min-height="250px">
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
      let removed_questions = this.removeQuestion(
        this.all_questions,
        current_question
      );

      let shuffled_questions = this.shuffleQuestion(removed_questions);

      let category_priority_questions = this.categoryPush(
        shuffled_questions,
        current_question
      );

      let tag_priority_questions = this.tagsPriority(
        category_priority_questions,
        current_question
      );

      let answers = this.createAnswer(tag_priority_questions);
      let set_answers = new Set(answers);

      let array_answers = Array.from(set_answers);
      let slice_answers = array_answers.slice(0, 3);
      this.addAnswer(slice_answers, current_question.answer);
      this.select_answers = this.shuffleQuestion(slice_answers);
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

    categoryPush(questions, current_question) {
      let category_questions = [];
      questions.forEach((question) => {
        if (question.category.name === current_question.category.name) {
          category_questions.unshift(question);
        } else {
          category_questions.push(question);
        }
      });
      return category_questions;
    },

    tagsPriority(questions, current_question) {
      let tag_priority_questions = [];
      const current_question_tag_names = current_question.tags.map((tag) => {
        return tag.name;
      });
      questions.forEach((question) => {
        const question_tag = question.tags.filter(tag => {
            return current_question_tag_names.includes(tag.name);
        })
          if (question_tag.length !== 0) {
            tag_priority_questions.unshift(question);
            return;
          }
          tag_priority_questions.push(question);
        });
      return tag_priority_questions;
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
