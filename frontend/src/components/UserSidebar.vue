<template>
  <div class="user-sidebar-nav">
    <div v-for="(item, index) in items" :key="index" class="user-item">
      <div
        class="user-name tool"
        v-b-toggle="`tools-collaps-${index}`"
        v-bind:style="{color: item.color}"
      >
        <i class="fa fa-caret-down toggle-icon" />
        <i v-bind:class="item.icon"/>
        
        {{item.name}}
      </div>
      <b-collapse
        class="user-tool-pannel"
        :id="`tools-collaps-${index}`"
        role="tabpanel"
        accordion="tools-accordion"
        :visible="index===0"
      >
        <draggable
          :list="item.children"
          :clone="cloneTool"
          :group="{ name: 'people', pull: 'clone', put: false }"
        >
          <div v-for="(tool, index1) in item.children" :key="index1" class="tool">
            <i v-bind:class="tool.tool_icon" v-if="!tool.img_mode"/>
            <UserIcon :icon="tool.tool_icon" v-if="tool.img_mode" class="mr-2" />
            {{tool.tool_name}}
          </div>
        </draggable>
      </b-collapse>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import UserIcon from "./UserIcon";

export default {
  name: "UserSidebarEx",
  components: {
    draggable,
    UserIcon
  },
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    const tools = [
      { name: "Signature", icon: "fa fa-pencil" },
      { name: "Full Name", icon: "fa fa-user" },
      { name: "Company", icon: "fa fa-building" },
      { name: "Title", icon: "fa fa-briefcase" },
      { name: "Text", icon: "fa fa-file-text" },
      { name: "Date Signed", icon: "fa fa-calendar" },
      { name: "Initials", icon: "initial.png", img_mode: true},
      { name: "Stamp", icon: "stamp.png", img_mode: true},
      { name: "Attachments", icon: "fa fa-paperclip" },
    ];
    const items = this.navItems.map(item => {
      const children = tools.map(tool => {
        return { ...item, tool_name: tool.name, tool_icon: tool.icon, img_mode: tool.img_mode };
      });
      return {
        ...item,
        children: children
      };
    });
    console.log(items);
    return {
      items: items
    };
  },
  computed: {
    psSettings: () => {
      // ToDo: find better rtl fix
      return {
        maxScrollbarLength: 200,
        minScrollbarLength: 40,
        suppressScrollX:
          getComputedStyle(document.querySelector("html")).direction !== "rtl",
        wheelPropagation: false,
        interceptRailY: styles => ({ ...styles, height: 0 })
      };
    }
  },
  methods: {
    cloneTool(item) {
      this.$emit("dragTool");
      return {
        ...item
      };
    }
  }
};
</script>
<style lang="scss">
@import "./UserSidebarEx.scss";
</style>
