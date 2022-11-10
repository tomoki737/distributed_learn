<template>
  <v-bottom-navigation app class="hidden-md-and-up">
    <v-btn v-for="menu in menus" :key="menu.title" :to="{ name: menu.url }">
      <span>{{ menu.title }}</span>
      <v-icon>{{ menu.icon }}</v-icon>
    </v-btn>
    <v-btn :to="{ name: user_menu.url, params: { id: get_user_id } }">
      <span>{{ user_menu.title }}</span>
      <v-icon>{{ user_menu.icon }}</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>
<script lang="ts">
interface Menu {
  title: String;
  icon: String;
  url: String;
}

import { Component, Vue } from "vue-property-decorator";
@Component
export default class BottomNavigation extends Vue {
  drawer: boolean = false;
  $store: any;
  menus: Menu[] = [
    { title: "一覧", icon: "mdi-book-open", url: "question.index" },
    { title: "検索", icon: "mdi-magnify", url: "question.search" },
    { title: "ホーム", icon: "mdi-home", url: "home" },
    { title: "作成", icon: "mdi-pencil", url: "question.create" },
  ];

  user_menu: Menu = { title: "ユーザー", icon: "mdi-account", url: "user" };

  get get_user_id(): number {
    return this.$store.getters["auth/id"];
  }
}
</script>
