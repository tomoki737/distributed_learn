<template>
  <v-container>
    <v-card elevation="2" class="mt-10 mx-auto" width="600px" height="500px">
      <v-row>
        <v-card-text class="text-center">
          <h3>あああ</h3>
        </v-card-text>
      </v-row>
    </v-card>
    <div class="text-center mt-7">
      <v-btn color="primary"> 答えを見る </v-btn>
    </div>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      questions: [],
    };
  },
  methods: {
    async questionDelete(question_id) {
      const response = await axios
        .delete("/api/question/" + question_id)
        .catch((error) => {
          return console.error(error);
        });
      this.getQuestions();
    },
    getQuestions() {
      axios.get("/api/question").then((response) => {
        this.questions = response.data.questions;
      });
    },
  },
  mounted() {
    this.getQuestions();
  },
};
</script>
