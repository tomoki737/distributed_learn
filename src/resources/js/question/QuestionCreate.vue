<template>
  <div>
    <v-container style="max-width: 1000px">
      <h2 class="mt-5">問題を作成</h2>
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


<script lang="ts">
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
import axios from "axios";
import { Component, Vue } from "vue-property-decorator";
interface QuestionForm {
  question: String;
  answer: String;
  tags: String;
  share: Boolean;
  category: String;
}
@Component({ components: { QuesitonTagsInput, BottomNavigation } })
export default class QuestionCreate extends Vue {
  questionForm: QuestionForm = {
    question: "",
    answer: "",
    tags: "",
    share: true,
    category: "その他",
  };

  items: String[] = [
    "学問",
    "ビジネス",
    "IT",
    "生活",
    "ヘルスケア",
    "スポーツ",
    "ゲーム",
    "音楽",
    "その他",
  ];

  tagNames: String[] = [];
  errors: Object = {};
  allTagNames: String[] = [];
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
  }

  tagsChange(tags: Object[]) {
    this.questionForm.tags = JSON.stringify(tags);
  }

  async getQuestion() {
    const response = await axios.get("/api/question/create");
    this.allTagNames = response.data.allTagNames;
  }
  mounted() {
    this.getQuestion();
  }
}
</script>
