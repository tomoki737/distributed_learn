<template>
  <div>
    <v-card elevation="2" class="mt-10">
      <v-card-text>
        {{ user.name }}
        {{ user.email }}
        <v-btn outlined @click="logout">ログアウト</v-btn>
      </v-card-text>
    </v-card>
    <bottom-navigation></bottom-navigation>
  </div>
</template>

<script>
import BottomNavigation from '../components/BottomNavigation.vue';
export default {
  components: { BottomNavigation },
  data() {
    return {
      user: [],
    };
  },
  mounted() {
    axios.get("/api/me").then((response) => {
      this.user = response.data;
    });
  },
  methods: {
    logout() {
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("logout")
          .then((res) => {
            this.$store.commit("auth/setUser", null);
            this.$router.push("/");
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
  },
};
</script>
