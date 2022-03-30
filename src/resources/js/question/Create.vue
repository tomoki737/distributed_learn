<template>
  <v-container class="mt-3">
    <h1>問題の作成</h1>
    <v-card class="mt-10">
      <v-card-text>
        <v-text-field
          v-model="questionForm.question"
          label="問題"
        ></v-text-field>
      </v-card-text>
      <v-card-text>
        <v-text-field v-model="questionForm.answer" label="回答"></v-text-field>
      </v-card-text>
    </v-card>
    <v-row justify="center">
      <v-col cols="12">
        <div class="text-end">
          <v-btn color="primary" dark x-large class="mt-4" @click="store">作成する</v-btn>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      questionForm: {
        question: "",
        answer: "",
      },
      errors: {},
    };
  },
  methods: {
    async store() {
      const response = await axios
        .post("/api/question/store", this.questionForm)
        .catch((error) => {
          this.errors = error.response.data.errors;
        });
        console.log("create")
        this.$router.push("/")
    },
  },
};
</script>
