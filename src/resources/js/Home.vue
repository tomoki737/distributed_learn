<template>
  <div>
    <loading :loading="loading" />
    <v-container style="max-width: 1000px">
      <div v-show="!loading">
        <v-card elevation="2" class="mt-10 mx-auto">
          <v-card-text>
            <v-row>
              <v-col cols="6">
                <div class="pink lighten-4 text-center">
                  <p>新しく覚えない</p>
                  <h3>{{ new_questions }}</h3>
                </div>
              </v-col>
              <v-col cols="6">
                <div class="light-blue lighten-4 text-center">
                  <p>復習</p>
                  <h3>{{ review_questions }}</h3>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions
            v-if="new_questions != '0' || review_questions != '0'"
            class="mt-5"
          >
            <div class="mx-auto">
              <v-btn router-link outlined :to="{ name: 'answer.understand' }">
                学習
              </v-btn>
              <v-btn router-link outlined :to="{ name: 'answer.select' }">
                選択肢
              </v-btn>
              <v-btn router-link outlined :to="{ name: 'answer.writing' }">
                筆記
              </v-btn>
            </div>
          </v-card-actions>
          <div v-else class="text-end">
            <v-card-text v-if="next_study_date">
              <p>次回: {{ next_study_date }}</p>
            </v-card-text>
          </div>
        </v-card>
        <div class="text-center mt-4">
          <v-btn @click="getQuestions" text icon class="ma-2">
            <v-icon x-large>mdi-restore</v-icon>
          </v-btn>
        </div>
      </div>
      <bottom-navigation></bottom-navigation>
    </v-container>
  </div>
</template>
<script lang="ts">
import Loading from "./components/Loading.vue";
import BottomNavigation from "./components/BottomNavigation.vue";
import axios from "axios";
import { Component, Vue } from "vue-property-decorator";
@Component({
  components: {
    Loading,
    BottomNavigation,
  },
})
export default class QuestionCreate extends Vue {
  review_questions: String = "0";
  loading: Boolean = true;
  new_questions: String = "0";
  imgPath: String = "";
  next_study_date: String = "";

  getQuestions() {
    axios.get("/api/home").then((response) => {
      this.review_questions = response.data.review_questions.length;
      this.new_questions = response.data.new_questions.length;
      this.next_study_date = response.data.next_study_date;
      this.loading = false;
    });
  }
  mounted() {
    this.getQuestions();
  }
}
</script>
