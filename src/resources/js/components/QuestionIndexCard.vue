<template>
  <v-card elevation="2" class="mt-5 mx-auto">
    <v-tabs fixed-tabs>
      <v-tab @click="select(1)">質問</v-tab>
      <v-tab @click="select(2)">解答</v-tab>
    </v-tabs>
    <v-card-text>
      <p v-show="isSelect === 1">{{ question.question }}</p>
      <p v-show="isSelect === 2">{{ question.answer }}</p>
    </v-card-text>
    <v-card-actions>
      <div v-for="(tag, index) in question.tags" :key="index">
        <v-chip
          router-link
          :to="{ name: 'tags.show', params: { name: tag.name } }"
          >{{ tag.name }}</v-chip
        >
      </div>
      <v-spacer></v-spacer>
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on"><v-icon>mdi-menu</v-icon></v-btn>
        </template>
        <v-list>
          <v-list-item
            router-link
            :to="{
              name: 'question.edit',
              params: { question_id: question.id },
            }"
          >
            <v-list-item-title>編集</v-list-item-title>
          </v-list-item>
          <v-list-item @click="questionDelete(question.id)">
            <v-list-item-title>削除</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  props: {
    question: {},
  },

  data() {
    return {
      isSelect: 1,
    };
  },

  methods: {
    async questionDelete(question_id) {
      const response = await axios
        .delete("/api/question/" + question_id)
        .catch((error) => {
          return console.error(error);
        });
      this.$emit("get");
    },

    select(val) {
      this.isSelect = val;
    },
  },
};
</script>
