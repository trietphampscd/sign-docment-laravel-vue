<template>
  <div class="folder-items">
    <div class="folder-item" v-for="(folder, index) in folders" :key="index">
      <div class="folder-name" v-b-toggle="layer_id+'//'+index" @click="getEventCollapse(folder.id)" :id="`folder_name_${folder.id}`">
        <div>
          <i class="fa fa-caret-down toggle-icon" v-if="folder.children && folder.children.length > 0"/>
          <span class="space-div" v-if="!folder.children || folder.children.length <= 0"/>
          <i class="fa fa-folder-open" /> {{folder.name}}
        </div>
      </div>
      <b-collapse v-if="folder.children && folder.children.length > 0" :id="layer_id+'//'+index">
        <SideFolders :folders="folder.children" :layer_id="layer_id+'//'+index"/>
      </b-collapse>
    </div>
  </div>
</template>

<script>
import { EventBus } from "../config/event-bus";
export default {
  name: "SideFolders",
  props: {
    folders: {
      type: Array,
      required: true,
      default: () => []
    },
    layer_id: {
      type: String,
      required: true,
      default: ""
    },
    move_id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
    }
  },
  created() {
  },
  mounted() {
    if(this.move_id) {
      sessionStorage.setItem('folder_id', this.move_id);
      $("#folder_name_"+this.move_id).addClass("dis-folder");
    }
  },
  methods: {
    getEventCollapse(id) {
      sessionStorage.setItem('parent_id', id);
      $(".folder-name").removeClass("active-color");
      $("#folder_name_"+id).addClass("active-color");
    }
  }
};
</script>
<style lang="scss">
@import "./SideFolders.scss";
</style>
