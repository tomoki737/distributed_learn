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
      <div v-for="(select_answer, index) in select_answers" :key="index">
        <answer-select-button
          :index="index"
          ref="child"
          :select_answer="select_answer"
          :current_question="current_question"
          :is_correct_answer="is_correct_answer"
          :your_select_answer="your_select_answer"
          @answer="answer"
          @next_question="next_question"
        ></answer-select-button>
      </div>
    </v-container>
  </div>
</template>

<script>
import AnswerSelectButton from "../components/AnswerSelectButton.vue";
export default {
  components: { AnswerSelectButton },
  data() {
    return {
      question_number: 0,
      questions: [],
      all_questions: [],
      select_answers: [],
      current_question: {},
      is_correct_answer: "",
      your_select_answer: "",
      tags: [],
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
        this.createSelectAnswers(this.questions[0]);
        this.loading = false;
      });
    },

    next_question(index) {
      if (this.questions.length === this.question_number + 1) {
        return this.$router.push("/");
      }
      this.question_number += 1;
      this.createSelectAnswers(this.questions[this.question_number]);
      this.$refs.child[index].dialogChange();
    },

    async answer(answer, index) {
      this.is_correct_answer = this.isCorrectAnswer(answer);
      this.your_select_answer = answer;

      if (this.is_correct_answer) {
        setTimeout(() => {
          this.next_question(index);
        }, 1000);
      }
      const response = await axios.put(
        "/api/question/" + this.current_question.id + "/answer",
        {
          correct_answer: this.is_correct_answer,
        }
      );
    },

    isCorrectAnswer(answer) {
      return this.current_question.answer === answer ? true : false;
    },

    createSelectAnswers(current_question) {
      this.current_question = current_question;
      let exclude_questions = this.excludeQuestion(
        this.all_questions,
        current_question
      );
      let shuffle_questions = this.shuffleQuestions(exclude_questions);

      let category_priority_questions = this.categoryPriority(
        shuffle_questions,
        current_question
      );

      let tag_priority_questions = this.tagsPriority(
        category_priority_questions,
        current_question
      );

      let answers = this.createAnswer(tag_priority_questions);
      let exclude_overlapping = Array.from(new Set(answers));

      let slice_answers = exclude_overlapping.slice(0, 3);
      this.addCurrentAnswer(slice_answers, current_question.answer);
      this.select_answers = this.shuffleQuestions(slice_answers);
    },

    shuffleQuestions(questions) {
      questions.map(function () {
        for (let i = questions.length - 1; i >= 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [questions[i], questions[j]] = [questions[j], questions[i]];
        }
      });
      return questions;
    },

    categoryPriority(questions, current_question) {
      let category_priority_questions = [];
      questions.forEach((question) => {
        if (question.category.name === current_question.category.name) {
          category_priority_questions.unshift(question);
        } else {
          category_priority_questions.push(question);
        }
      });
      return category_priority_questions;
    },

    tagsPriority(questions, current_question) {
      let tag_priority_questions = [];
      const current_question_tag_names = current_question.tags.map(
        (tag) => tag.name
      );
      questions.forEach((select_question) => {
        const question_tags = select_question.tags.filter((tag) =>
          current_question_tag_names.includes(tag.name)
        );
        if (question_tags.length !== 0) {
          tag_priority_questions.unshift(select_question);
          return;
        }
        tag_priority_questions.push(select_question);
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

    addCurrentAnswer(answers, add_answer) {
      return answers.push(add_answer);
    },

    excludeQuestion(all_questions, exclude_question) {
      return all_questions.filter(
        (question) => !(question.answer == exclude_question.answer)
      );
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
