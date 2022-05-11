<template>
  <v-container class="mt-3" style="max-width: 1000px">
    <h1>問題の編集</h1>
    <v-card class="mt-10">
      <v-card-text>
        <quesiton-tags-input
          :initialTags="tagNames"
          @tagsJson="tagsChange"
          :autocompleteItems="allTagNames"
          :placeholder="'タグを5個まで追加できます'"
        ></quesiton-tags-input>
        <span v-if="errors.tags">
          {{ errors.tags[0] }}
        </span>
        <v-select
          :items="items"
          label="カテゴリー"
          v-model="question.category"
        ></v-select>
        <span v-if="errors.category">
          {{ errors.category[0] }}
        </span>
        <v-textarea v-model="question.question" label="問題"></v-textarea>
        <span v-if="errors.question">
          {{ errors.question[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-text-field
         v-model="question.answer" label="回答"></v-text-field>
        <span v-if="errors.answer">
          {{ errors.answer[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-switch
          v-model="question.share"
          label="Share"
          class="d-flex justify-content-end"
        ></v-switch>
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
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
export default {
  components: { QuesitonTagsInput },
  data() {
    return {
      question: {
        question: "",
        answer: "",
        tags: "",
        share: false,
        category: "",
      },

      errors: {},
      tagNames: [],
      allTagNames: [],
      items: [
        "学問",
        "ビジネス",
        "IT",
        "生活",
        "ヘルスケア",
        "スポーツ",
        "ゲーム",
        "音楽",
        "その他",
      ],
    };
  },

  props: {
    question_id: {},
  },

  methods: {
    edit() {
      axios
        .put("/api/question/" + this.question_id, this.question)
        .then((response) => {
          this.$router.push("/question/index");
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
      this.tagNames = response.data.tagNames;
      this.question.category = response.data.question.category.name;
      this.allTagNames = response.data.allTagNames;
    },

    tagsChange(tags) {
      this.question.tags = JSON.stringify(tags);
    },
  },
  mounted() {
    this.getQuestion();
  },
};
</script>
