<template>
  <div>
    <v-container style="max-width: 1000px">
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
            v-model="questionForm.question"
            label="問題"
            style="font-size: 16px"
          ></v-textarea>
          <span v-if="errors.question">
            {{ errors.question[0] }}
          </span>
          <v-text-field
            v-model="questionForm.answer"
            label="解答"
            style="font-size: 16px"
          ></v-text-field>
          <span v-if="errors.answer">
            {{ errors.answer[0] }}
          </span>
          <v-switch
            v-model="questionForm.share"
            label="Share"
            class="d-flex justify-content-end"
          ></v-switch>
          <uplad-file @parentMethod="addUploadFile"></uplad-file>
        </v-card-text>
      </v-card>
      <div class="mb-13">
        <v-btn
          color="primary"
          dark
          block
          class="hidden-md-and-up mt-2"
          @click="store"
          >作成する</v-btn
        >
        <div class="text-end mt-4 hidden-sm-and-down">
          <v-btn color="primary" dark x-large @click="store">作成する</v-btn>
        </div>
      </div>
    </v-container>
    <bottom-navigation></bottom-navigation>
  </div>
</template>

<script>
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
import UpladFile from "../components/UploadFile.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
export default {
  components: { QuesitonTagsInput, BottomNavigation, UpladFile },
  data() {
    return {
      questionForm: {
        question: "",
        answer: "",
        tags: "",
        share: true,
        category: "",
        file: {},
      },

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

    addUploadFile(file) {
      this.questionForm.file = file;
      console.log(this.questionForm.file);
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
