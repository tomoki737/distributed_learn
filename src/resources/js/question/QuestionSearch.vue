<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container style="max-width: 1000px" class="mt-2">
      <div v-show="!loading">
        <h2>検索</h2>
        <question-search-form
          @questionChange="questionChange"
          :is_my_question_search_prop="false"
        ></question-search-form>
        <div v-for="question in displayQuestions" :key="question.id">
          <question-index-card
            :my_question_search="false"
            :prop_question="question"
            @get="getQuestions"
          ></question-index-card>
        </div>
        <div class="text-center" v-if="length">
          <v-pagination
            class="mt-3 mb-13"
            v-model="page"
            :length="length"
            @input="pageChange"
          ></v-pagination>
        </div>
      </div>
      <bottom-navigation></bottom-navigation>
    </v-container>
  </div>
</template>

<script lang="ts">
import Loading from "../components/Loading.vue";
import QuestionIndexCard from "../components/QuestionIndexCard.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
import QuestionSearchForm from "../components/QuestionSearchForm.vue";
import axios from "axios";
import { Component, Vue } from "vue-property-decorator";
@Component({
  components: {
    Loading,
    QuestionIndexCard,
    BottomNavigation,
    QuestionSearchForm,
  },
})
export default class QuestionCreate extends Vue {
  questions: Object[] = [];
  loading: Boolean = true;
  allTagNames: String[] = [];
  page: number = 1;
  length: number = 0;
  displayQuestions: Object = [];
  pageSize: number = 10;

  async getQuestions() {
    const response = await axios.get("/api/question/search");
    this.questions = response.data.questions;
    this.allTagNames = response.data.allTagNames;
    this.loading = false;
    this.createPageNation();
  }

  createPageNation() {
    this.length = Math.ceil(this.questions.length / this.pageSize);
    this.displayQuestions = this.questions.slice(
      this.pageSize * (this.page - 1),
      this.pageSize * this.page
    );
  }

  pageChange(pageNumber: number) {
    this.displayQuestions = this.questions.slice(
      this.pageSize * (pageNumber - 1),
      this.pageSize * pageNumber
    );
    window.scrollTo({
      top: 0,
      behavior: "auto",
    });
  }

  questionChange(questions: Object[]) {
    this.questions = questions;
    this.createPageNation();
    this.pageChange(1);
  }

  mounted() {
    this.getQuestions();
  }
}
</script>

