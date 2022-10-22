<template>
  <v-bottom-navigation app class="hidden-md-and-up">
    <v-btn v-for="menu in menus" :key="menu.title" :to="menu.url">
      <span>{{ menu.title }}</span>
      <v-icon>{{ menu.icon }}</v-icon>
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
  menus: Menu[] = [
    { title: "一覧", icon: "mdi-book-open", url: "/question/index" },
    { title: "検索", icon: "mdi-magnify", url: "/question/search" },
    { title: "ホーム", icon: "mdi-home", url: "/" },
    { title: "作成", icon: "mdi-pencil", url: "/question/create" },
    { title: "ユーザー", icon: "mdi-account", url: "/about" },
  ];
}
</script>
