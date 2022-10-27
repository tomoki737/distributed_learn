<template>
  <v-row justify="space-between">
    <v-col cols="9">
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
          <v-card-title class="text-h5 blue lighten-3"> 検索条件 </v-card-title>
          <v-divider></v-divider>

          <v-card-text>
            <v-select
              :items="items"
              label="カテゴリー"
              v-model="searchForm.category"
              hide-details
            ></v-select>

            <v-text-field
              hide-details
              label="タグ"
              v-model="searchForm.tag"
            ></v-text-field>

            <v-text-field
              hide-details
              label="キーワード"
              v-model="searchForm.keyword"
            ></v-text-field>
            <v-checkbox
              v-if="is_my_question_search_prop"
              v-model="searchForm.learning"
              label="学習中"
              hide-details
            ></v-checkbox>
            <v-divider class="mt-2"></v-divider>
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
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";
import axios from "axios";
interface SearchForm {
  tag: String;
  keyword: String;
  category: String;
  learning: Boolean;
  is_my_question_search: Boolean;
}
@Component
export default class QuestionSearchForm extends Vue {
  @Prop({ default: false })
  is_my_question_search_prop!: Boolean;

  dialog: Boolean = false;
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
  searchForm: SearchForm = {
    tag: "",
    keyword: "",
    category: "",
    learning: true,
    is_my_question_search: this.is_my_question_search_prop,
  };

  async search() {
    const response = await axios.post("/api/question/search", this.searchForm);
    const questions = response.data.questions;
    this.$emit("questionChange", questions);
  }
  get get_is_my_question_search(): Boolean {
    console.log(this.is_my_question_search_prop);
    return this.is_my_question_search_prop;
  }
}
</script>

