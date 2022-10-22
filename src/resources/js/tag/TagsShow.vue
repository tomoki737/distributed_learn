<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width: 1000px">
      <div class="mb-13">
        <v-card class="mt-7 mx-auto">
          <v-card-title>
            <h2 class="ml-6">{{ name }}</h2>
            <span class="ml-2">({{ questions_length }})</span>
          </v-card-title>
        </v-card>
        <div v-for="question in questions" :key="question.id">
          <question-index-card
            :question="question"
            @get="getQuestions"
          ></question-index-card>
        </div>
      </div>
    </v-container>
    <bottom-navigation></bottom-navigation>
  </div>
</template>
<script lang="ts">
import Loading from "../components/Loading.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
import QuestionIndexCard from "../components/QuestionIndexCard.vue";
import axios from "axios";
import { Component, Prop, Vue } from "vue-property-decorator";
@Component({
  components: {
    Loading,
    QuestionIndexCard,
    BottomNavigation,
  },
})
export default class QuestionCreate extends Vue {
  @Prop({ default: "" })
  name !: String;

  questions: Object[] = [];
  questions_length: number = 0;
  loading: Boolean = true;

  getQuestions() {
    axios.get("/api/tags/" + this.name).then((response) => {
      this.questions = response.data.tag.questions;
      console.log(this.questions);
      this.questions_length = response.data.tag.questions.length;
      this.loading = false;
    });
  }

  mounted() {
    this.getQuestions();
  }
}
</script>
