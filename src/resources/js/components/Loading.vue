<template>
  <v-row class="mt-5">
    <vue-loading
      type="spin"
      color="#333"
      :size="{ width: '50px', height: '50px' }"
      v-show="loading"
    ></vue-loading>
  </v-row>
</template>
<script>
import { VueLoading } from "vue-loading-template";
export default {
  components: {
    VueLoading,
  },
  props: {
    loading: true,
  },
};
</script>
