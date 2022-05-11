<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading" style="max-width: 1000px" class="mt-2">
      <v-row
        ><v-col cols="9">
          <span
            >タグ: {{ searchForm.tag }} / キーワード: {{ searchForm.keyword }} /
            カテゴリー: {{ searchForm.category }}</span
          >
        </v-col>
        <v-col cols="3">
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
                  <v-select
                    :items="items"
                    label="カテゴリー"
                    v-model="searchForm.category"
                  ></v-select>

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

      searchForm: {
        tag: "",
        keyword: "",
        category: "",
      },
    };
  },

  methods: {
    async getQuestions() {
      const response = await axios.get("/api/question");
      this.questions = response.data.questions;
      this.allTagNames = response.data.allTagNames;
      this.loading = false;
    },

    async search() {
      const response = await axios.post(
        "/api/question/search",
        this.searchForm
      );
      this.questions = response.data.questions;
    },
  },

  mounted() {
    this.getQuestions();
  },
};
</script>
