<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width:1000px" class="mt-2">
     <template>
  <div class="text-end">
    <v-dialog
      v-model="dialog"
      width="500"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="blue"
          dark
          v-bind="attrs"
          v-on="on"
          outlined
        >
          <v-icon>mdi-magnify</v-icon>
        </v-btn>
      </template>

      <v-card>
        <v-card-title class="text-h5 blue lighten-3">
          検索条件
        </v-card-title>
        <v-divider></v-divider>

      <v-card-text>
        <v-text-field label="キーワード"></v-text-field>
        <question-tags-input :placeholder="'タグ'" :autocompleteItems="allTagNames"></question-tags-input>
      </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn
            color="primary"
            text
            @click="dialog = false"
          >
            キャンセル
          </v-btn>
          <v-btn
            color="primary"
            text
            @click="dialog = false"
          >
            検索
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
      <div v-for="question in questions" :key="question.id">
        <question-index-card
          :question="question"
          @get="getQuestions"
        ></question-index-card>
      </div>
    </v-container>
  </div>
</template>

<script>
import Loading from "../components/Loading.vue";
import QuestionIndexCard from "../components/QuestionIndexCard.vue";
import QuestionTagsInput from "../components/QuestionTagsInput.vue"
export default {
  components: {
    Loading,
    QuestionIndexCard,
    QuestionTagsInput,
  },

  data() {
    return {
      questions: [],
      loading: true,
      dialog:false,
      allTagNames: [],
      searchForm: {
          tag:[],
          keyword: "",
      }
    };
  },

  methods: {
    getQuestions() {
      axios.get("/api/question").then((response) => {
        this.questions = response.data.questions;
        this.allTagNames = response.data.allTagNames;
        this.loading = false;
      });
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
