<template>
  <div>
    <v-container style="max-width: 1000px">
      <h2 class="mt-5">問題を編集</h2>
      <v-card class="mt-3">
        <v-card-text>
          <quesiton-tags-input
            :initialTags="tagNames"
            @tagsJson="tagsChange"
            :autocompleteItems="allTagNames"
            :placeholder="'タグを5個まで追加できます'"
            style="font-size: 16px"
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
          <v-textarea
            v-model="question.question"
            label="問題"
            style="font-size: 16px"
          ></v-textarea>
          <span v-if="errors.question">
            {{ errors.question[0] }}
          </span>
          <v-text-field
            v-model="question.answer"
            label="回答"
            style="font-size: 16px"
          ></v-text-field>
          <span v-if="errors.answer">
            {{ errors.answer[0] }}
          </span>
          <v-switch
            v-model="question.share"
            label="Share"
            class="d-flex justify-content-end"
          ></v-switch>
        </v-card-text>
      </v-card>
      <div class="mb-13">
        <div class="text-end mt-4">
          <v-btn
            color="primary"
            dark
            x-large
            class="hidden-sm-and-down"
            @click="edit"
            >編集する</v-btn
          >
        </div>
        <v-btn
          color="primary"
          class="mb-3 mt-2 hidden-md-and-up"
          dark
          block
          @click="edit"
          >編集する</v-btn
        >
      </div>
    </v-container>
    <bottom-navigation></bottom-navigation>
  </div>
</template>

<script lang="ts">
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
import axios from "axios";
import { Component, Prop, Vue } from "vue-property-decorator";
interface Question {
  question: String;
  answer: String;
  tags: String;
  share: Boolean;
  category: String;
}
@Component({ components: { QuesitonTagsInput, BottomNavigation } })
export default class QuestionCreate extends Vue {
  @Prop({ default: "" })
  question_id: String;
  $router: any;
  question: Question = {
    question: "",
    answer: "",
    tags: "",
    share: true,
    category: "",
  };

  errors: Object = {};
  tagNames: String[] = [];
  allTagNames: String[] = [];
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

  edit() {
    axios
      .put("/api/question/" + this.question_id, this.question)
      .then((response) => {
        this.$router.push("/question/index");
      })
      .catch((error) => {
        this.errors = error.response.data.errors;
      });
  }

  async getQuestion() {
    const response = await axios.get(
      "/api/question/" + this.question_id + "/edit"
    );

    this.question = response.data.question;
    this.tagNames = response.data.tagNames;
    this.question.category = response.data.question.category.name;
    this.allTagNames = response.data.allTagNames;
  }

  tagsChange(tags: Object[]) {
    this.question.tags = JSON.stringify(tags);
  }
  mounted() {
    this.getQuestion();
  }
}
</script>
