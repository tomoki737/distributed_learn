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
            <span v-if="errors.email">
              {{ errors.name[0] }}
            </span>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.email"
              label="メールアドレス"
              required
            ></v-text-field>
            <span v-if="errors.email">
              {{ errors.email[0] }}
            </span>
          </v-col>
          <v-col cols="12">
            <v-text-field
              v-model="registerForm.password"
              label="パスワード"
              required
            ></v-text-field>
            <span v-if="errors.password">
              {{ errors.password[0] }}
            </span>
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
      },
      errors: [],
    };
  },
  methods: {
    async register() {
      axios.get("/sanctum/csrf-cookie").then((res) => {
        axios
          .post("/register", this.registerForm)
          .then((res) => {
            this.$router.push("/");
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      });
    },
  },
};
</script>

