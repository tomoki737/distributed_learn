<template>
  <div>
    <input type="file" ref="preview" @change="uploadFile" />
    <div v-if="url" style="position: reactive">
    <div style="position: absolute" @click="deletePreview">X</div>
      <v-img contain :src="url" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      url: "",
    };
  },
  methods: {
    uploadFile() {
      const file = this.$refs.preview.files[0];
      this.url = URL.createObjectURL(file);
    },
    deletePreview() {
      this.url = '';
    },
  },
};
</script>
