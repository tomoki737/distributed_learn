<template>
  <div>
    <loading :loading="loading" />
    <v-container style="max-width: 1000px">
      <div v-show="!loading">
        <v-card elevation="2" class="mt-10 mx-auto">
          <v-row>
            <v-col cols="6">
              <v-card-text>
                <div class="pink lighten-4 text-center">
                  <p>新しく覚える</p>
                  <h3>{{ new_questions }}</h3>
                </div>
              </v-card-text>
            </v-col>
            <v-col cols="6">
              <v-card-text>
                <div class="light-blue lighten-4 text-center">
                  <p>復習</p>
                  <h3>{{ review_questions }}</h3>
                </div>
              </v-card-text>
            </v-col>
          </v-row>
          <v-card-actions>
            <div class="mx-auto">
              <v-btn
                router-link
                :to="{ name: 'answer.understand' }"
                v-show="new_questions != '0' || review_questions != '0'"
              >
                学習する
              </v-btn>
              <v-btn
                router-link
                :to="{ name: 'answer.select' }"
                v-show="new_questions != '0' || review_questions != '0'"
              >
                選択肢で学習する
              </v-btn>
            </div>
          </v-card-actions>
        </v-card>
      </div>
      <bottom-navigation></bottom-navigation>
    </v-container>
  </div>
</template>
<script>
import Loading from "./components/Loading.vue";
import BottomNavigation from "./components/BottomNavigation.vue";
export default {
  components: { Loading, BottomNavigation, BottomNavigation },
  data() {
    return {
      review_questions: "0",
      loading: true,
      new_questions: "0",
    };
  },
  methods: {
    getQuestions() {
      axios.get("/api/home").then((response) => {
        this.review_questions = response.data.review_questions.length;
        this.new_questions = response.data.new_questions.length;
        this.loading = false;
      });
    },
  },
  mounted() {
    this.getQuestions();
  },
};
</script>
