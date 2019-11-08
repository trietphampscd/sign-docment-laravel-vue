<template>
  <span v-on:click="chooserFile()">
    <UserIcon icon="onedrive.svg" class="store-src-item" />
  </span>
</template>

<script>
import UserIcon from "../UserIcon";
import config from "../../config/config";
import { extensionToMimeType, convertUrlToFile } from "../../helpers";

export default {
  name: "OneDrive",
  components: {
    UserIcon
  },
  props: {
    prop_files: Object
  },
  data() {
    return {
      pickerApiLoaded: false,
      oauthToken: null
    };
  },
  methods: {
    chooserFile() {
      let self = this;
      let odOptions = {
        clientId: config.MICROSOFT.CLIENT_ID,
        action: "download",
        multiSelect: true,
        openInNewWindow: true,
        viewType: "files",
        advanced: {
          /** URL CHANGED */
          // endpointHint: config.MICROSOFT.endpointHint
          redirectUri: config.MICROSOFT.redirectUri,
          // queryParameters: "select=id,name,size,file,folder,photo,@microsoft.graph.downloadUrl",
        },
        success: function(files) {
          /* success handler */
          let attachments =
            files &&
            files.value &&
            files.value.length > 0 &&
            files.value.map(v => {
              // return convertUrlToFile(v, '@microsoft.graph.downloadUrl');
              return {
                ...v,
                type: extensionToMimeType(`${v.name.split(".")[1]}`),
                downloadUrl: v["@microsoft.graph.downloadUrl"],
              };
            });
          self.$emit("addFiles", attachments, true);
        },
        cancel: function() {
          /* cancel handler */
          // console.log("cancel");
        },
        error: function(error) {
          /* error handler */
          // console.log("error", error);
        }
      };
      OneDrive.open(odOptions);
    }
  },
  mounted() {
    let oneDrive = document.createElement("script");
    oneDrive.setAttribute("type", "text/javascript");
    oneDrive.setAttribute("src", "https://js.live.net/v7.2/OneDrive.js");
    // oneDrive.setAttribute("id", "onedrive-js");
    // oneDrive.setAttribute("client-id", config.MICROSOFT.CLIENT_ID);
    document.head.appendChild(oneDrive);
  }
};
</script>
