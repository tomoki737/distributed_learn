<template>
  <div>
    <loading :loading="loading"></loading>
    <div v-show="!loading">
      <v-container class="mt-10" style="max-width: 850px">
        <h1>プロフィール</h1>
        
      </v-container>
    </div>
</template>

<script lang="ts">
import BottomNavigation from "../components/BottomNavigation.vue";
import FollowButton from "../components/FollowButton.vue";
import axios from "axios";
import Loading from "../components/Loading.vue";
import { Component, Prop, Vue } from "vue-property-decorator";

@Component({
  components: {
    BottomNavigation,
    Loading,
    FollowButton,
  },
})
export default class User extends Vue {

}
