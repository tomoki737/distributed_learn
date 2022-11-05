<template>
  <div>
    <loading :loading="loading"></loading>
    <div v-show="!loading">
      <v-container class="mt-10 max-width: 800px">
        <h1>フォロワー</h1>
        <div v-for="follower in followers" :key="follower.id">
          <v-card class="mt-5 mx-auto">
            <v-card-text>
              <v-row align="center" justify="space-between" class="pa-3">
                <div>
                  <div
                    @click="$router.push('/about/' + follower.id)"
                    style="cursor: pointer"
                  >
                    <v-icon size="50">mdi-account-circle</v-icon>
                    <span class="mb-5 ml-2">{{ follower.name }}さん</span>
                  </div>
                </div>
              </v-row>
            </v-card-text>
          </v-card>
        </div>
        <bottom-navigation></bottom-navigation>
      </v-container>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import BottomNavigation from "../components/BottomNavigation.vue";
import FollowButton from "../components/FollowButton.vue";
import axios from "axios";
import Loading from "../components/Loading.vue";
@Component({ components: { BottomNavigation, FollowButton, Loading } })
export default class Followers extends Vue {
  @Prop({ default: "" })
  id: String;
  loading: Boolean = true;
  $store: any;
  user: { followers: { id: number }[] } = { followers: [{ id: 0 }] };
  followers: { id: number }[] = [{ id: 0 }];

  async getUserProfile() {
    const response = await axios.get("/api/user/" + this.id + "/followers");
    this.user = response.data.user;
    this.followers = response.data.followers;
    console.log(this.followers);
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


