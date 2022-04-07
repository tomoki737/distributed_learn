<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container v-show="!loading">
      <div v-for="question in questions" :key="question.id">
        <v-card elevation="2" class="mt-10 mx-auto" width="600px">
          <v-tabs fixed-tabs>
            <v-tab @click="select(1)">質問</v-tab>
            <v-tab @click="select(2)">解答</v-tab>
          </v-tabs>
          <v-card-text>
            <p v-show="isSelect === 1">{{ question.question }}</p>
            <p v-show="isSelect === 2">{{ question.answer }}</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on"
                  ><v-icon>mdi-menu</v-icon></v-btn
                >
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
              </v-list>
            </v-menu>
          </v-card-actions>
        </v-card>
      </div>
    </v-container>
  </div>
</template>
<script>
import Loading from "../components/Loading.vue";
export default {
  components: {
    Loading,
  },

  data() {
    return {
      questions: [],
      loading: true,
      isSelect: 1,
    };
  },

  methods: {
    async questionDelete(question_id) {
      const response = await axios
        .delete("/api/question/" + question_id)
        .catch((error) => {
          return console.error(error);
        });
      this.getQuestions();
    },

    getQuestions() {
      axios.get("/api/question").then((response) => {
        this.loading = false;
        this.questions = response.data.questions;
      });
    },

    select(val) {
      this.isSelect = val;
    },
  },

  mounted() {
    console.log("mounted start");
    this.loading = true;
    this.getQuestions();
  },
};
</script>
