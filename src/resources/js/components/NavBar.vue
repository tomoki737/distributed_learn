<template>
  <div class="mb-12">
    <v-navigation-drawer app clipped v-model="drawer">
      <div v-if="!isLogin">
        <v-list-item>
          <v-btn block outlined router-link :to="{ name: 'register' }"
            >新規登録</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn block outlined router-link :to="{ name: 'login' }"
            >ログイン
          </v-btn>
        </v-list-item>
      </div>
      <div v-else>
        <v-list-item>
          <v-btn block outlined router-link :to="{ name: 'question.index' }"
            >一覧</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn block outlined router-link :to="{ name: 'question.create' }"
            >作成</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn block outlined dark @click="logout">ログアウト</v-btn>
        </v-list-item>
      </div>
    </v-navigation-drawer>
    <v-app-bar color="primary" dark app clipped-left>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title @click="$router.push('/')" style="cursor: pointer"
        >分散学習帳</v-toolbar-title
      >
      <v-spacer />
      <div class="hidden-md-and-down">
        <div v-if="!isLogin">
          <v-btn outlined router-link :to="{ name: 'register' }"
            >新規登録</v-btn
          >
          <v-btn outlined router-link :to="{ name: 'login' }">ログイン</v-btn>
        </div>
        <div v-else>
          <v-btn outlined router-link :to="{ name: 'question.index' }"
            >一覧</v-btn
          >
          <v-btn outlined router-link :to="{ name: 'question.create' }"
            >作成</v-btn
          >
          <v-btn outlined dark @click="logout">ログアウト</v-btn>
        </div>
      </div>
    </v-app-bar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      drawer: false,
    };
  },
  methods: {
    logout() {
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("/logout")
          .then((res) => {
            this.$store.commit("auth/setUser", null);
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
  },

  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
  },
};
</script>
