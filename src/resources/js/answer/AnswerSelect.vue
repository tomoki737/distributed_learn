<template>
  <div>
    <loading :loading="loading"></loading>
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
      </div>
    </v-container>
  </div>
</template>

<script lang="ts">
import Loading from "../components/Loading.vue";
import AnswerSelectButton from "../components/AnswerSelectButton.vue";
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
  components: { AnswerSelectButton, Loading },
})
export default class AnswerSelect extends Vue {
  question_number: number = 0;
  questions: Question[] = [];
  all_questions: Question[] = [];
  select_answers: String[] = [];
  is_correct_answer: Boolean = false;
  your_select_answer: String = "";
  current_question: Question = {question:"", id:0, answer:"", category:{name:""}, tags:[{name:""}]};
  tags: Object[] = [];
  loading: Boolean = true;
  $router: any;
  $refs: any;

  getQuestions() {
    axios.get("/api/answer/select").then((response) => {
      this.questions = response.data.questions;
      if (!this.questions) {
        this.$router.push("/");
      }
      this.all_questions = response.data.all_questions;
      this.createSelectAnswers(this.questions[0]);
      this.loading = false;
      console.log(this.current_question.question)
    });
  }

  next_question(index: number) {
    if (this.questions.length === this.question_number + 1) {
      return this.$router.push("/");
    }
    this.question_number += 1;
    this.createSelectAnswers(this.questions[this.question_number]);
    this.$refs.child[index].dialogChange();
  }

  async answer(answer: String, index: number) {
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
  }

  isCorrectAnswer(answer: Object): Boolean {
    return this.current_question.answer === answer ? true : false;
  }

  createSelectAnswers(current_question: Question) {
    this.current_question = current_question;
    let exclude_questions = this.excludeQuestion(
      this.all_questions,
      current_question
    );
    let shuffle_questions = this.shuffleArray(exclude_questions);

    let category_priority_questions: Question[] = this.categoryPriority(
      shuffle_questions,
      current_question
    );

    let tag_priority_questions: Question[] = this.tagsPriority(
      category_priority_questions,
      current_question
    );

    let answers = this.createAnswer(tag_priority_questions);
    let exclude_overlapping = Array.from(new Set(answers));

    let slice_answers = exclude_overlapping.slice(0, 3);
    this.addCurrentAnswer(slice_answers, current_question.answer);
    this.select_answers = this.shuffleArray(slice_answers);
  }

  shuffleArray(array: any) {
    array.map(function () {
      for (let i = array.length - 1; i >= 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
    });
    return array;
  }

  categoryPriority(questions: Question[], current_question: Question) {
    let category_priority_questions: Question[] = [];
    questions.forEach((question: Question) => {
      if (question.category.name === current_question.category.name) {
        category_priority_questions.unshift(question);
      } else {
        category_priority_questions.push(question);
      }
    });
    return category_priority_questions;
  }

  tagsPriority(questions: Question[], current_question: Question) {
    let tag_priority_questions: Question[] = [];
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
  }

  createAnswer(quesitons: Question[]): String[] {
    let answers: String[] = [];
    quesitons.forEach((question: Question) => {
      answers.push(question.answer);
    });
    return answers;
  }

  addCurrentAnswer(answers: String[], add_answer: String) {
    return answers.push(add_answer);
  }

  excludeQuestion(all_questions: Question[], exclude_question: Question) {
    return all_questions.filter(
      (question) => !(question.answer == exclude_question.answer)
    );
  }

  mounted() {
    this.getQuestions();
  }
}
</script>
