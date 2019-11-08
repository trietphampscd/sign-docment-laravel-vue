<template>
  <div class="user-sidebar-nav remove-user-drag">
    <div v-for="(item, index) in items" :key="index" class="user-item">
      <div
        class="user-name tool"
        v-b-toggle="`tools-collaps-${index}`"
        v-bind:style="{color: item.color}"
      >
        <i class="fa fa-caret-right toggle-icon" />
        <i v-bind:class="item.icon" />
        {{item.name}}
      </div>
      <b-collapse
        class="user-tool-pannel"
        :id="`tools-collaps-${index}`"
        role="tabpanel"
        accordion="tools-accordion"
        :visible="index===0"
      >
        <div class="col" style="padding: 0">
          <div v-for="(tool, index1) in item.children" :key="index1" class="tool">
            <span
              :id="`list_drag_${index}_${index1}`"
              class="user-drag"
              :data-color="item.color"
              :data-tool="addDataToElement(item, tool)"
            >
              <font-awesome-icon icon="stamp" v-if="tool.icon == 'fas fa-stamp'" style="margin-right: 10px;"></font-awesome-icon>
              <i v-bind:class="tool.icon" v-else/>
              <span>{{$t(tool.label)}}</span>
            </span>
          </div>
        </div>

        <!-- <draggable
          :list="item.children"
          :clone="cloneTool"
          :group="{ name: 'people', pull: 'clone', put: false }"
        >
          <div v-for="(tool, index1) in item.children" :key="index1" class="tool">
            <i v-bind:class="tool.tool_icon" />
            {{tool.tool_name}}
          </div>
        </draggable>-->
      </b-collapse>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import { userListDefaultColors } from "../helpers";
import { userSideBarHandle, prepareTools } from "../helpers/prepareHandle";
import { GET_RECIPIENTS, DELETE_ANNTATION } from "../store/modules/document";
import { omit } from "lodash";
import { EventBus } from "../config/event-bus";

export default {
  name: "UserSidebarEx",
  components: {
    draggable
  },
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    // const items = this.navItems.map(item => {
    //   const children = prepareTools.map(tool => tool);
    //   return {
    //     ...item,
    //     children: children
    //   };
    // });
    return {
      // items: items,
      clientInfo: {},
      items: []
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
    },
    addDataToElement(item, tool) {
      return JSON.stringify({
        ...omit(item, ["children"]),
        tool: { ...tool },
        clientId: this.clientInfo.id
      });
    }
  },
  mounted() {
    let vm = this;
    vm.$store
      .dispatch(GET_RECIPIENTS, vm.$route.query.document_id)
      .then(res => {
        if (res.status && res.list) {
          vm.items = res.list.map((item, key) => {
            const children = prepareTools.map(tool => tool);
            return {
              ...item,
              color: userListDefaultColors[key],
              icon: "fa fa-user",
              children: children
            };
          });
          EventBus.$emit("recipientsList", vm.items);
          userSideBarHandle((annotation_id, draggable) => {
            vm.$store.dispatch(DELETE_ANNTATION, annotation_id).then(res => {
              $(draggable).remove();
            });
          });
        }
      });

    EventBus.$on("clientInfo", clientInfo => {
      vm.clientInfo = clientInfo;
    });
  }
};
</script>
<style lang="scss">
@import "./UserSidebarEx.scss";
</style>
