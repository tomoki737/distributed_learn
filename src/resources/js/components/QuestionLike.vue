<template>
  <div>
    <v-btn icon :color="IsLikedBy ? 'pink' : 'grey'" @click="clickLike">
      <v-icon>mdi-heart</v-icon>
      <span>{{ LikesCount }}</span>
    </v-btn>
  </div>
</template>
<script lang="ts">
import axios from "axios";
import { Component, Vue, Prop } from "vue-property-decorator";
interface Question {
  id: number;
  question: String;
  answer: String;
  likes: { id: number }[];
  user: { name: String };
  category: { name: String };
  tags: { name: String }[];
  download_users: { id: number }[];
  created_at: Date;
  correct_answer: String;
  likes_count: number;
}
@Component
export default class QuestionLike extends Vue {
  @Prop({ default: {} })
  question!: Question;

  @Prop({ default: {} })
  user_id!: number;

  @Prop({ default: "" })
  isLogin!: Boolean;

  LikesCount: number = this.question.likes_count;
  IsLikedBy: boolean = this.IsLikedByMethod;

  clickLike() {
    if (!this.isLogin) {
      alert("いいね機能はログイン中のみ使用できます");
      return;
    }
    this.IsLikedBy ? this.unlike() : this.like();
  }
  async like() {
    const response = await axios.put(
      "/api/question/" + this.question.id + "/like"
    );
    this.IsLikedBy = true;
    this.LikesCount = response.data.coutLikes;
  }

  async unlike() {
    const response = await axios.delete(
      "/api/question/" + this.question.id + "/unlike"
    );
    this.IsLikedBy = false;
    this.LikesCount = response.data.coutLikes;
  }

  get IsLikedByMethod() {
    return this.question.likes.filter((user) => user.id === this.user_id).length
      ? true
      : false;
  }
}
</script>
