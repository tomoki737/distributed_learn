<template>
  <div>
    <input type="hidden" name="tags" v-model="tagsJson" />
    <vue-tags-input
      v-model="tag"
      :tags="initialTags"
      :autocomplete-items="filteredItems"
      @tags-changed="(newTags) => (tags = newTags)"
      :placeholder="placeholder"
      :add-on-key="[13, 32]"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from "vue-property-decorator";
import VueTagsInput from "@johmun/vue-tags-input";
@Component({ components: { VueTagsInput } })
export default class QuestionTagsInput extends Vue {
  @Prop({ default: [] })
  initialTags: String[];
  @Prop({ default: [] })
  autocompleteItems: { text: String }[];
  @Prop({ default: "" })
  placeholder: String;
  tags: String[] = [];
  tag: String = "";
  get filteredItems() {
    return this.autocompleteItems.filter((i) => {
      return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
    });
  }
  get tagsJson() {
    const json = JSON.stringify(this.tags);
    this.$emit("tagsJson", this.tags);
    return json;
  }
  @Watch("initialTags")
  prop(newValue: String[]) {
    this.tags = newValue;
  }
}
</script>
<style lang="css" scoped>
.vue-tags-input {
  max-width: inherit;
}
</style>
<style lang="css">
.vue-tags-input .ti-tag {
  background: transparent;
  border: 1px solid #747373;
  color: #747373;
  margin-right: 4px;
  border-radius: 0px;
  font-size: 13px;
}
</style>

