<template>
  <v-container class="mt-3">
    <h1>問題の作成</h1>
    <v-card class="mt-10">
      <v-card-text>
        <v-text-field v-model="question" label="問題"></v-text-field>
        <span v-if="errors.question">
          {{ errors.question[0] }}
        </span>
      </v-card-text>
      <v-card-text>
        <v-text-field v-model="answer" label="回答"></v-text-field>
        <span v-if="errors.answer">
          {{ errors.answer[0] }}
        </span>
      </v-card-text>
    </v-card>
    <v-row justify="center">
      <v-col cols="12">
        <div class="text-end">
          <v-btn color="primary" dark x-large class="mt-4" @click="store"
            >作成する</v-btn
          >
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
@Component
export default class QuestionForm extends Vue {
  @Prop({ default: "" })
  question!: String;

  @Prop({ default: "" })
  answer!: String;

  @Prop({ default: [] })
  errors!: String[];
}
</script>
