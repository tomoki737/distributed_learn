<template>
  <div>
    <v-btn :color="buttonColor" outlined>
      {{ buttonText }}
    </v-btn>
  </div>
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";
import axios from "axios";
interface User {
  followers: { id: number }[];
}
@Component
export default class FollowButton extends Vue {
  @Prop({ default: false })
  prop_user: Object;

  user: User = { followers: [{ id: 0 }] };
  initialIsFollowedBy: Boolean;
  $store: any;

  get buttonColor(): String {
    return this.IsFollowedBy ? "indigo" : "white";
  }

  get buttonText(): String {
    return this.IsFollowedBy ? "フォロー中" : "フォロー";
  }

  get IsFollowedBy() {
    return this.user.followers.filter((user) => user.id === this.get_user_id)
      .length
      ? true
      : false;
  }

  @Watch("prop_user")
  changeUser(newValue: User) {
    console.log(newValue);
    this.user = newValue;
  }

  get get_user_id(): number {
    return this.$store.getters["auth/id"];
  }
}
</script>
