<template>
  <div>
    <loading :loading="loading"></loading>
    <div v-show="!loading">
      <v-container class="mt-10 max-width: 1000px">
        <h2>プロフィール</h2>
        <v-card class="mt-5 mx-auto" max-width="1000">
          <v-card-text>
            <v-row align="center" justify="space-between" class="pa-3">
              <span class="mb-5 text-h4">{{ user.name }}さん</span>
              <follow-button
                :prop_user="user"
                ></follow-button
              >
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
            <span class="body-1 mt-4"> フォロー: 0 フォロワー: 0 </span>
          </v-card-text>
          <v-card-actions class="ml-0">
            <v-list-item>
              <v-row align="center" justify="end">
                <v-btn
                  v-if="this.get_user_id === this.user.id"
                  class="mr-1"
                  outlined
                  color="success"
                  d-block
                  @click="logout"
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
  $store: any;
  $router: any;

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
