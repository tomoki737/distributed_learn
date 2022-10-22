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
<script lang="ts">
import { VueLoading } from "vue-loading-template";
import { Component, Prop, Vue } from "vue-property-decorator";
@Component({ components: { VueLoading } })
export default class Loading extends Vue {
  @Prop({ default: true })
  loading!: Boolean;
}
</script>
