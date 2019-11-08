<template>
  <span v-on:click="chooserFile()">
    <UserIcon icon="box.svg" class="store-src-item" />
  </span>
</template>

<script>
import UserIcon from "../UserIcon";
import config from "../../config/config";
import { extensionToMimeType, convertUrlToFile } from "../../helpers";

export default {
  name: "Box",
  components: {
    UserIcon
  },
  props: {},
  data() {
    return {
      pickerApiLoaded: false,
      oauthToken: null
    };
  },
  methods: {
    chooserFile() {
      let options = {
        clientId: config.BOX.CLIENT_ID,
        linkType: "direct",
        multiselect: true
      };
      let boxSelect = new BoxSelect(options);
      const self = this;

      boxSelect.launchPopup();

      // Register a success callback handler
      boxSelect.success(function(response) {
        let attachments =
          response &&
          response.length > 0 &&
          response.map(v => {
            // return convertUrlToFile(v, 'url');
            return {
              ...v,
              type: extensionToMimeType(`${v.name.split(".")[1]}`),
              downloadUrl: v["url"],
            };
          });
        self.$emit("addFiles", attachments, true);
      });
      // Register a cancel callback handler
      boxSelect.cancel(function() {
        console.log("The user clicked cancel or closed the popup");
      });
    }
  },
  mounted() {
    let boxScript = document.createElement("script");
    boxScript.setAttribute("src", "https://app.box.com/js/static/select.js");
    document.head.appendChild(boxScript);
  }
};
</script>
