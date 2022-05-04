<template>
  <v-container class="mt-3">
    <h1>問題の作成</h1>
    <v-card class="mt-10">
      <v-card-text>
        <quesiton-tags-input
          @tagsJson="tagsChange"
          :initialTags="tagNames"
          :autocompleteItems="allTagNames"
        ></quesiton-tags-input>
        <span v-if="errors.tags">
          {{ errors.tags[0] }}
        </span>
        <v-textarea
          v-model="questionForm.question"
          label="問題"
        ></v-textarea>
        <span v-if="errors.question">
          {{ errors.question[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-textarea v-model="questionForm.answer" label="解答"></v-textarea>
        <span v-if="errors.answer">
          {{ errors.answer[0] }}
        </span>
      </v-card-text>
    </v-card>
    <v-row justify="center">
      <v-col cols="12">
        <div class="text-end">
          <v-btn color="primary" dark x-large class="mt-4" @click="store"
            >作成する</v-btn
          >
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
export default {
  components: { QuesitonTagsInput },
  data() {
    return {
      questionForm: {
        question: "",
        answer: "",
        tags: "",
      },

      tagNames: [],
      errors: {},
      allTagNames: [],
    };
  },
  methods: {
    store() {
      axios
        .post("/api/question", this.questionForm)
        .then((response) => {
          this.questionForm.question = "";
          this.questionForm.answer = "";
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
        });
    },
    
    tagsChange(tags) {
      this.questionForm.tags = JSON.stringify(tags);
    },

    async getQuestion() {
      const response = await axios.get("/api/question/create");
      this.allTagNames = response.data.allTagNames;
    },
  },
  mounted() {
    this.getQuestion();
  },
};
</script>
