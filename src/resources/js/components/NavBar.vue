<template>
  <div class="mb-12">
    <v-app-bar color="primary" dark app clipped-left>
      <v-toolbar-title
        class="ml-xs-5 ml-sm-10 ml-md-15 ml-lg-15 ml-xl-15"
        @click="$router.push('/')"
        style="cursor: pointer"
        >分散学習アプリ</v-toolbar-title
      >
      <v-spacer></v-spacer>
      <div class="mr-15 hidden-sm-and-down">
        <v-btn
          icon
          :to="{ name: menu.url }"
          v-for="(menu, index) in isLogin ? nav_menus : guest_user_menus"
          :key="index"
          class="mr-10"
        >
          <v-icon class="mr-2">{{ menu.icon }}</v-icon>
          <span>{{ menu.title }}</span>
        </v-btn>
        <v-btn
          v-if="isLogin"
          icon
          :to="{ name: user_menu.url, params: { id: get_user_id } }"
        >
          <span>{{ user_menu.title }}</span>
          <v-icon>{{ user_menu.icon }}</v-icon>
        </v-btn>
      </div>
    </v-app-bar>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import axios from "axios";
interface Menu {
  title?: String;
  icon?: String;
  url: String;
}
@Component
export default class NavBar extends Vue {
  drawer: Boolean = false;
  $store: any;
  $router: any;

  about_menu: Menu = { title: "ユーザー情報", url: "about" };
  user_menu: Menu = { title: "ユーザー", icon: "mdi-account", url: "about" };
  nav_menus: Menu[] = [
    { title: "一覧", icon: "mdi-book-open", url: "question.index" },
    { title: "検索", icon: "mdi-magnify", url: "question.search" },
    { title: "作成", icon: "mdi-pencil", url: "question.create" },
  ];

  guest_user_menus: Menu[] = [
    { title: "ログイン", url: "login" },
    { title: "新規登録", url: "register" },
  ];

  logout() {
    axios.get("/sanctum/csrf-cookie").then((res) => {
      axios
        .post("/logout")
        .then((res) => {
          this.$store.commit("auth/setUser", null);
          this.$router.push("/");
        })
        .catch((error) => {
          console.error(error);
        });
    });
  }
  changeDrawer() {
    this.drawer = !this.drawer;
  }
  downDrawer() {
    this.drawer = false;
  }

  get isLogin(): Boolean {
    return this.$store.getters["auth/check"];
  }
  get get_user_id(): String {
    return this.$store.getters["auth/id"];
  }
}
</script>
