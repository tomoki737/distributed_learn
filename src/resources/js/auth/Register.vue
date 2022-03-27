<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-form>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.name"
              label="名前"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.email"
              label="メールアドレス"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.password"
              label="パスワード"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.password_confirmation"
              label="パスワード"
              required
            ></v-text-field>
          </v-col>
          <v-card-actions>
            <v-btn class="info" dark @click="register">登録</v-btn>
          </v-card-actions>
        </v-row>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      registerForm: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
    };
  },
  methods: {
    async register() {
        axios.get('/sanctum/csrf-cookie').then(response => {
            
        })
    },
  },
};
</script>

