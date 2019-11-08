<template>
  <b-container fluid style="padding: 0;" class="main-page">
    <SideNavbar></SideNavbar>
    <Header></Header>
    <div class="main-page-container">
      <div class="page-header">
        <h3 class="page-title">Add Documents</h3>
      </div>
      
      <div class="main-page-content">
          <vue-dropzone id="dropzone" :options="dzOptions"></vue-dropzone>
      </div>

      <Footer></Footer>
    </div>
  </b-container>
</template>

<script>
import SideNavbar from '../../components/common/SideNavbar'
import Header from '../../components/common/Header'
import Footer from '../../components/common/Footer'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

import config from '../../config/config'

import { authentication } from '../../mixins/authentication'

export default {
  name: "AddDocument",
  mixins: [authentication],
  components: {
    vueDropzone: vue2Dropzone,
    SideNavbar,
    Header,
    Footer
  },
  mounted() {
  },
  data() {
    return {
      dzOptions: {
        url: config.BASE_URL + '/api/document',
        headers: {
          Authorization: 'Bearer ' + this.$store.getters.authToken
        }
      },
      audoProcessQueue: true,
      acceptedFiles: "application/pdf",
      uploadMultiple: false,
      maxNumberOfFiles: 5,
      addRemoveLinks: false,
      clickable: ".addfile",
      dictDefaultMessage:
        "Drag a file here or <a href='javascript:;' class='addfile'>browse</a> for a file to upload",
      init: function() {
        this.on("addedfiles", function(files) {
            var dz = this;
            setTimeout(function() {
              for (var i = 0; i < files.length; i++) {
                if (files[i].type != "application/pdf") {
                  alert("You may only upload pdf files.");
                  setTimeout(function() {
                    dz.removeFile(files[i]);
                  }, 2000);
                  return;
                }
                if (files[i].size > self.maxFileSize) {
                  alert("You may only upload upto 30MB file.");
                  setTimeout(function() {
                    dz.removeFile(files[i]);
                  }, 2000);
                  return;
                }
              }
            }, 500);
          });

          this.on("sending", function(file, xhr, formData) {
            // formData.append("file", file);
          });

          this.on("success", function(a,b,c) {
            self.uploadedFiles++;
            if (self.uploadedFiles === self.$refs.docfile.dropzone.files.length) {
              // self.$toaster.success("The document has been uploaded successfully");
              // self.$router.push({ name: "client-documents" });
            }
          });
      }
    }
  },
  methods: {
  }
}
</script>

<style>

</style>
