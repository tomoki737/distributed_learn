<template>
  <v-container style="max-width: 800px" class="mt-10">
    <h1>ログイン</h1>
    <v-card elevation="2" class="mt-10">
      <v-form>
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
          </v-card-actions>
          <v-divider></v-divider>
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
              :to="{ name: 'register' }"
            >
              登録はこちら
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

interface LoginForm {
  email: String;
  password: String;
}

@Component
export default class Login extends Vue {
  LoginForm: LoginForm = {
    email: "",
    password: "",
  };

  errors: String[] = [];
  showPassword: Boolean = false;
  getUserMessage: String = "";
  $store: any;
  $router: any;

  login() {
    axios
      .get("/sanctum/csrf-cookie")
      .then((res) => {
        axios
          .post("login", this.LoginForm)
          .then((res) => {
            if (res.status == 200) {
              this.$store.commit("auth/setUser", res.data.user);
              this.$router.push("/");
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
