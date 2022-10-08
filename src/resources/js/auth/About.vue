<template>
  <div>
    <v-container class="mt-10 max-width: 1000px">
        <p>ユーザー名: {{ get_user_name }}</p>
        <p>メールアドレス: {{ get_user_email }}</p>
        <v-btn outlined color="success" d-block @click="logout" class="mt-5">ログアウト</v-btn>
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
  computed: {
    get_user_name() {
      return this.$store.getters["auth/name"];
    },
    get_user_email() {
      return this.$store.getters["auth/email"];
    },
  },
};
</script>
