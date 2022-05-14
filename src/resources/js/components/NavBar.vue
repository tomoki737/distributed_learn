<template>
  <div class="mb-12">
    <v-navigation-drawer app clipped v-model="drawer" class="hidden-md-and-down">
      <v-list-item>
        <v-btn block outlined @click="drawerClose">閉じる</v-btn>
      </v-list-item>
      <div v-if="!isLogin">
        <v-list-item>
          <v-btn
            block
            @click="drawerClose()"
            outlined
            router-link
            :to="{ name: 'register' }"
            >新規登録</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn
            block
            @click="drawerClose()"
            outlined
            router-link
            :to="{ name: 'login' }"
            >ログイン
          </v-btn>
        </v-list-item>
      </div>
      <div v-else>
        <v-list-item>
          <v-btn
            block
            @click="
              drawerClose();
              $router.push('/');
            "
            style="cursor: pointer"
            outlined
            >ホーム</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn
            block
            @click="drawerClose(); $router.push('/question/index');"
            outlined
            >一覧</v-btn
          >
        </v-list-item>
        <v-list-item>
          <v-btn
            block
            @click="drawerClose(); $router.push('/question/create');"
            outlined
            router-link
            >作成
          </v-btn>
        </v-list-item>
        <v-list-item>
          <v-btn
            block
            outlined
            @click="
              logout();
              drawerClose();
            "
            >ログアウト
          </v-btn>
        </v-list-item>
      </div>
    </v-navigation-drawer>
    <v-app-bar color="primary" dark app clipped-left>
      <v-app-bar-nav-icon @click="drawerClose" class="hidden-md-and-down"></v-app-bar-nav-icon>
      <v-toolbar-title @click="$router.push('/')" style="cursor: pointer"
        >分散学習帳</v-toolbar-title
      >
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
            this.$router.push("/")
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
    drawerClose() {
        this.drawer = !this.drawer;
    }
  },

  computed: {
    isLogin() {
      return this.$store.getters["auth/check"];
    },
  },
};
</script>
