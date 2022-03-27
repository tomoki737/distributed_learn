<template>
  <div class="mb-12">
    <v-app-bar color="primary" dark app>
      <v-toolbar-title @click="$router.push('/')" style="cursor: pointer"
        >分散学習帳</v-toolbar-title
      >
      <v-spacer></v-spacer>
      <v-btn outlined router-link :to="{ name: 'register' }">新規登録</v-btn>
      <v-btn outlined router-link :to="{ name: 'login' }">ログイン</v-btn>
    </v-app-bar>
  </div>
</template>
