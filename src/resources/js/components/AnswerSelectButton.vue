<template>
  <div class="mt-2">
    <v-dialog v-model="dialog" width="500" persistent no-click-animation>
      <template v-slot:activator="{ on, attrs }">
          <v-btn
            plain
            block
            outlined
            x-large
            @click="answer"
            v-bind="attrs"
            v-on="on"
            style="text-transform: none; display: block; white-space: normal"
          >
            {{ select_answer }}
          </v-btn>
      </template>

      <v-card>
        <v-card-title class="text-h5" :class="showAnswerColor">
          {{ showCheckAnswer }}
        </v-card-title>

        <v-card-text class="mt-2">
          <h3>{{ current_question.question }}</h3>
        </v-card-text>
        <v-card-text>
          <p style="margin: 0; padding: 0">正解:</p>
          <h3 style="margin: 0; padding: 0">
            {{ current_question.answer }}
          </h3></v-card-text
        >
        <div v-if="!is_answer">
          <v-card-text>
            <p style="margin: 0; padding: 0">あなたの回答:</p>
            <h3 style="margin: 0; padding: 0">
              {{ your_answer }}
            </h3></v-card-text
          >
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" text @click="$emit('next', index)">次へ</v-btn>
          </v-card-actions>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    select_answer: {},
    current_question: {},
    your_answer: {},
    is_answer: "",
    index: "",
  },
  data() {
    return {
      dialog: false,
    };
  },

  computed: {
    showCheckAnswer() {
      return this.is_answer ? "正解" : "不正解";
    },

    showAnswerColor() {
      return this.is_answer
        ? "color: green lighten-1"
        : "color: deep-orange lighten-3";
    },
  },
  methods: {
    dialogChange() {
      this.dialog = false;
    },
    answer() {
      this.$emit("answer", this.select_answer, this.index);
    },
  },
};
</script>

