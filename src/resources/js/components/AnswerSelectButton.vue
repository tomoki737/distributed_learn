<template>
  <div class="text-center mt-7">
    <v-dialog v-model="dialog" width="500" persistent no-click-animation>
      <template v-slot:activator="{ on, attrs }">
        <div v-for="(select_question, index) in select_questions" :key="index">
          <v-btn
            @click="answer(select_question)"
            style="text-transform: none"
            v-bind="attrs"
            v-on="on"
            >{{ select_question.answer }}
          </v-btn>
        </div>
      </template>

      <v-card>
        <v-card-title class="text-h5" :class="showAnswerColor">
          {{ showCheckAnswer }}
        </v-card-title>

        <v-card-text class="mt-2">
          <h3>> {{ current_question.question }}</h3>
        </v-card-text>
        <v-card-text>
          <p style="margin: 0; padding: 0">正解:</p>
          <h3 style="margin: 0; padding: 0">
            {{ current_question.answer }}
          </h3></v-card-text
        >
        <div v-if="!check_answer">
          <v-card-text>
            <p style="margin: 0; padding: 0">あなたの回答:</p>
            <h3 style="margin: 0; padding: 0">
              {{ your_answer }}
            </h3></v-card-text
          >
          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="$emit('next')">次へ</v-btn>
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    select_questions: {},
    current_question: {},
    your_answer: {},
    check_answer: "",
    dialog: false,
  },
  data() {
    return {
      dialog: this.dialog,
    };
  },
  computed: {
    showCheckAnswer() {
      return this.check_answer ? "正解" : "不正解";
    },
    showAnswerColor() {
      return this.check_answer
        ? "color: green lighten-1"
        : "color: deep-orange lighten-3";
    },
  },
  methods: {
    answer(select_question) {
        console.log(select_question)
      this.$emit("answer", select_question);
    },
  },
};
</script>
