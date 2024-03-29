<template>
  <div>
    <loading :loading="loading"></loading>
    <div v-show="!loading">
      <v-container class="mt-10" style="max-width: 850px">
        <h1>プロフィール</h1>
        <v-card class="mt-5 mx-auto">
          <v-card-text>
            <v-row align="center" justify="space-between" class="pa-3">
              <div>
                <v-icon size="50">mdi-account-circle</v-icon>
                <span class="mb-5 text-body-1">{{ user.name }}さん</span>
              </div>
              <follow-button
                v-if="user.id !== get_user_id"
                :prop_user="user"
                @parentUnFollowMethod="unfollow"
                @parentFollowMethod="follow"
              ></follow-button>
            </v-row>
            <v-divider></v-divider>
            <p class="body-1 mt-4">
              ダウンロードした問題数: {{ download_questions_count }}
            </p>
            <p class="body-1 mt-4">
              問題がダウンロードされた回数: {{ downloaded_questions_count }}
            </p>
            <p class="body-1 mt-4">
              問題がいいねされた回数: {{ downloaded_questions_count }}
            </p>
            <span class="body-1 mt-4">
              <div style="cursor: pointer">
                <span @click="$router.push('/user/' + user.id + '/followers')"
                  >フォロワー: {{ followers_count }}</span
                >
                <span @click="$router.push('/user/' + user.id + '/followings')"
                  >フォロー: {{ followings_count }}</span
                >
              </div>
            </span>
          </v-card-text>
          <v-card-actions class="ml-0" v-if="this.get_user_id === this.user.id">
            <v-list-item>
              <v-row align="center" justify="end">
                <v-btn
                  class="mr-1"
                  outlined
                  color="success"
                  d-block
                  @click="logout"
                  @parentUnFollowMethod="unfollow"
                  @parentFollowMethod="follow"
                  >ログアウト</v-btn
                >
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
        <bottom-navigation></bottom-navigation>
      </v-container>
    </div>
  </div>
</template>

<script lang="ts">
import BottomNavigation from "../components/BottomNavigation.vue";
import FollowButton from "../components/FollowButton.vue";
import axios from "axios";
import Loading from "../components/Loading.vue";
import { Component, Prop, Vue } from "vue-property-decorator";

@Component({
  components: {
    BottomNavigation,
    Loading,
    FollowButton,
  },
})
export default class About extends Vue {
  @Prop({ default: "" })
  id: String;
  user: { followers: { id: number }[] } = { followers: [{ id: 0 }] };
  loading: Boolean = true;
  download_questions_count: number = 0;
  downloaded_questions_count: number = 0;
  likes_questions_count: number = 0;
  followings_count: number = 0;
  followers_count: number = 0;
  $store: any;
  $router: any;

  follow() {
    this.followers_count += 1;
  }

  unfollow() {
    this.followers_count -= 1;
  }
  logout() {
    axios.get("/sanctum/csrf-cookie").then((res) => {
      axios
        .post("/logout")
        .then((res) => {
          this.$store.commit("auth/setUser", null);
          this.$router.push("/login");
        })
        .catch((error) => {
          console.error(error);
        });
    });
  }

  async getUserProfile() {
    const response = await axios.get("/api/user/" + this.id);
    this.user = response.data.user;
    this.download_questions_count = response.data.download_questions_count;
    this.downloaded_questions_count = response.data.downloaded_questions_count;
    this.likes_questions_count = response.data.likes_questions_count;
    this.followings_count = response.data.user.followings_count;
    this.followers_count = response.data.user.followers_count;
    this.loading = false;
  }

  mounted() {
    this.getUserProfile();
  }

  get get_user_id(): number {
    return this.$store.getters["auth/id"];
  }
}
</script>
