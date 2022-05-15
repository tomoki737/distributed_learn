<template>
  <div>
    <loading :loading="loading"></loading>
    <v-container>
      <div v-show="!loading">
        <p>ユーザー名: {{ user.name }}</p>
        <p>メールアドレス: {{ user.email }}</p>
        <v-btn outlined @click="logout">ログアウト</v-btn>
      </div>
      <bottom-navigation></bottom-navigation>
    </v-container>
  </div>
</template>

<script>
import BottomNavigation from "../components/BottomNavigation.vue";
import Loading from "../components/Loading.vue";
export default {
  components: { BottomNavigation, Loading },
  data() {
    return {
      user: [],
      loading: true,
    };
  },
  mounted() {
    axios.get("/api/me").then((response) => {
      this.user = response.data;
      this.loading = false;
    });
  },

  methods: {
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
    },
  },
};
</script>
