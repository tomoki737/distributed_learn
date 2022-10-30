<template>
  <div>
    <loading :loading="loading"></loading>
    <div v-show="!loading">
      <v-container class="mt-10 max-width: 1000px">
        <h2>プロフィール</h2>
        <v-card class="mt-5 mx-auto" max-width="1000">
          <v-card-text>
            <h2 class="mb-5">{{ get_user_name }}さん</h2>
            <v-divider></v-divider>
            <p class="body-1 mt-4">
              ダウンロードした問題数: {{ download_questions_count }}
            </p>
            <p class="body-1 mt-4">
              問題がダウンロードされた回数: {{ downloaded_questions_count }}
            </p>
          </v-card-text>
          <v-card-actions class="ml-0">
            <v-list-item>
              <v-row align="center" justify="end">
                <v-btn
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
import axios from "axios";
import Loading from "../components/Loading.vue";
import { Component, Vue } from "vue-property-decorator";

@Component({
  components: { BottomNavigation, Loading },
})
export default class About extends Vue {
  user: Object;
  loading: Boolean = true;
  download_questions_count: number = 0;
  downloaded_questions_count: number = 0;
  $store: any;
  $router: any;
  logout() {
    axios.get("/sanctum/csrf-cookie").then((res) => {
      axios
        .post("logout")
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
    const response = await axios.get("/api/user");
    this.download_questions_count = response.data.download_questions_count;
    this.downloaded_questions_count = response.data.downloaded_questions_count;
    this.loading = false;
  }
  get get_user_name(): String {
    return this.$store.getters["auth/name"];
  }

  mounted() {
    this.getUserProfile();
  }
}
</script>
