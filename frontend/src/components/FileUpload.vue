<template>
  <div class="w-100">
    <div class="drag-drop-container" v-bind:class="{'drag-has-file':checkHasFiles()}">
      <form ref="fileform" class="drag-drop-div" v-bind:class="{'has-files':checkHasFiles()}">
        <span v-on:click="openBrows()">
          <UserIcon icon="folder.svg" class="folder-2" />
        </span>
        <input type="file" ref="file" multiple style="display: none" @change="onFileChange" />
        <div class="px-md-3" v-bind:class="{'has-file-upload-type':checkHasFiles()}">
          <div class="drag-a-file" v-bind:class="{'has-file':checkHasFiles()}">
            {{ $t("home.drag.title_1") }}
            <span
              class="text-style-1 clickable-text"
              v-on:click="openBrows()"
            >{{ $t("home.drag.title_2") }}</span>
            {{ $t("home.drag.title_3") }}
          </div>
          <div
            class="you-can-use"
            v-bind:class="{'text-left-align':checkHasFiles()}"
          >{{ $t("home.drag.subtitle") }}</div>
        </div>
        <div class="store-src" v-bind:class="{'none-top-margin':checkHasFiles()}">
          <Dropbox @addFiles="addFiles" />
          <GoogleDrive @addFiles="addFiles" />
          <OneDrive @addFiles="addFiles" />
          <Box @addFiles="addFiles" />
          <!-- <UserIcon icon="dropbox.svg" class="store-src-item" /> -->
          <!-- <UserIcon icon="google-drive.svg" class="store-src-item" /> -->
          <!-- <UserIcon icon="onedrive.svg" class="store-src-item" /> -->
          <!-- <UserIcon icon="box.svg" class="store-src-item" /> -->
        </div>
        <div
          class="you-can-use"
          v-bind:class="{'you-can-use-has-file':checkHasFiles()}"
        >{{ $t("home.drag.comments") }}</div>
        <b-button
          variant="outline-primary"
          style="min-width:153px;margin-top: 10px;"
          :class="{'none-top-margin':files.length > 0}"
        >{{ $t("home.drag.button") }}</b-button>
      </form>
    </div>
    <div class="file-list">
      <div v-if="$route.query.document_id">
        <div v-for="(file, index) in old_files" :key="index" class="file-listing">
          <div class="file-content">
            <img v-bind:src="getFileType(file.name)" class="folder-2" />
            <div class="file-info ml-3">
              <div class="doc-file-name">{{ file.name }}</div>
              <div class="doc-file-info">{{getFileSize(file.bytes || file.size)}}</div>
              <!-- <div class="doc-file-info">5 pages</div> -->
            </div>
          </div>
          <div class="file-control">
            <!-- <i class="fa fa-ellipsis-h fa-lg mr-4"></i> -->
            <!-- <i class="cui-trash icons"></i> -->
            <span v-on:click="removeFile(index, true, file.id)">
              <UserIcon icon="delete.svg" :button="true" />
            </span>
          </div>
        </div>
      </div>
      <div v-for="(file, index) in files" :key="index+1000" class="file-listing">
        <div class="file-content">
          <img v-bind:src="getFileType(file.name, file.iconUrl ? file.iconUrl : null,file.url ? file.url : null)" class="folder-2" />
          <div class="file-info ml-3">
            <div class="doc-file-name">{{ file.name }}</div>
            <div class="doc-file-info">{{getFileSize(file.bytes || file.size)}}</div>
            <!-- <div class="doc-file-info">5 pages</div> -->
          </div>
        </div>
        <div class="file-control">
          <!-- <i class="fa fa-ellipsis-h fa-lg mr-4"></i> -->
          <!-- <i class="cui-trash icons"></i> -->
          <span v-on:click="removeFile(index, false)">
            <UserIcon icon="delete.svg" :button="true" />
          </span>
        </div>
      </div>
      <!-- <div v-if="files && files.length>0">
        <div class="file-listing w-100">
          <div class="file-content w-100">
            <img v-bind:src="getFileType(files[0].name)" class="folder-2" />
            <div class="file-info ml-3 w-100">
              <div class="doc-file-name">168 KB of 378 KB (56% Done)</div>
              <div class="text-right">
                <i class="cui-circle-x icons" style="font-size:18px"></i>
                <b-progress :value="56" :max="100" class="mt-1"></b-progress>
              </div>
            </div>
          </div>
        </div>
      </div>-->
    </div>
  </div>
</template>

<script>
import UserIcon from "./UserIcon";
import { GoogleDrive, Dropbox, OneDrive, Box } from "./connect";
import { bytesToSize, addParamsToBlob, extensionToMimeType } from "../helpers";
import { EventBus } from "../config/event-bus";
import { mapGetters, mapState } from "vuex";
import store from "../store/store";
import { GET_DOCS } from "../store/actions.type";
import {
  GET_BLOD_FROM_URL,
  REMOVE_DOCUMENT_REQUEST
} from "../store/modules/document";

export default {
  name: "FileUpload",
  components: {
    UserIcon,
    GoogleDrive,
    Dropbox,
    OneDrive,
    Box
  },
  props: ["files", "default_files"],
  data() {
    return {
      dragAndDropCapable: false,
      old_files: [],
      landingFiles: [],
      isFile: false
    };
  },
  computed: {
    ...mapGetters({
      getDocuments: "getDocuments"
    }),
  },
  created() {
    
  },
  mounted() {
    if (this.$route.query.document_id) {
      this.old_files = [];
      store
        .dispatch(GET_DOCS, this.$route.query.document_id)
        .then(res => {
          this.getDocuments.map((value, key) => {
            store
              value.file ={
                ...value,
                name: value.document_name,
                downloadUrl: value.document_file,
                id: value.id,
                size: value.size
              };
              this.old_files.push(value.file);
          });
          this.isFile = true;
          this.$emit("isData", true);
        })
        .catch(err => {
          this.$router.push("/docu-sign/add-document");
        });

    } else {
      this.isFile = false;
    }
    this.dragAndDropCapable = this.determineDragAndDropCapable();
    if (this.dragAndDropCapable) {
      [
        "drag",
        "dragstart",
        "dragend",
        "dragover",
        "dragenter",
        "dragleave",
        "drop"
      ].forEach(
        function(evt) {
          this.$refs.fileform &&
            this.$refs.fileform.addEventListener(
              evt,
              function(e) {
                e.preventDefault();
                e.stopPropagation();
              }.bind(this),
              false
            );
        }.bind(this)
      );

      this.$refs.fileform &&
        this.$refs.fileform.addEventListener(
          "drop",
          function(e) {
            this.addFiles(e.dataTransfer.files);
          }.bind(this)
        );
    }
  },
  methods: {
    getFileType(fileName, iconUrl = null, url = null) {
      if(url && url.indexOf("https://docs.google.com") != -1){
        return iconUrl;
      }else if(fileName.split(".").pop() === "mp3" || fileName.split(".").pop() === "mp4") {
        return "img/icons/warning-sign.svg";
      } else {
        return "img/icons/" + fileName.split(".").pop() + ".svg";
      }
    },
    getFileSize(size) {
      return size== 0 ? '' : bytesToSize(size);
    },
    determineDragAndDropCapable() {
      var div = document.createElement("div");
      return (
        ("draggable" in div || ("ondragstart" in div && "ondrop" in div)) &&
        "FormData" in window &&
        "FileReader" in window
      );
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      files && files.length > 0 && this.addFiles([files[0]]);
    },
    addFiles(files, needLoadFile) {
      files.map( (val, key) => {
        if(val.type.indexOf("video/mp4") != -1 || val.type.indexOf("audio/mpeg") != -1)
        {
          let text = "File "+val.name+" is not a supported format"
          this.$awn.alert(text, {
            position: "bottom-left",
            labels: {
              alert: "Danger Message"
            }
          })
        }
      })
      if (this.$route.path.indexOf("landing") != -1) {
        this.$emit("addLandingFiles", files);
        this.$router.push({
          path: "/docu-sign/add-document",
          // query: { files: files }
        });
      }
      this.$emit("addFiles", files, needLoadFile);

    },
    removeFile(index, isRemove, doc_id = null) {
      if (isRemove) {
        store.dispatch(REMOVE_DOCUMENT_REQUEST, doc_id).then(resp => {
          if (resp && resp.status) {
            this.old_files.splice(index, 1);
            if(this.old_files.length < 1) {
              this.$emit("isData", false);
            }
          } else {
            window.confirm("Remove file fail");
          }
        });
      } else {
        this.files.splice(index, 1);
      }
    },
    openBrows() {
      this.$refs.file.click();
    },
    checkHasFiles() {
      return (this.files && this.files.length > 0) || (this.old_files && this.old_files.length > 0);
    }
  },
  
};
</script>
<style lang="scss">
@import "./FileUpload.scss";
</style>
