<template>
  <ul class="user-custom-select">
    <HeaderDropdown>
      <template slot="header">
        <div style="width:100%; overflow:hidden;">{{value}}</div>
      </template>
      <template slot="dropdown">
        <b-dropdown-item
          v-for="(item, index) in items"
          :key="index"
          v-on:click="changeValue(item)"
          :value="item.id ? item.id : ''"
        >{{item}}</b-dropdown-item>
      </template>
    </HeaderDropdown>
  </ul>
</template>

<script>
import { HeaderDropdown } from "@coreui/vue";
export default {
  name: "UserSelect",
  components: {
    HeaderDropdown
  },
  props: {
    value: String,
    items: Array
  },
  methods: {
    changeValue(item) {
      this.$emit("changeValue", item);
    }
  }
};
</script>

<style lang="scss">
</style>
