<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-row>
      <v-col cols="6">
        <v-card-text>
          <div class="red text-center">
            <p>新しく覚える</p>
            <h3>0</h3>
          </div>
        </v-card-text>
      </v-col>
      <v-col cols="6">
        <v-card-text>
          <div class="blue text-center">
            <p>復習</p>
            <h3>0</h3>
          </div>
        </v-card-text>
      </v-col>
    </v-row>
    <v-card-actions>
      <div class="mx-auto">
      <v-btn router-link :to="{ name: 'question.answer' }"> 学習する </v-btn>
      </div>
    </v-card-actions>
  </v-card>
</template>
<script>
export default {
  components: {},
};
</script>
