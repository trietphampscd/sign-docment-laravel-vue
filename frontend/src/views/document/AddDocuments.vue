<template>
  <div class="app flex-row">
    <div class="w-100">
      <h1>{{ $t("docsign.document.title") }}</h1>
      <hr />
      <FileUpload :files="files" @addFiles="addFiles" @isData="isData" />
      <div class="d-flex justify-content-end pt-4 flex-wrap align-items-center">
        <b-form-checkbox class="m-1">{{ $t("docsign.document.checkbox") }}</b-form-checkbox>
        <button
          class="btn btn-primary min-width-124px m-1"
          v-on:click="moveNextPage()"
          v-if="old_data"
          style="position: relative;"
          v-bind:disabled="!old_data"
        >
        <i v-if="pageLoading && files.length === 0 || addDocument.loading" class="fa fa-spinner fa-spin fa-3x" style="position: absolute; top: 0; left: 33%;" />
        {{ $t("docsign.next") }}
        </button>
        <button
          v-else
          class="btn btn-primary min-width-124px m-1"
          v-on:click="moveNextPage()"
          v-bind:disabled="files.length === 0 || addDocument.loading"
          style="position: relative;"
        >
        <i v-if="pageLoading && files.length === 0 || addDocument.loading" class="fa fa-spinner fa-spin fa-3x" style="position: absolute; top: 0; left: 33%;" />
        {{ $t("docsign.next") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import FileUpload from "../../components/FileUpload";
import { EventBus } from "../../config/event-bus";
import { bytesToSize, addParamsToBlob } from "../../helpers";
import {
  ADD_DOCUMENT_REQUEST,
  DOCUMENT_FILES,
  GET_DOCUMENT_FILE,
  DOCUMENT_FILES_SUCCESS,
  GET_BLOD_FROM_URL
} from "../../store/modules/document";
import { blobToFile } from "../../helpers";

export default {
  name: "AddDocument",
  components: {
    FileUpload
  },
  data() {
    return {
      files: [],
      uuid: "",
      old_data: false,
      pageLoading: false
    };
  },
  computed: {
    ...mapGetters(["addDocument", [DOCUMENT_FILES]])
  },
  watch: {
  },
  created() {
    if (this.$root && this.$root.uploadFiles && this.$root.isLanding) {
      // this.files = this.$root.uploadFiles;
    } else {
      this.files = [];

    }
  },
  mounted() {
  },
  methods: {
    moveNextPage() {
      var isFormat = true;
      this.files.map( (val, key) => {
        if(val.type.indexOf("video/mp4") != -1 || val.type.indexOf("audio/mpeg") != -1)
        {
          isFormat = false
          let text = "File "+val.name+" is not a supported format"
          this.$awn.alert(text, {
            position: "bottom-left",
            labels: {
              alert: "Danger Message"
            }
          })
        }
      })
      if (!this.$route.query.document_id) {
        this.uuid =
          Math.random()
            .toString(36)
            .substring(2) +
          "_" +
          Date.now().toString(36);
      } else {
        this.uuid = this.$route.query.document_id;
      }
      if(isFormat){
        if(this.files && this.files.length > 0) {
          let formData = new FormData();
          var filesSocical = [];
          this.files.map((v,k) => {
            if(v.downloadUrl) {
              filesSocical.push(this.files[k])
            } else {
              formData.append("filesBrowser[]", blobToFile(v));
            }
          });
          formData.append("document_id", this.uuid);
          formData.append("filesSocical", JSON.stringify(filesSocical));
          this.$store.dispatch(ADD_DOCUMENT_REQUEST, formData).then(res => {
            res &&
              res.status &&
              this.$router.push(
                "/docu-sign/add-recipients?document_id=" + this.uuid
              );

            this.pageLoading = !res
          });
        }else {
          this.$router.push("/docu-sign/add-recipients?document_id=" + this.uuid);
        }
      }
    },
    addFiles(files, needLoadFile) {
      let self = this;
      files &&
        files.length > 0 &&
        files.map(file => {
          self.files.push(file)
        });
    },
    isData(data) {
      data ? (this.old_data = true) : (this.old_data = false);
    }
  },
  
};
</script>

<style lang="scss">
@import "./AddDocuments.scss";
</style>

