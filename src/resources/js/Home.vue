<template>
  <div>
    <loading :loading="loading"></loading>
    <v-card elevation="2" class="mt-10 mx-auto" width="600px" v-show="!loading">
      <v-row>
        <v-col cols="6">
          <v-card-text>
            <div class="red text-center">
              <p>新しく覚える</p>
              <h3>0</h3>
            </div>
          </v-card-text>
        </v-col>
        <v-col cols="6">
          <v-card-text>
            <div class="blue text-center">
              <p>復習</p>
              <h3>{{ review }}</h3>
            </div>
          </v-card-text>
        </v-col>
      </v-row>
      <v-card-actions>
        <div class="mx-auto">
          <v-btn router-link :to="{ name: 'question.answer' }">
            学習する
          </v-btn>
        </div>
      </v-card-actions>
    </v-card>
  </div>
</template>
<script>
import Loading from "./components/Loading.vue";
export default {
  components: { Loading },
  data() {
    return {
      review: "0",
      loading: true,
    };
  },
  methods: {
    getQuestions() {
      axios.get("/api/answer").then((response) => {
        this.review = response.data.questions.length;
        this.loading = false;
      });
    },
  },
  mounted() {
    this.getQuestions();
  },
};
</script>
