<template>
  <span v-on:click="chooserFile()">
    <UserIcon icon="dropbox.svg" class="store-src-item" />
  </span>
</template>

<script>
import UserIcon from "../UserIcon";
import config from "../../config/config";
import { extensionToMimeType, convertUrlToFile } from "../../helpers";

export default {
  name: "Dropbox",
  components: {
    UserIcon
  },
  props: {
    prop_files: Object,
    addFiles: Function
  },
  data() {
    return {
      pickerApiLoaded: false,
      oauthToken: null
    };
  },
  methods: {
    async chooserFile() {
      let options = {
        success: async files => {
          let attachments =
            files &&
            files.length > 0 &&
            files.map(v => {
              // return convertUrlToFile(v, 'link');
              return {
                ...v,
                type: extensionToMimeType(`${v.name.split(".")[1]}`),
                size: v.bytes,
                downloadUrl: v["link"],
              };
            });
          this.$emit("addFiles", attachments, true );
        },
        cancel: function() {},
        linkType: "direct",
        multiselect: true,
        // extensions: [".pdf", ".doc", ".docx"],
        folderselect: false
        // sizeLimit: 102400000
      };
      Dropbox.choose(options);
    }
  },
  mounted() {
    let dropBox = document.createElement("script");
    dropBox.setAttribute(
      "src",
      "https://www.dropbox.com/static/api/2/dropins.js"
    );
    dropBox.setAttribute("id", "dropboxjs");
    dropBox.setAttribute("data-app-key", config.DROPBOX_KEY);
    document.head.appendChild(dropBox);
  }
};
</script>
