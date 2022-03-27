<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-form>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="LoginForm.email"
              label="メールアドレス"
              required
            ></v-text-field>
            <span v-if="errors.email">
              {{ errors.email[0] }}
            </span>
          </v-col>

          <v-col cols="12">
            <v-text-field
              v-model="LoginForm.password"
              label="パスワード"
              required
            ></v-text-field>
            <span v-if="errors.password">
              {{ errors.password[0] }}
            </span>
          </v-col>
          <v-card-actions>
            <v-btn class="info" dark @click="login">ログイン</v-btn>
          </v-card-actions>
          <v-card-actions>
            <v-btn class="info" dark @click="logout">ログアウト</v-btn>
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
      LoginForm: {
        email: "",
        password: "",
      },
      errors: [],
    };
  },
  methods: {
    login() {
      axios
        .get("/sanctum/csrf-cookie")
        .then(res => {
          axios
            .post("login", this.LoginForm)
            .then((res) => {
              if (res.status == 200) {
                this.$router.push("/");
              }

              this.getUserMessage = "ログインに失敗しました。";
            })
            .catch((error) => {
              this.errors = error.response.data.errors;
            });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    logout() {
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("logout")
          .then((res) => {
            this.$router.push("/");
          })
          .catch((error) => {
            console.error(error);
          });
      });
    },
  },
};
</script>
