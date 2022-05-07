<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width: 1000px" class="mt-2">
      <v-row>
        <v-col>
          <p>タグ: {{ searchedTag }} / キーワード: {{ searchedKeyword }}</p>
        </v-col>
        <v-col>
          <div class="text-end">
            <v-dialog v-model="dialog" width="500">
              <template v-slot:activator="{ on, attrs }">
                <v-btn color="blue" dark v-bind="attrs" v-on="on" outlined>
                  <v-icon>mdi-magnify</v-icon>
                </v-btn>
              </template>

              <v-card>
                <v-card-title class="text-h5 blue lighten-3">
                  検索条件
                </v-card-title>
                <v-divider></v-divider>

                <v-card-text>
                  <v-text-field
                    label="タグ"
                    v-model="searchForm.tag"
                  ></v-text-field>
                  <v-text-field
                    label="キーワード"
                    v-model="searchForm.keyword"
                  ></v-text-field>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>

                  <v-btn color="primary" text @click="dialog = false">
                    キャンセル
                  </v-btn>
                  <v-btn
                    color="primary"
                    text
                    @click="
                      search();
                      dialog = false;
                    "
                  >
                    検索
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </div>
        </v-col>
      </v-row>
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
export default {
  components: {
    Loading,
    QuestionIndexCard,
  },

  data() {
    return {
      questions: [],
      loading: true,
      dialog: false,
      allTagNames: [],

      searchForm: {
        tag: "",
        keyword: "",
      },

      searchedTag: "全て",
      searchedKeyword: "全て",
    };
  },

  methods: {
    async getQuestions() {
      const response = await axios.get("/api/question");
      this.questions = response.data.questions;
      console.log(this.questions);
      this.allTagNames = response.data.allTagNames;
      this.loading = false;
    },

    async search() {
      const response = await axios.post(
        "/api/question/search",
        this.searchForm
      );
      this.questions = response.data.questions;
      this.searchedTag = response.data.tag;
      this.searchedKeyword = response.data.keyword;
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
