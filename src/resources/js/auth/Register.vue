<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-form>
      <v-card-title>
        <h2>登録</h2>
      </v-card-title>
      <v-card-text>
        <v-text-field
          v-model="registerForm.name"
          label="名前"
          required
          prepend-icon="mdi-account-circle"
        ></v-text-field>
        <span v-if="errors.name">
          {{ errors.name[0] }}
        </span>
        <v-text-field
          v-model="registerForm.email"
          label="メールアドレス"
          required
          type="email"
          prepend-icon="mdi-email"
        ></v-text-field>
        <span v-if="errors.email">
          {{ errors.email[0] }}
        </span>
        <v-text-field
          v-model="registerForm.password"
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
          <v-btn class="info" dark @click="register">登録</v-btn>
        </v-card-actions>
      </v-card-text>
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
      },
      errors: [],
      showPassword: false,
    };
  },
  methods: {
    async register() {
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("/register", this.registerForm)
          .then((res) => {
            this.$store.commit("auth/setUser", res.data.user);
            this.$router.push("/about");
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      });
    },
  },
};
</script>

