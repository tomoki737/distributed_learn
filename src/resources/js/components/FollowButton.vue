<template>
  <div>
    <v-btn :color="buttonColor" outlined @click="clickFollow">
      {{ buttonText }}
    </v-btn>
  </div>
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";
import axios from "axios";
interface User {
  id: number;
  followers: { id: number }[];
}
@Component
export default class FollowButton extends Vue {
  @Prop({ default: false })
  prop_user: Object;

  user: User = { id: 0, followers: [{ id: 0 }] };
  $store: any;
  IsFollowedBy: Boolean = false;

  clickFollow() {
    if (!this.isLogin) {
      alert("フォロー機能はログイン中のみ使用可能です");
      return;
    }
    this.IsFollowedBy ? this.unfollow() : this.follow();
  }

  async follow() {
    const response = await axios.put("/api/user/" + this.user.id + "/follow");
    this.IsFollowedBy = true;
    this.$emit("parentFollowMethod");
  }

  async unfollow() {
    const response = await axios.delete(
      "/api/user/" + this.user.id + "/unfollow"
    );
    this.IsFollowedBy = false;
    this.$emit("parentUnFollowMethod");
  }

  get buttonColor(): String {
    return this.IsFollowedBy ? "black" : "green";
  }

  get buttonText(): String {
    return this.IsFollowedBy ? "フォロー中" : "フォロー";
  }

  IsFollowedByMethod() {
    this.IsFollowedBy = this.user.followers.filter(
      (user) => user.id === this.getUserId
    ).length
      ? true
      : false;
  }

  @Watch("prop_user")
  changeUser(newValue: User) {
    this.user = newValue;
    this.IsFollowedByMethod();
  }

  get getUserId(): number {
    return this.$store.getters["auth/id"];
  }

  get isLogin(): number {
    return this.$store.getters["auth/check"];
  }
}
</script>
