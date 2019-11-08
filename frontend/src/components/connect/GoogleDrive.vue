<template>
  <span v-on:click="chooserFile()">
    <UserIcon icon="google-drive.svg" class="store-src-item" />
  </span>
</template>

<script>
import UserIcon from "../UserIcon";
import config from "../../config/config";
import { extensionToMimeType, convertUrlToFile } from "../../helpers";

export default {
  name: "GoogleDrive",
  components: {
    UserIcon
  },
  props: {
    prop_files: Object
  },
  data() {
    return {
      ...config.GOOGLE_CONFIG,
      pickerApiLoaded: false,
      oauthToken: null
    };
  },
  methods: {
    async chooserFile() {
      // console.log("Clicked");
      await gapi.load("auth2", () => {
        // console.log("Auth2 Loaded");
        gapi.auth2.authorize(
          {
            client_id: this.clientId,
            scope: this.scope,
            immediate: false
          },
          this.handleAuthResult
        );
      });
      gapi.load("picker", () => {
        // console.log("Picker Loaded");
        this.pickerApiLoaded = true;
        this.createPicker();
      });
    },
    handleAuthResult(authResult) {
      // console.log("Handle Auth result", authResult);
      if (authResult && !authResult.error) {
        this.oauthToken = authResult.access_token;
        this.createPicker();
      }
    },
    createPicker() {
      // console.log("Create Picker", google.picker);
      if (this.pickerApiLoaded && this.oauthToken) {
        var picker = new google.picker.PickerBuilder()
          .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
          .addView(google.picker.ViewId.DOCS)
          .setOAuthToken(this.oauthToken)
          .setDeveloperKey(this.developerKey)
          .setCallback(this.pickerCallback)
          .build();
        picker.setVisible(true);
      }
    },
    async pickerCallback(data) {
      // console.log("PickerCallback", data);
      var url = "nothing";
      var name = "nothing";
      if (data[google.picker.Response.ACTION] === google.picker.Action.PICKED) {
        // Array of Picked Files
        if (data.docs && data.docs.length > 0) {
          let attachments =
            data.docs &&
            data.docs.length > 0 &&
            data.docs.map(v => {
              // return convertUrlToFile(v, 'embedUrl');
              return {
                ...v,
                type: extensionToMimeType(`${v.name.split(".")[1]}`),
                size: v.sizeBytes,
                downloadUrl: `https://drive.google.com/uc?id=${v.id}&export=download`,
              };
            });
          this.$emit("addFiles", attachments, true);
        }
      }
    }
  },
  mounted() {
    let gDrive = document.createElement("script");
    gDrive.setAttribute("type", "text/javascript");
    gDrive.setAttribute("src", "https://apis.google.com/js/api.js");
    document.head.appendChild(gDrive);
  }
};
</script>
