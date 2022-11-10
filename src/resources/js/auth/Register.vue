<template>
  <v-container style="max-width: 800px" class="mt-10">
    <h1 class="text-center mb-3">新規登録</h1>
    <v-divider></v-divider>
    <v-card elevation="2" class="mt-8 mx-auto">
      <v-form>
        <v-card-text>
          <span>
            {{ getUserMessage }}
          </span>
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
            <v-btn class="info" block dark @click="register">登録</v-btn>
          </v-card-actions>
          <v-divider class="mt-5"></v-divider>
          <v-card-title>
            <div class="mx-auto text-body-1">
              ユーザー登録せずに機能を試したい方はこちら
            </div>
          </v-card-title>
          <v-card-actions>
            <v-btn block class="error" dark @click="loginGuest"
              >ゲストユーザーログイン</v-btn
            >
          </v-card-actions>
          <v-divider class="mt-5"></v-divider>
          <v-card-actions>
            <v-btn
              class="mx-auto"
              @click="drawerClose()"
              router-link
              text
              :to="{ name: 'login' }"
            >
              ログインはこちら
            </v-btn>
          </v-card-actions>
        </v-card-text>
      </v-form>
    </v-card>
  </v-container>
</template>

<script lang="ts">
import axios from "axios";
import { Component, Vue } from "vue-property-decorator";

interface RegisterForm {
  name: String;
  email: String;
  password: String;
}

@Component
export default class Register extends Vue {
  registerForm: RegisterForm = {
    name: "",
    email: "",
    password: "",
  };
  errors: String[] = [];
  showPassword: Boolean = false;
  $store: any;
  $router: any;
  getUserMessage: String = "";

  async register() {
    axios.get("/sanctum/csrf-cookie").then((res) => {
      axios
        .post("/register", this.registerForm)
        .then((res) => {
          this.$store.commit("auth/setUser", res.data.user);
          this.$router.push("/");
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
        });
    });
  }

  loginGuest() {
    axios
      .get("/api/guest")
      .then((res) => {
        this.$store.commit("auth/setUser", res.data.user);
        this.$router.push("/");
      })
      .catch((error) => {
        this.getUserMessage = "ログインに失敗しました。";
        this.errors = error.response.data.errors || [];
      });
  }
}
</script>

