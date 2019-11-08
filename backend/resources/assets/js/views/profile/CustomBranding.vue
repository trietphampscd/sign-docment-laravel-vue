<template>
  <div class="app flex-row" ref="fileform">
    <div class="w-100">
      <h1>Custom Branding</h1>
      <hr class="mb-4" />
      <div class="row">
        <div class="col-12 col-md-8">
          <div class="content-card">
            <div class="content-header">
              <strong>Email Style - Custom Branding</strong>
              <b-button variant="primary" class="mx-3" v-on:click="editStyle()">Edit email Style</b-button>
            </div>
            <div
              class="comments mb-3"
            >This feature allows you to change the logo, style, and intro of your signiture request.</div>
            <div class="form-group d-flex align-items-center">
              <label class="mr-3">Logo Image</label>
              <div class="logo-img">
                <img src="img/icons/logo_axisbits.svg" />
              </div>
            </div>
            <div class="form-group d-flex align-items-center">
              <label class="mr-3 mb-0">
                Siging request Message
              </label>
              <span>{{explanation}}</span>
            </div>
            <div class="d-flex-align-center flex-wrap">
              <div class="form-group d-flex align-items-center mr-5">
                <label class="mr-3 mb-0">
                  Edge Color
                </label>
                <div class="select-color bg-white border-0">
                  <div class="color-pan" v-bind:style="{backgroundColor: top_color}"></div>
                  <div class="color-text">{{top_color}}</div>
                </div>
              </div>
              <div class="form-group d-flex align-items-center mr-5">
                <label class="mr-3 mb-0">
                  Button Color
                </label>
                <div class="select-color bg-white border-0">
                  <div class="color-pan" v-bind:style="{backgroundColor: button_color}"></div>
                  <div class="color-text">{{button_color}}</div>
                </div>
              </div>
              <div class="form-group d-flex align-items-center">
                <label class="mr-3 mb-0">
                  Button Text Color
                </label>
                <div class="select-color bg-white border-0">
                  <div class="color-pan" v-bind:style="{backgroundColor: button_text_color}"></div>
                  <div class="color-text">{{button_text_color}}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="content-card">
            <div class="content-header">
              <strong>Sender’s Info - You can customize sender’s information here:</strong>
            </div>
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="Name"
                placeholder="Name"
                name="name"
                value="Axisbits"
                required
              />
            </div>
            <div class="d-flex justify-content-center align-items-center">
              <b-button class="mr-4" variant="link" v-on:click="cancelName()">Cancel</b-button>
              <b-button variant="primary" v-on:click="saveName()">Save Changes</b-button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="edit-style-modal" ref="edit-style-modal" hide-footer centered size="xl">
      <div class="edit-style-modal">
        <div class="row">
          <div class="col-12 col-lg-4 pr-lg-0">
            <div class="content-card">
              <div class="content-header">
                <strong>Email Style - Custom Branding</strong>
              </div>
              <div class="form-group">
                <label>Logo Image</label>
                <div class="logo-img clickable-icon" v-on:click="changeLogo()">
                  <img src="img/icons/logo_axisbits.svg" />
                </div>
              </div>
              <hr class="mb-4" />
              <div class="form-group">
                <label>Siging request Message</label>
                <input
                  type="text"
                  class="form-control"
                  id="logo_link"
                  placeholder="Explanation"
                  name="logo_link"
                  v-model="explanation"
                />
              </div>
              <div class="form-group">
                <label>Edge Color</label>
                <div
                  class="select-color enable"
                  v-b-toggle="'top-color-collapse'"
                >
                  <div class="color-pan" v-bind:style="{backgroundColor: top_color}"></div>
                  <div class="color-text">{{top_color}}</div>
                </div>
                <b-collapse id="top-color-collapse" class="color-collapse">
                  <UserColorPicker @changeValue="top_color = $event"></UserColorPicker>
                </b-collapse>
              </div>
              <div class="form-group">
                <label>Button Color</label>
                <div
                  class="select-color enable"
                  v-b-toggle="'button-color-collapse'"
                >
                  <div class="color-pan" v-bind:style="{backgroundColor: button_color}"></div>
                  <div class="color-text">{{button_color}}</div>
                </div>
                <b-collapse id="button-color-collapse" class="color-collapse">
                  <UserColorPicker @changeValue="button_color = $event"></UserColorPicker>
                </b-collapse>
              </div>
              <div class="form-group">
                <label>Button Text Color</label>
                <div
                  class="select-color enable"
                  v-b-toggle="'button-text-color-collapse'"
                >
                  <div class="color-pan" v-bind:style="{backgroundColor: button_text_color}"></div>
                  <div class="color-text">{{button_text_color}}</div>
                </div>
                <b-collapse id="button-text-color-collapse" class="color-collapse">
                  <UserColorPicker @changeValue="button_text_color = $event"></UserColorPicker>
                </b-collapse>
              </div>
              <div class="d-flex justify-content-center align-items-center">
                <b-button class="mr-4" variant="link" v-on:click="cancelField()">Cancel</b-button>
                <b-button variant="primary" v-on:click="saveField()">Save Changes</b-button>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8">
            <div class="content-card">
              <div class="doc-format"  v-bind:style="{borderColor: top_color}">
                <div class="doc-header" v-bind:style="{borderColor: top_color}">
                  <img src="img/logo_dark.svg" width="125" class="my-1"/>
                  <img src="img/icons/logo_axisbits_sm.svg" class="my-1"/>
                </div>
                <div class="doc-content">
                  <div class="doc-title">{{explanation}}</div>
                  <div class="text-center mt-3 mb-4">
                    Press button below to go to the signing page.
                  </div>
                  <div class="doc-item doc-top-border">
                    <span class="min-width-230px">Document name</span>
                    <span class="ml-3">Signer</span>
                  </div>
                  <div class="doc-item doc-top-border d-flex border-bottom-0 signer">
                    <div class="comments min-width-124px">Signer 1:</div>
                    <span>Hong Gil-dong (kim@modusign.co.kr)</span>
                  </div>
                  <div class="doc-item d-flex signer">
                    <div class="comments min-width-124px">Signer 2:</div>
                    <span>Continuous Improvement lorem ipsum sit dollor amet (hong@modusign.co.kr)</span>
                  </div>
                  <b-button
                    block
                    v-bind:style="{backgroundColor: button_color, color: button_text_color}"
                    class="mt-4"
                  >Checking and Signing Content</b-button>
                </div>
              </div>
              <div class="doc-content">
                <div class="doc-subtitle">Do not share links with email.</div>
                <div class="doc-text mb-4">
                  If you share a link with this message, unauthorized third parties can verify and sign the content. Signing is not responsible for any problems that occur at this time.
                </div>
              </div>
              <div class="doc-footer doc-text">
                This is send-only. Please do not reply. <br/>© 2019. Coffeesign Inc. All rights reserved. 
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
    
    <b-modal id="change-logo-modal" ref="change-logo-modal"
       hide-footer centered size="xl">
      <div class="change-logo-modal">
        <div class="title">Change Avatar</div>
        <div class="img-control-btns">
          <b-button variant="outline-primary">Change Photo</b-button>
          <div>
            <i class="fa fa-rotate-left clickable-icon" v-on:click="rotate(-90)"/>
            <i class="fa fa-rotate-right clickable-icon mx-3" v-on:click="rotate(90)"/>
          </div>
        </div>        
        <vue-cropper
          ref="cropper"
          :src="'img/icons/logo_axisbits.svg'"
          alt="Source Image"
          :cropmove="cropped"
          class="my-4"
          :minContainerHeight="300"
        >
        </vue-cropper>
        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelLogo()" >Cancel</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="saveLogo()">Save changes</button>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import UserColorPicker from "../../components/UserColorPicker";
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import Vue from "vue";
import CxltToastr from "cxlt-vue2-toastr";
var toastrConfigs = {
  position: "top right",
  showDuration: 500,
  delay: 0,
  timeOut: 5000,
  hideDuration: 500,
  progressBar: true,
  color: "#00c292"
  // icon: "img/icons/Info@2x.png"
};
Vue.use(CxltToastr, toastrConfigs);
export default {
  name: "CustomBranding",
  components: {
    UserIcon,
    UserSelect,
    UserColorPicker,
    VueCropper
  },
  data() {
    return {
      explanation: "You got the Signing request",
      cropped: null,
      edit_name: false,
      edit_field: false,
      top_color: "#2e3949",
      button_color: "#6299F8",
      button_text_color: "#FFFFFF"
    };
  },
  methods: {
    rotate(rotationAngle) {
      this.$refs['cropper'].rotate(rotationAngle);
    },
    saveLogo() {
      this.$refs['change-logo-modal'].hide();
    },
    changeLogo() {
      this.$refs['change-logo-modal'].show();
    },
    cancelAgree() {
      this.$refs["edit-style-modal"].hide();
    },
    agreeAll() {
      this.$refs["edit-style-modal"].hide();
    },
    editStyle() {
      this.$refs["edit-style-modal"].show();
    },
    editName() {
      this.edit_name = true;
    },
    saveName() {
      this.edit_name = false;
      console.log("ls");
      this.$toast.success({
        title: "Congratulation!",
        message: "Sender's info has changed!"
      });
    },
    cancelName() {
      this.edit_name = false;
    },
    editField() {
      this.edit_field = true;
    },
    saveField() {
      this.edit_field = false;
      this.$refs["edit-style-modal"].hide();
    },
    cancelField() {
      this.edit_field = false;
      this.$refs["edit-style-modal"].hide();
    }
  }
};
</script>

<style lang="scss">
@import "./CustomBranding.scss";
</style>

