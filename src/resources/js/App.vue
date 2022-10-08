<template>
  <v-app>
    <nav-bar></nav-bar>
    <RouterView />
  </v-app>
</template>
<script>
import NavBar from "./components/NavBar.vue";
export default {
  components: { NavBar},
};
</script>
