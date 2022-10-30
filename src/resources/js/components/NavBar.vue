<template>
  <div class="mb-12">
    <v-navigation-drawer
      app
      clipped
      v-model="drawer"
      class="hidden-sm-and-down"
    >
      <v-list-item>
        <v-btn block outlined @click="changeDrawer">閉じる</v-btn>
      </v-list-item>
      <div v-if="!isLogin">
        <v-list-item v-for="menu in guest_user_menus" :key="menu.title">
          <v-btn
            block
            outlined
            router-link
            :to="menu.url"
            @click="downDrawer"
            >{{ menu.title }}</v-btn
          >
        </v-list-item>
      </div>
      <div v-if="isLogin">
        <v-list-item v-for="menu in login_user_menus" :key="menu.title">
          <v-btn
            block
            outlined
            router-link
            :to="menu.url"
            @click="downDrawer"
            >{{ menu.title }}</v-btn
          >
        </v-list-item>
      </div>
    </v-navigation-drawer>
    <v-app-bar color="primary" dark app clipped-left>
      <v-app-bar-nav-icon
        @click="changeDrawer"
        class="hidden-sm-and-down"
      ></v-app-bar-nav-icon>
      <v-toolbar-title @click="$router.push('/')" style="cursor: pointer"
        >分散学習アプリ</v-toolbar-title
      >
    </v-app-bar>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import axios from "axios";
interface Menu {
  title: String;
  url: String;
}
@Component
export default class NavBar extends Vue {
  drawer: Boolean = false;
  $store: any;
  $router: any;

  login_user_menus: Menu[] = [
    { title: "一覧", url: "/question/index" },
    { title: "検索", url: "/question/search" },
    { title: "ホーム", url: "/" },
    { title: "作成", url: "/question/create" },
    { title: "ユーザー情報", url: "/about" },
  ];

  guest_user_menus: Menu[] = [
    { title: "ログイン", url: "/login" },
    { title: "新規登録", url: "/register" },
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
}
</script>
