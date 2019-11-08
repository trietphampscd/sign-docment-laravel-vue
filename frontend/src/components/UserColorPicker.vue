<template>
  <div class="user-color-picker">
    <div class="color-palets">
      <div
        class="color-pan"
        v-for="color in colors"
        :key="color"
        v-bind:style="{backgroundColor: color}"
        v-on:click="cur_color=color; changeValue()"
      ></div>
      <b-input-group class="w-100">
        <b-input-group-prepend>
          <b-input-group-text>
            <UserIcon icon="sharp.svg"></UserIcon>
          </b-input-group-text>
        </b-input-group-prepend>
        <b-form-input
          type="text"
          placeholder="Put your color"
          @input="changeValue"
          @change="changeValue"
          v-model="cur_color"
        ></b-form-input>
      </b-input-group>
    </div>
  </div>
</template>

<script>
import UserIcon from "./UserIcon";
import VueMask from "v-mask";
export default {
  name: "UserColorPicker",
  components: {
    UserIcon,
    VueMask
  },
  data() {
    return {
      colors: [
        "#eb144c",
        "#ff6900",
        "#ffc601",
        "#7bdcb5",
        "#00d084",
        "#8ed1fc",
        "#0693e3",
        "#2e3949",
        "#abb8c3",
        "#ffffff"
      ],
      cur_color: ""
    };
  },
  methods: {
    changeValue() {
      this.$emit("changeValue", this.cur_color);
    }
  }
};
</script>

<style lang="scss">
.user-color-picker {
  .color-palets {
    display: flex;
    // justify-content: space-between;
    flex-wrap: wrap;
    align-items: center;
    .color-pan {
      margin: 2px;
      width: 30px;
      height: 30px;
      min-width: 30px;
      border-radius: 3px;
      border: solid 1px #ebebeb;
      &:hover {
        cursor: pointer;
        box-shadow: 0 0 3px 0 #775649;
      }
    }
  }
}
</style>
