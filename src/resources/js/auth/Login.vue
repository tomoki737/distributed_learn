<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-form>
      <v-card-title>
        <h2>ログイン</h2>
      </v-card-title>
      <v-card-text>
        <v-text-field
          v-model="LoginForm.email"
          label="メールアドレス"
          required
          type="email"
          prepend-icon="mdi-account-circle"
        ></v-text-field>

        <v-text-field
          v-model="LoginForm.password"
          label="パスワード"
          required
          :type="showPassword ? 'test' : 'password'"
          prepend-icon="mdi-lock"
          @click:append="showPassword = !showPassword"
          :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        ></v-text-field>
        <span v-if="errors.password">
          {{ errors.password[0] }}
        </span>
        <v-card-actions>
          <v-btn class="info" dark @click="login">ログイン</v-btn>
        </v-card-actions>
      </v-card-text>
    </v-form>
  </v-card>
</template>
<script>
export default {
  data() {
    return {
      LoginForm: {
        email: "",
        password: "",
      },
      errors: [],
      showPassword: false,
    };
  },
  methods: {
    login() {
      axios
        .get("/sanctum/csrf-cookie")
        .then((res) => {
          axios
            .post("login", this.LoginForm)
            .then((res) => {
              if (res.status == 200) {
                this.$store.commit("auth/setUser", res.data);
                this.$router.push("/about");
              }

              this.getUserMessage = "ログインに失敗しました。";
            })
            .catch((error) => {
              this.errors = error.response.errors;
            });
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
