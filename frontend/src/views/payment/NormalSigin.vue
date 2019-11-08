<template>
  <div class="app flex-row" ref="fileform">
    <div class="w-100 normal-sign">
      <div class="profile-header">
        <div class="user-happy">
          <!-- <img src="img/avatars/NoPath@2x.png" /> -->
          <div class="avatar-tag">
            <span>ST</span>
            <div class="change-avatar" v-on:click="changeAvatar()">Change</div>
              <img :src="option.img" />

          </div>
          <div class="ml-3">
            <h1 class="happy">{{ $t("home.title") }}<div>{{ $t("home.subtitle") }}</div></h1>
            <span class="comments">{{ $t("home.comments") }}</span>
          </div>
        </div>
        <div class="user-actions">
          <div class="user-action">
            <div class="action-numbers">0</div>
            <div class="action-type">{{ $t("home.user.action.typeLeft") }}</div>
          </div>
          <div class="user-action">
            <div class="action-numbers">1</div>
            <div class="action-type">{{ $t("home.user.action.typeRight") }}</div>
          </div>
          <div class="user-plan">
            <div>
              <img src="img/payment/upgrad.png" />
            </div>
            <div class="upgrade-content">
              <div class="upgrade-to">{{ $t("home.user.plan.title") }}</div>
              <div class="comments">{{ $t("home.user.plan.comments") }}</div>
              <b-link class="click-here" v-on:click="upgradePlan()">{{ $t("home.user.plan.click") }}</b-link>
            </div>
          </div>
        </div>
      </div>
      <hr class="mb-5" />
      <div class="row">
        <div class="col-md-3 pr-md-0" v-if ="false">
          <div class="content-card text-center">
            <div class="chart">
              <radial-progress-bar :diameter="160"
                       :completed-steps="completedSteps"
                       :total-steps="totalSteps"
                       stopColor="#e2ccc3"
                       startColor="#b59a90"
                       innerStrokeColor="#eeeae8"
                       :strokeWidth="8"
                       >
                <div class="completion-rate">{{ completedSteps }}/{{ totalSteps }}</div>
              </radial-progress-bar>
            </div>
            <div class="completion"> {{ $t("home.profile.title") }}</div>
            <div class="comments text-center mb-4">
              {{ $t("home.profile.comments") }}
            </div>
            <b-button variant="primary" class="mb-4" block>{{ $t("home.profile.button") }}</b-button>
          </div>
        </div>
        <div class="col-md-5 pr-md-0">
          <div class="content-card sign-signature">
            <div class="header">{{ $t("home.signature") }}</div>
            <div class="sign">Suzanne Thompson</div>
            <div class="text-right">
              <b-button variant="link" class="mr-1">{{ $t("home.replace") }}</b-button>|
              <b-button variant="link" class="ml-1">{{ $t("home.remove") }}</b-button>
            </div>
          </div>
          <div class="row">
            <div class="col-6 pr-1">
              <div class="content-card sign-signature">
                <div class="header">{{ $t("home.initials") }}</div>
                <div class="sign initials">ST</div>
                <div class="text-right">
                  <b-button variant="link" class="mr-1">{{ $t("home.replace") }}</b-button>|
                  <b-button variant="link" class="ml-1">{{ $t("home.remove") }}</b-button>
                </div>
              </div>
            </div>
            <div class="col-6 pl-1">
              <div class="content-card sign-signature">
                <div class="header">{{ $t("home.stamp") }}</div>
                <div class="stamp">
                  <b-button variant="link" block>
                    <UserIcon icon="upload.svg" />
                  </b-button>
                  <b-button variant="link" block class="upload-initials">{{ $t("home.upload") }}</b-button>
                  <!-- <img src="img/payment/stamp.png" /> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <FileUpload :files="uploadFiles" @addLandingFiles="addFiles"/>
        </div>
      </div>
    </div>
    <Message />
    
    <b-modal id="change-avatar-modal" ref="change-avatar-modal"
       hide-footer centered size="xl">
      <div class="change-avatar-modal">
        <div class="title">Change Profile Image</div>

        <div class="img-control-btns">
          <input 
            type="file" 
            ref="avatar_file"
            style="display: none"  
            @change="uploadImg($event, 1)" />
          <b-button variant="outline-primary" v-on:click="changeAvatarImg()">Other Image</b-button>
          <div>
            <i class="fa fa-rotate-left clickable-icon" @click="rotateLeft"/>
            <i class="fa fa-rotate-right clickable-icon mx-3" @click="rotateRight"/>
          </div>
        </div> 
        <!-- <vue-cropper
          ref="cropper"
          v-bind="options"
        /></vue-cropper> -->
        <div class="cut">
          <vue-cropper 
            ref="cropper" 
            :img="option.img" 
            :src="option.img"
            :output-size="option.size" 
            :output-type="option.outputType" 
            :info="true" :full="option.full" 
            :fixed="fixed"
            :can-move="option.canMove" 
            :can-move-box="option.canMoveBox" 
            :fixed-box="option.fixedBox" 
            :original="option.original"
            :auto-crop="option.autoCrop" 
            :auto-crop-width="option.autoCropWidth" 
            :auto-crop-height="option.autoCropHeight" 
            :center-box="option.centerBox"
            @real-time="realTime" :high="option.high"
            @img-load="imgLoad" mode="cover"
          ></vue-cropper>
        </div>
        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelAvatar()" >Cancel</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="saveAvatar()">Save changes</button>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import FileUpload from "../../components/FileUpload";
import Message from "../../components/Message";
import RadialProgressBar from 'vue-radial-progress';
// import VueCropper from 'vue-cropperjs';
import { VueCropper } from 'vue-cropper'
import { EventBus } from "../../config/event-bus";
import { mapGetters, mapState } from 'vuex'
import store from '../../store/store'
import { CHANGE_IMAGE_REQUEST, GET_USER_INFOR_REQUEST } from '../../store/actions.type'
import { CHANGE_IMAGE_SUCCESS } from '../../store/mutations.type'

import 'cropperjs/dist/cropper.css';
export default {
  name: "NormalSign",
  components: {
    FileUpload,
    UserIcon,
    Message,
    RadialProgressBar,
    VueCropper
  },
  data() {
    return {
      uploadFiles: [],
      files: [],
      cropped: null,
      avatar: "../../../../img/avatars/default.png",
      sections: [
        { label: 'Red section', value: 45, color: '#d4bcb2' },
      ],
      completedSteps: 3,
      totalSteps: 7,
      model: false,
      modelSrc: '',
      crap: false,
      previews: {},
      lists: [
        {
          img: 'https://qn-qn-kibey-static-cdn.app-echo.com/goodboy-weixin.PNG'
        },
        {
          img: 'https://avatars2.githubusercontent.com/u/15681693?s=460&v=4'
        }
      ],
      option: {
        img: this.test && this.test.user && this.test.user.avatar ? this.test.user.avatar :'../../../../img/avatars/default.png',
        size: 1,
        full: false,
        outputType: 'png',
        canMove: true,
        fixedBox: false,
        original: false,
        canMoveBox: true,
        autoCrop: true,
        autoCropWidth: 200,
        autoCropHeight: 150,
        centerBox: false,
        high: true
      },
      show: true,
      fixed: true,
      fixedNumber: [1, 2]
    }
  },
  created(){
    store.dispatch(GET_USER_INFOR_REQUEST)
    .then( res => {
      res 
        && res.user 
          && res.user.avatar ? this.option.img = res.user.avatar : this.option.img;
    })
    .catch( err => {

    });
  },
  mounted(){
  },
  computed: {
    ...mapGetters({
    })
  },
  methods: {
    changeAvatarImg() {
      this.$refs.avatar_file.click();
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files || !files.length) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        this.avatar = e.target.result;
      }.bind(this);
      reader.readAsDataURL(files[0]);
    },
    uploadImg(e, num) {
      var file = e.target.files[0]
      if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
        return false
      }
      var reader = new FileReader()
      reader.onload = (e) => {
        let data
        if (typeof e.target.result === 'object') {
          data = window.URL.createObjectURL(new Blob([e.target.result]))
        } else {
          data = e.target.result
        }
        if (num === 1) {
          this.option.img = data
        } else if (num === 2) {
          this.example2.img = data
        }
      }
      reader.readAsArrayBuffer(file)
    },
    rotate(rotationAngle) {
      this.$refs['cropper'].rotate(rotationAngle);
    },
    saveAvatar(type) {
      this.$refs.cropper.getCropData((data) => {
        store.dispatch(CHANGE_IMAGE_REQUEST, {avatar: data})
        .then( res => {
          this.option.img = res.user.avatar;
          this.$refs['change-avatar-modal'].hide();
        })
        .catch( err => {

        });
      })
    },
    changeAvatar() {
      this.$refs['change-avatar-modal'].show();
    },
    cancelAvatar() {
      this.$refs['change-avatar-modal'].hide();
    },
    upgradePlan() {
      this.$router.push({ path: "/payment/upgrade-plan" });
    },
    addFiles(files) {
      let self = this;
      files &&
        files.length > 0 &&
        files.map(file => {
          self.uploadFiles.push(file)
        });
      EventBus.$emit('FILES_UPLOAD', this.uploadFiles)
    },
    uploadImg(e, num) {
      var file = e.target.files[0]
      if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
        return false
      }
      var reader = new FileReader()
      reader.onload = (e) => {
        let data
        if (typeof e.target.result === 'object') {
          data = window.URL.createObjectURL(new Blob([e.target.result]))
        } else {
          data = e.target.result
        }
        if (num === 1) {
          this.option.img = data
        } else if (num === 2) {
          this.example2.img = data
        }
      }
      reader.readAsArrayBuffer(file)
    },
    imgLoad(msg) {
      console.log(msg)
    },
    rotateLeft() {
      this.$refs.cropper.rotateLeft()
    },
    rotateRight() {
      this.$refs.cropper.rotateRight()
    },
    realTime(data) {
      this.previews = data
    },
  }
};
</script>

<style lang="scss">
@import "./NormalSigin.scss";
@import "../document/AddDocuments.scss";
</style>

