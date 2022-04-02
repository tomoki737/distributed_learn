<template>
  <v-container class="mt-3">
    <h1>問題の編集</h1>
    <v-card class="mt-10">
      <v-card-text>
        <v-text-field v-model="question.question" label="問題"></v-text-field>
        <span v-if="errors.question">
          {{ errors.question[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-text-field
          v-model="question.answer"
          label="回答"
        ></v-text-field>
        <span v-if="errors.answer">
          {{ errors.answer[0] }}
        </span>
      </v-card-text>
    </v-card>
    <v-row justify="center">
      <v-col cols="12">
        <div class="text-end">
          <v-btn color="primary" dark x-large class="mt-4" @click="edit"
            >作成する</v-btn
          >
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      question: {},
      errors: {},
    };
  },
  props: {
    question_id: { type: String },
  },
  methods: {
    edit() {
      axios
        .put("/api/question/" + this.question_id, this.question)
        .then((response) => {
          this.$router.push("/");
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
        });
    },
    async getQuestion() {
      const response = await axios.get(
        "/api/question/" + this.question_id + "/edit"
      );
      this.question = response.data.question;
    },
  },
  mounted() {
    this.getQuestion();
  },
};
</script>
