<template>
  <div>
    <div class="content-dash draw-initials">
      <input
        type="file"
        ref="upload_initials_file"
        style="display: none"
        v-on:change="onFileChanged"
      />
      <div
        class="draw-placeholder clickable-icon"
        v-on:click="selectInitials"      
      >
        <img v-if="initials_file.length>0" :src="image" class="img-responsive" style="width: 170px; height: auto; max-height: inherit !important; border-radius: 20%" />
        <img v-else :src="config_file.img || 'img/icons/upload.svg'" class="img-responsive" />
        <div class="mt-3">{{ config_file.text || 'Upload Image' }}</div>
      </div>
    </div>
    <div class="reset" v-if="initials_file.length>0">
      <b-button variant="link" v-on:click="selectInitials">{{ $t('signature.button.replace') }}</b-button>
      <span class="mx-2">|</span>
      <b-button variant="link" v-on:click="initials_file = ''">{{ $t('signature.button.remove') }}</b-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageUpload',
  components: {},
  props: {
    uploadComponent: {
      type: String
    },
    signature_file: {
      type: String
    },
    files: {
      type: Array,
      default: function () {
        return []
      }
    },
    config_file: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      image: "",
      old_files: "",
      isFile: false,
      initials_file: ""
    };
  },
  methods: {
    selectInitials() {
      this.$refs.upload_initials_file.click();
    },

    onFileChanged(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.$emit('toggle', files[0]);
        this.createImage(files[0]);
    },
    createImage(file) {
        let reader = new FileReader();
        var vm = this;
        reader.onload = (e) => {
            vm.image = e.target.result;
            vm.initials_file = e.target.result;
            vm.$emit("files", vm.initials_file);
        };
        reader.readAsDataURL(file);
    }
  }
};
</script>

<style lang="scss" scoped>
  img {
    max-height: 36px;
  }
</style>