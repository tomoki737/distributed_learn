<template>
  <v-bottom-navigation app class="hidden-md-and-up">
    <v-btn v-for="menu in menus" :key="menu.title" :to="menu.url">
      <span>{{ menu.title }}</span>
      <v-icon>{{ menu.icon }}</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>
<script>
export default {
  data() {
    return {
      drawer: false,
      menus: [
        { title: "ホーム", icon: "mdi-home", url: "/" },
        { title: "一覧", icon: "mdi-book-open", url: "/question/index" },
        { title: "作成", icon: "mdi-pencil", url: "/question/create" },
      ],
    };
  },
};
</script>
