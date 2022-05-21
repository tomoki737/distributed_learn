<template>
  <div>
    <v-container style="max-width: 1000px">
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
        </v-card-text>
        <v-card-text>
          <v-text-field
            v-model="question.answer"
            label="回答"
            style="font-size: 16px"
          ></v-text-field>
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
      <div class="mb-13">
          <div class="text-end mt-4">
            <v-btn
              color="primary"
              dark
              x-large
              class="hidden-sm-and-down"
              @click="edit"
              >作成する</v-btn
            >
          </div>
           <v-btn
        color="primary"
        class="mb-3 mt-2 hidden-md-and-up"
        dark
        block
        @click="edit"
        >作成する</v-btn
      >
      </div>
    </v-container>
    <bottom-navigation></bottom-navigation>
  </div>
</template>

<script>
import QuesitonTagsInput from "../components/QuestionTagsInput.vue";
import BottomNavigation from "../components/BottomNavigation.vue";
export default {
  components: { QuesitonTagsInput, BottomNavigation },
  data() {
    return {
      question: {
        question: "",
        answer: "",
        tags: "",
        share: true,
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
