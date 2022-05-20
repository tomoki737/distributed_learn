<template>
  <v-container style="max-width: 1000px">
    <v-card elevation="2" class="mt-10">
      <v-form>
        <v-card-title>
          <h2>ログイン</h2>
        </v-card-title>
        <v-card-text>
          <span>
            {{ getUserMessage }}
          </span>
          <v-text-field
            v-model="LoginForm.email"
            label="メールアドレス"
            required
            type="email"
            prepend-icon="mdi-account-circle"
          ></v-text-field>
          <span v-if="errors.email">
            {{ errors.email[0] }}
          </span>
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
            <v-btn block class="info" dark @click="login">ログイン</v-btn>
            <v-btn
              block
              @click="drawerClose()"
              router-link
              :to="{ name: 'register' }"
            >
              新規登録
            </v-btn>
          </v-card-actions>
        </v-card-text>
      </v-form>
    </v-card>
  </v-container>
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
      getUserMessage: "",
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
                console.log(res.data);
                this.$store.commit("auth/setUser", res.data.user);
                this.$router.push("/about");
              }
            })
            .catch((error) => {
              this.getUserMessage = "ログインに失敗しました。";
              this.errors = error.response.data.errors || [];
            });
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
