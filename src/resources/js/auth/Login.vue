<template>
  <v-card elevation="2" class="mt-10 mx-auto" width="600px">
    <v-form>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="email"
              label="メールアドレス"
              required
            ></v-text-field>
          </v-col>

          <v-col cols="12">
            <v-text-field
              v-model="password"
              label="パスワード"
              required
            ></v-text-field>
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
      email: "",
      password: "",
    };
  },
  methods: {
    login() {
      axios
        .get("/sanctum/csrf-cookie")
        .then((res) => {
          axios
            .post("login", {
              email: this.email,
              password: this.password,
            })
            .then((res) => {
              if (res.status == 200) {
                this.$router.push("/");
              }

              this.getUserMessage = "ログインに失敗しました。";
            })
            .catch((err) => {
              console.log(err);
              this.getUserMessage = "ログインに失敗しました。";
            });
        })
        .catch((err) => {
          //
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
