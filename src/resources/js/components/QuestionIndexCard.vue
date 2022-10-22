<template>
  <v-card elevation="2" class="mt-5 mx-auto">
    <v-tabs fixed-tabs>
      <v-tab @click="select(1)">質問</v-tab>
      <v-tab @click="select(2)">解答</v-tab>
    </v-tabs>
    <v-card-text>
      <p v-show="isSelect === 1">{{ question.question }}</p>
      <p v-show="isSelect === 2">{{ question.answer }}</p>
    </v-card-text>
    <v-card-actions>
      <v-chip
        close-icon="mdi-close-outline"
        label
        color="light-blue lighten-3"
        class="mr-1"
        >{{ question.category.name }}</v-chip
      >
      <div v-for="(tag, index) in question.tags" :key="index">
        <v-chip
          router-link
          label
          :to="{ name: 'tags.show', params: { name: tag.name } }"
          class="mr-1"
          v-if="tag.name"
          >{{ tag.name }}</v-chip
        >
      </div>
    </v-card-actions>
    <v-divider></v-divider>
    <v-card-actions v-if="my_question_search">
      <v-icon>mdi-book-open</v-icon>
      <span class="ml-2">{{ question.answer_times }} 回</span>
      <v-icon
        class="ml-5"
        :class="question.correct_answer ? 'green--text' : 'red--text'"
        >{{ correct_answer_icon }}</v-icon
      >
      <span class="ml-1">{{ correct_answer }}</span>
      <v-dialog v-model="dialog">
        <template v-slot:activator="{ on, attrs }">
          <v-btn text v-bind="attrs" v-on="on" class="ml-4">
            {{ created_at }}
          </v-btn>
        </template>
        <v-card>
          <v-card-text>
            <p>次回学習日: {{ question.next_study_date }}</p>
            <p>作成日: {{ created_at }}</p>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="dialog = false"> OK </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-spacer></v-spacer>
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on"><v-icon>mdi-menu</v-icon></v-btn>
        </template>
        <v-list>
          <v-list-item
            router-link
            :to="{
              name: 'question.edit',
              params: { question_id: question.id },
            }"
          >
            <v-list-item-title>編集</v-list-item-title>
          </v-list-item>
          <v-list-item @click="questionDelete(question.id)">
            <v-list-item-title>削除</v-list-item-title>
          </v-list-item>
          <v-list-item @click="questionExcept(question.id)">
            <v-list-item-title>対象外にする</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-card-actions>
    <v-card-actions v-else>
      <v-icon>mdi-account</v-icon>
      <span v-if="question" class="ml-2">{{ question.user.name }}</span>
      <span class="ml-4">{{ created_at }}</span>
      <v-spacer></v-spacer>
      <v-btn
        v-show="get_user_id !== question.user_id"
        class="mx-2"
        fab
        dark
        small
        :class="is_downloaded ? 'grey' : 'primary'"
        @click="clickDownload"
        ><v-icon>mdi-cloud-download-outline</v-icon></v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import axios from "axios";
import moment from "moment";
interface Question {
  id: number;
  question: String;
  answer: String;
  user:{name:String}
  category: { name: String };
  tags: { name: String }[];
  download_users: { id: number }[];
  created_at: Date;
  correct_answer: String;
}

@Component
export default class QuestionIndexCard extends Vue {
  @Prop({ default: "" })
  question!: Question;
  @Prop({ default: "" })
  my_question_search!: String;
  $store: any;
  isSelect: number = 1;
  correct_answer: String = "";
  correct_answer_icon: String = "";
  created_at: String = "";
  dialog: Boolean = false;
  is_downloaded: Boolean = false;

  async questionDelete(question_id: number) {
    const response = await axios
      .delete("/api/question/" + question_id)
      .catch((error) => {
        return console.error(error);
      });
    this.$emit("get");
  }

  correctAnswer() {
    const correct_answer = this.question.correct_answer;
    if (correct_answer === null) {
      return (this.correct_answer = "未解答");
    }
    this.correct_answer = correct_answer ? "正解" : "不正解";
  }

  correctAnswerIcon() {
    const correct_answer = this.question.correct_answer;
    if (this.question.correct_answer === null) {
      return;
    }
    this.correct_answer_icon = correct_answer
      ? "mdi-checkbox-blank-circle-outline"
      : "mdi-window-close";
  }

  formatCreatedAt() {
    this.created_at = moment(this.question.created_at).format("YYYY/MM/DD");
  }

  async questionExcept(question_id: number) {
    const response = await axios
      .put("/api/question/" + question_id + "/except")
      .catch((error) => {
        return console.error(error);
      });
  }

  clickDownload() {
    if (this.is_downloaded === false) {
      this.downloadQuestion();
    } else {
      alert("すでにダウンロードされています");
    }
  }

  async downloadQuestion() {
    const response = await axios
      .post("/api/question/download", { question_id: this.question.id })
      .catch((error) => {
        return console.error(error);
      });
    this.is_downloaded = true;
  }

  select(val: number) {
    this.isSelect = val;
  }

  is_user_downloaded() {
    this.question.download_users.map((obj) => {
      if (obj.id === this.get_user_id) {
        this.is_downloaded = true;
      }
    });
  }

  get get_user_id() {
    return this.$store.getters["auth/id"];
  }

  mounted() {
    this.correctAnswer();
    this.correctAnswerIcon();
    this.formatCreatedAt();
    this.is_user_downloaded();
  }
}
</script>
