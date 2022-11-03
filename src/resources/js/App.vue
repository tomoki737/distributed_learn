<template>
  <v-app>
    <nav-bar></nav-bar>
    <RouterView />
  </v-app>
</template>
<script lang="ts">
import NavBar from "./components/NavBar.vue";
import { Component, Vue } from "vue-property-decorator";
@Component({
  components: {
    NavBar,
  },
})
export default class App extends Vue {}
</script>
