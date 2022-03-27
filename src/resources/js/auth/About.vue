<template>
  <div>
    <v-card elevation="2" class="mt-10">
      <v-card-text>
        {{ user.name }}
        {{ user.email }}
        <v-btn outlined @click="logout">ログアウト</v-btn>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
    data() {
        return {
            user: ""
        };
    },
    mounted() {
        axios.get("/api/user").then(response => {
            this.user = response.data;
        });
    },
    methods: {
        logout() {
            axios
                .post("api/logout")
                .then(response => {
                    console.log(response);
                    localStorage.removeItem("auth");
                    this.$router.push("/login");
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
