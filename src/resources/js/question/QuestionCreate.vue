<template>
  <v-container class="mt-3" style="max-width: 1000px">
    <h1>問題の作成</h1>
    <v-card class="mt-3">
      <v-card-text>
        <quesiton-tags-input
          @tagsJson="tagsChange"
          :initialTags="tagNames"
          :autocompleteItems="allTagNames"
          :placeholder="'タグを5個まで追加できます'"
        ></quesiton-tags-input>
        <span v-if="errors.tags">
          {{ errors.tags[0] }}
        </span>
        <v-select
          :items="items"
          label="カテゴリー"
          v-model="questionForm.category"
        ></v-select>
        <span v-if="errors.category">
          {{ errors.category[0] }}
        </span>
        <v-textarea
          rows="2"
          v-model="questionForm.question"
          label="問題"
        ></v-textarea>
        <span v-if="errors.question">
          {{ errors.question[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-textarea
          rows="2"
          v-model="questionForm.answer"
          label="解答"
        ></v-textarea>
        <span v-if="errors.answer">
          {{ errors.answer[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-switch
          v-model="questionForm.share"
          label="Share"
          class="d-flex justify-content-end"
        ></v-switch>
      </v-card-text>
    </v-card>
    <div class="text-end">
      <v-btn color="primary" dark x-large class="mt-4" @click="store"
        >作成する</v-btn
      >
    </div>
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
        share: false,
        category: "",
      },

      items: [
        "学問",
        "ビジネス",
        "生活",
        "ヘルスケア",
        "スポーツ",
        "ゲーム",
        "音楽",
        "IT",
        "その他",
      ],

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
