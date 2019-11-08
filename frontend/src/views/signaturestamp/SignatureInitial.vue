<template>
  <div>
    <!-- HEADER -->
    <div class="row">
      <div class="col-12">
        <div class="content-card">
          <div class="content">
            <div class="d-flex align-items-center">
              <img src="img/icons/contract.svg" />
              <div class="ml-3">
                <div class="header">{{ $t("signature.headerSign") }}</div>
                <div class="comments">{{ $t('signature.commentSign') }}</div>
              </div>
            </div>
            <b-button variant="primary" v-on:click="showSignInitialModal">{{ $t('signature.button.addSign') }}</b-button>
          </div>
        </div>
      </div>
    </div>
    <!-- END HEADER -->

    <!-- LISTING SIGN INITIAL -->
    <div class="row">
      <div class="col-12">
        <div class="content-card sign-signature pb-0 px-md-3 px-2" v-for="signinitial in SIGNATURES" :key="signinitial.id">
          <div class="sign w-100 mb-2" :style="`font-family: ${signinitial.font_face}; font-size: ${signinitial.font_size}px; max-height: 130px; overflow: hidden;`">
            <div v-if="signinitial.initial_uploaded_url != null" class="col-sm-10 mx-auto no-wrap" style="max-height: 100px; text-align: left; overflow: hidden">
              <img class="pl-md-2 pl-auto" :src="signinitial.uploaded_url" alt="Uploaded Signature" style="vertical-align: top;  height: 100px; width: auto; max-width: 140px;" />
              <img class="float-right" :src="signinitial.initial_uploaded_url" alt="Uploaded Initial" style="position: relative;height: 80px; width: auto" />
            </div>
            <div v-else class="col-sm-10 mx-auto no-wrap">
              <span class="float-left px-4">{{signinitial.text}}</span>
              <span class="float-right px-4">{{signinitial.initial}}</span>
            </div>
          </div>

          <div class="actions" style="display: contents">
            <div class="action clickable-icon" v-on:click="onDefaultSignInitial(signinitial.id)">
              <i class="fa fa-pencil pr-2"></i> {{ $t('signature.button.setdefault') }}
            </div>
            <a class="action clickable-icon" style="text-decoration: none" :href="signinitial.uploaded_url" target="_blank">
              <i class="fa fa-download pr-2"></i> {{ $t('signature.button.download') }}
            </a>
            <div class="action clickable-icon" v-on:click="onDeleteSignInitial(signinitial.id)">
              <i class="fa fa-trash pr-2"></i> {{ $t('signature.button.delete') }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END LISTING SIGN INITIAL -->

    <!-- MODAL -->
    <b-modal 
      id="create-signature-modal" 
      ref="create-signature-modal" 
      hide-footer size="xl" 
      :no-close-on-backdrop="true" 
      :no-close-on-esc="true"
      :hide-header-close="true">
      <div class="create-signature-modal">
        <div class="title">
          {{ $t('signature.modal.titleSign') }}
        </div>
        <!-- Tab Menu -->
        <div class="row mb-md-4 mb-2">
          <div class="col-4 pr-0 pr-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Choose' ? 'primary' : 'outline-primary'"
              v-on:click="() => {
                config_val.navtab_index = 'Choose'
                drawing_data.signature.drawable=false
                drawing_data.initial.drawable=false
              }" 
              block
            >
              {{ $t('signature.modal.tab.choose') }}
            </b-button>
          </div>
          <div class="col-4 px-2 px-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Draw' ? 'primary' : 'outline-primary'"
              v-on:click="() => {
                config_val.navtab_index = 'Draw'
                drawing_data.signature.drawable=false
                drawing_data.initial.drawable=false
              }" 
              block
            >
              {{ $t('signature.modal.tab.draw') }}
            </b-button>
          </div>
          <div class="col-4 pl-0 pl-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Upload' ? 'primary' : 'outline-primary'"
              v-on:click="() => {
                config_val.navtab_index = 'Upload'
                drawing_data.signature.drawable=false
                drawing_data.initial.drawable=false
              }" 
              block
            >
              {{ $t('signature.modal.tab.upload') }}
            </b-button>
          </div>
        </div>
        <!-- End Tab Menu -->

        <!-- Create Form -->
        <div>
          <!-- Generate Signature & Initial -->
          <div class="row mb-4" v-if="config_val.navtab_index == 'Choose'">
            <div class="col px-sm-3 px-1">
              <hr />
              <div class="row mb-1">
                <!-- Select Language -->
                <div class="col-lg-2 col-12 pr-lg-1">
                  <UserSelect
                    v-bind:value="form_data.language"
                    v-bind:items="['English', 'Korean', 'Japanese']"
                    v-model="form_data.language"
                    @changeValue="onSyncLanguage"
                  />
                </div>
                <!-- Input Name -->
                <div class="col-lg-7 col-md-8 col-12 px-lg-1 pr-md-1">
                  <div class="form-group">
                    <input
                      type="text"
                      :class="{
                        'form-control': true,
                        'input-invalid': false,
                        'input-valid': true
                      }"
                      id="signature_text"
                      name="signature_text"
                      v-model="form_data.signature_text"
                      :placeholder="$t('signature.modal.placeholderFullname')"
                      :maxlength="25"
                      @changeValue="form_data.signature_text = $event"
                      v-on:keyup="onChangeName"
                    />
                    <p v-if="form_data.signature_text >= 25" class="validation-error text-left pl-2">
                      <!-- Validation Response Error -->
                    </p>
                  </div>
                </div>
                <!-- Input Initial -->
                <div class="col-lg-3 col-md-4 col-12 pl-md-1 ">
                  <div class="form-group">
                    <input
                      type="text"
                      :class="{
                        'form-control': true,
                        'input-invalid': false,
                        'input-valid': true
                      }"
                      id="initial"
                      name="initial"
                      v-model="form_data.initial"
                      :placeholder="$t('signature.modal.placeholderInitials')"
                      :maxlength="4"
                      @changeValue="form_data.initial = $event"
                    />
                    <p  v-if="form_data.initial >= 25" class="validation-error text-left pl-2">
                      <!-- Validation Response Error -->
                    </p>
                  </div>
                </div>

              </div>

            <!--
              <div class="row">
                <div class="col-sm mx-2" style="border: solid 1px #f0f3f5">
                  <div
                    :style="`font-family: ${config_val.languages[form_data.language][config_val.navtab_selected]}; font-size: ${parseInt(config_val.fontsize[form_data.language][config_val.navtab_selected])+20}px`"
                  >
                    <span class="p-2" ref="testSign">{{form_data.signature_text}}</span>
                  </div>
                  <img :src="generate_img.signature" alt="Signature Image">
                </div>
                <div class="col-sm mx-2" style="border: solid 1px #f0f3f5">
                  <div
                    :style="`font-family: ${config_val.languages[form_data.language][config_val.navtab_selected]}; font-size: ${parseInt(config_val.fontsize[form_data.language][config_val.navtab_selected])+20}px`"
                  >                    
                    <span class="p-2" ref="testInitial">{{form_data.initial}}</span>
                  </div>
                  <img :src="generate_img.initial" alt="Initial Image">
                </div>
              </div>
            -->

              <!-- Generated Signature & Initial -->
              <div class="signatures">  
                <div v-if="user_sign" class="sign-result d-flex-align-center checked">
                  <div class="row w-100">
                    <div class="col-10 d-flex-align-center px-md-5">
                      <div class="d-flex flex-column justify-content-between ">
                        <div class="signed-by">{{ $t('signature.modal.signby') }}</div>
                        <div 
                          class="signature-text" 
                          :style="`font-family: ${user_sign.font_face}; font-size: ${parseInt(user_sign.font_size)+10}px`"
                        >
                          {{user_sign.text}}
                        </div>
                        <div class="signed-id" hidden>C55F18C25</div>
                      </div>
                      <div class="right-border"></div>
                    </div>
                    <div class="col-2 pl-0 d-flex-align-center justify-content-center">
                      <div class="signature-text" 
                        :style="`font-family: ${user_sign.font_face}; font-size: ${parseInt(user_sign.font_size)+10}px`"
                      >
                        {{user_sign.initial}}
                      </div>
                    </div>
                  </div>
                  <div class="check-box" style="background: #ef634c;">
                    <span class="p-2" style="color: #FFF; font-family: Roboto; vertical-align: middle;">Default</span>
                  </div>
                </div>

                <div v-else v-for="(item, index) in config_val.languages[form_data.language]"
                  v-bind:key="index"                    
                  :class="{
                    'sign-result  d-flex-align-center': true,
                    'checked': index == config_val.navtab_selected
                  }"
                  v-on:click="onSelectSignature(index)"
                >
                    <div class="row w-100">
                      <div class="col-10 d-flex-align-center px-md-5">
                        <div class="d-flex flex-column justify-content-between ">
                          <div class="signed-by">{{ $t('signature.modal.signby') }}</div>
                          <!-- , -->
                          <div 
                            class="signature-text" 
                            :style="`font-family: ${item}; font-size: ${parseInt(config_val.fontsize[form_data.language][index])+10}px`"
                          >
                            <span class="p-2" :ref="`generatedSign-${index}`">{{form_data.signature_text}}</span>
                          </div>
                          <div class="signed-id" hidden>C55F18C25</div>
                        </div>
                        <div class="right-border"></div>
                      </div>
                      <div class="col-2 pl-0 d-flex-align-center justify-content-center">
                        <div class="signature-text" 
                          :style="`font-family: ${item}; font-size: ${parseInt(config_val.fontsize[form_data.language][index])+10}px`"
                        >
                          <span class="p-2" :ref="`generatedInitial-${index}`">{{form_data.initial}}</span>
                        </div>
                      </div>
                    </div>
                    <div class="check-box" v-if="config_val.navtab_selected == index">
                      <img src="img/icons/check-2.svg" />
                    </div>

                </div>
              </div>
              <!-- End Generated Signature & Initial -->
            </div>
          </div>

          <!-- Draw Signature & Initial -->
          <div v-else-if="config_val.navtab_index == 'Draw'" class="row mb-4">
            <div class="col-lg-8 col-md-6 col-12 pr-md-1 mb-3 mb-md-0">
              <div ref="container-for-signature" class="content-dash draw-signature">
                <div
                  class="draw-placeholder clickable-icon"
                  v-if="!drawing_data.signature.drawable"
                  v-on:click="drawing_data.signature.drawable=true"
                >
                  <img src="img/icons/pencil-draw.svg" />
                  <div class="mt-3">{{ $t('signature.modal.drawSign') }}</div>
                </div>
                <div class="canvas-container">
                  <drawing-board
                    id="drawboard-sign"
                    v-if="drawing_data.signature.drawable" 
                    v-bind:key="drawing_data.signature.index" 
                    :paramData="drawing_data.signature"
                    :drawSize="{
                      width: getContainerWidth('container-for-signature'),
                      height: getContainerHeight('container-for-signature'),
                    }"
                    ref="drawboard-sign"
                    class="draw-pan" 
                  />
                </div>
                <div v-if="drawing_data.signature.drawable">
                  <b-button variant="link" v-on:click="drawing_data.signature.drawable=false">
                    <i class="fa fa-undo" /> {{ $t('signature.button.reset') }}
                  </b-button>
                </div>
              </div>
            </div>
            
            <div class="col pl-md-1 pb-2">
              <div ref="container-for-initial" class="content-dash draw-initials">
                <div
                  class="draw-placeholder clickable-icon"
                  v-if="!drawing_data.initial.drawable"
                  v-on:click="drawing_data.initial.drawable=true"
                >
                  <img src="img/icons/pencil-draw.svg" />
                  <div class="mt-3">{{ $t('signature.modal.drawInitials') }}</div>
                </div>
                <div class="canvas-container">
                  <drawing-board 
                    id="drawboard-ini"
                    v-if="drawing_data.initial.drawable" 
                    v-bind:key="drawing_data.initial.index" 
                    :paramData="drawing_data.initial"
                    :drawSize="{
                      width: getContainerWidth('container-for-initial'),
                      height: getContainerHeight('container-for-initial'),
                    }"
                    ref="drawboard-initial"
                    class="draw-pan" 
                  />
                </div>
                <div v-if="drawing_data.initial.drawable">
                  <b-button variant="link" v-on:click="drawing_data.initial.drawable=false">
                    <i class="fa fa-undo" /> {{ $t('signature.button.reset') }}
                  </b-button>
                </div>
              </div>
            </div>
          </div>

          <!-- Upload Stamp -->
          <div v-else class="row mb-4">
            <div class="col-lg-8 col-md-6 col-12 pr-md-1 mb-3 mb-md-0">
              <ImageUpload 
                v-bind:files="signature_file" 
                v-bind:config_file="({
                  img: 'img/icons/upload.svg',
                  text: $t('signature.modal.uploadSign')
                })"
                v-on:toggle="toggleSignUpload($event)" 
              />
            </div>
            <div class="col pl-md-1 pb-2">
              <ImageUpload 
                v-bind:files="initial_file" 
                v-bind:config_file="({
                  img: 'img/icons/upload.svg',
                  text: $t('signature.modal.uploadInitials')
                })" 
                v-on:toggle="toggleInitialUpload($event)"
              />
            </div>
          </div>
          <hr />

          <div class="footer">
            <div class="summary">
              {{ $t('signature.modal.tncSign') }}
            </div>
            <div class="buttons">
              <b-button variant="link" v-on:click="hideSignInitialModal">
                <span>
                  <i class="fa fa-close"></i> {{ $t('signature.button.cancel') }}
                </span>
              </b-button>

              <div v-if="config_val.navtab_index == 'Choose'">
                <b-button v-if="user_sign" variant="primary" v-on:click="onUpdateSignInitial">Update</b-button>
                <b-button v-else variant="primary" v-on:click="onCreateSignInitial">{{ $t('signature.button.create') }}</b-button>
              </div>
              <div v-else-if="config_val.navtab_index == 'Draw'">
                <b-button variant="primary" v-on:click="onDrawSignInitial">{{ $t('signature.button.create') }}</b-button>
              </div>
              <div v-else>
                <b-button variant="primary" v-on:click="onUploadSignInitial">{{ $t('signature.button.create') }}</b-button>
              </div>

            </div>
          </div>

        </div>

      </div>
    </b-modal>
    <!-- END MODAL -->

  </div>
</template>

<script>
import Vue from 'vue'
import VueHtml2Canvas from 'vue-html2canvas'

import axios from 'axios'
import store from '../../store/store'
import { mapGetters, mapState } from 'vuex'
import { getOutSide } from '../../utils/http'
import JwtService from '../../mixins/jwt.service'
import { signation } from '../../mixins/signation'
import { 
  SIGNATURE_CREATE, 
  SIGNATURE_UPLOAD,
  SIGNATURE_GET, 
  SIGNATURE_UPDATE, 
  SIGNATURE_SOFTDELETE, 
  SIGNATURE_DESTROY, 
  AUTH_LOADING 
} from '../../store/actions.type'
import Stamp from '../../components/common/Stamp'
import UserSelect from '../../components/UserSelect'
import DrawingBoard from '../../components/DrawingBoard'
import ImageUpload from '../../components/common/ImageUpload'

Vue.use(VueHtml2Canvas)

export default {
  name: 'SignatureInitial',
  components: {
    Stamp, UserSelect, DrawingBoard, ImageUpload
  },
  mixins: [signation],
  data() {
    return {
      user_selected_sign: 0,
      user_sign: "",
      form_data: {
        signature_type: 'Choose',
        initial: '',
        signature_text: '',
        font_face: '',
        font_size: '',
        language: 'English',
        uploaded_url: ''
      },
      s_data: {
        signature_type: 'Choose',
        initial: '',
        signature_text: '',
        font_face: '',
        font_size: '',
        language: 'English',
        uploaded_url: '',
        initial_uploaded_url: ''
      },
      generate_data: {
        signature_type: 'Choose',
        initial: '',
        text: '',
        font_face: '',
        font_size: '',
        language: 'English',
        uploaded_url: ''
      },
      generate_img: {
        signature: '',
        initial: ''
      },

      drawing_data: {
        signature: {
          name: 'signature',
          drawing: '',
          drawable: false
        },
        initial: {
          name: 'initial',
          drawing: '',
          drawable: false
        }
      },

      signature_file: [],
      initial_file: [],
      uploadSignComponent: [],
      uploadInitialComponent: [],

      validator: {
        
      },
      config_val: {
        navtab_index: 'Choose',
        navtab_selected: '0',
        lang_short: 'gb',
        generated_show: false,
        languages: {
          /** English */
          English: ["Mrs Saint Delafield", "Badhead Typeface", "Banthers", "Connoisseurs", "Cutepunk_Regular", "Elrotex Basic", "GreatVibes-Regular", "KLSweetPineappleRegular", "Mightype Script", "pops_08_REGULAR", "somethingwild-Regular"],
          /** Korean */
          Korean: ["KimNamyun", "KCC-eunyoung", "Goyang", "SangSangFlowerRoad", "InkLipquid", "OTEnjoystoriesBA", "Dovemayo-Medium", "SDMiSaeng", "HSGyoulnoonkot", "Jeju Hallasan"],
          /** Japanese */
          Japanese: ["AsobiMemogaki-Regular-1-01", "crayon_1-1", "RiiPopkkR", "RiiT_F", "sjis_sp_setofont", "GenEiLateGoN_v2", "GenEiAntiquePv5-M"]
        },
        fontsize: {
          // English
          English: ["26", "29", "19", "29", "29", "14", "21", "29", "19", "18", "29"],
          // Korean
          Korean: ["27", "23", "21", "26", "23", "23", "16", "24", "17", "16"],
          // Korean: ["27", "35", "22", "38", "32", "36", "21", "34", "19", "21"],
          // Japanese
          Japanese: ["24", "23", "21", "26", "23", "23", "20"]
        },
      }
    }
  },
  created() {

  },
  computed: {
    ...mapGetters(['USER', 'SIGNATURES', 'loading', 'errors']),

    userSelectedSign: function () {
      return this.SIGNATURES.filter(usersign => usersign.id == this.user_selected_sign)[0];
    },
  },
  mounted() {
    var vm = this

    vm.getSignatures()
      .then(response => {
        store.dispatch(SIGNATURE_GET, response.data.data)
      })
      .catch(errors => {
        console.log(errors.response)
      });

    this.form_data.signature_text = this.USER.name
    this.form_data.initial = this.USER.first_name.substring(0,1) + this.USER.last_name.substring(0,1)
  },
  methods: {
    /** Create Signature */
    onCreateSignInitial: function () {
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      vm.getGeneratedBase64()
        .then(() => {
          vm.s_data = {
            signature_type: vm.config_val.navtab_index,
            initial: vm.form_data.initial,
            signature_text: vm.form_data.signature_text,
            font_face: vm.config_val.languages[vm.form_data.language][vm.config_val.navtab_selected],
            font_size: vm.config_val.fontsize[vm.form_data.language][vm.config_val.navtab_selected],
            language: vm.form_data.language,
            uploaded_url: vm.generate_img.signature,
            initial_uploaded_url: vm.generate_img.initial
          }
        })
        .then(() => {
          vm.createSignature(vm.s_data)
            .then(response => {
              store.dispatch(SIGNATURE_CREATE, response.data.data)
                .then(() => {
                  vm.$toast.success({
                    title: "Signature and Initial Created",
                    message: "User's signature and initial have created!"
                  })
            
                  store.dispatch(AUTH_LOADING, false)
                  vm.$refs["create-signature-modal"].hide()                  
                })
            })
            .catch(errors => {
              console.log(errors)
              store.dispatch(AUTH_LOADING, false)
            })
        })
        .catch(error => {
          console.log(error)
          store.dispatch(AUTH_LOADING, false)
        })
    },

    /** Draw Signature & Initial */
    onDrawSignInitial: function () {
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      let s_image = {
        sign_image: vm.getDataURLSign(),
        initial_image: vm.getDataURLInitial()
      }
      
      vm.uploadFiles(s_image)

      store.dispatch(AUTH_LOADING, false)
    },

    /** Upload Signature & Initial */
    toggleSignUpload: function (e) {
      if (!e) return

      let reader = new FileReader();
      reader.onload = e => this.uploadSignatureComponent = e.target.result
      reader.readAsDataURL(e)
    },
    toggleInitialUpload: function (e) {
      if (!e) return

      let reader = new FileReader();
      reader.onload = e => this.uploadInitialComponent = e.target.result
      reader.readAsDataURL(e)
    },
    onUploadSignInitial: function () {
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      let s_image = {
        sign_image: vm.uploadSignatureComponent,
        initial_image: vm.uploadInitialComponent
      }

      vm.uploadFiles(s_image)

      store.dispatch(AUTH_LOADING, false)
    },    
    uploadFiles: function (s_image) {
      var vm = this

      vm.uploadSignature(s_image)
        .then(response => {
          store.dispatch(SIGNATURE_UPLOAD, response.data.data)
            .then(() => {
              vm.$toast.success({
                title: "Signature and Initial Uploaded",
                message: "User's signature and initial have uploaded!"
              });

              vm.$refs["create-signature-modal"].hide();

              vm.drawing_data.signature.drawable=false
              vm.drawing_data.initial.drawable=false
            })
        })
        .catch(errors => {
          console.log(errors)
        });
    },

    /** Default Signature & Initial */
    onDefaultSignInitial: function (id) { // In progress
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      vm.defaultSignature(id)
        .then(response => {
          store.dispatch(SIGNATURE_UPDATE, response.data.data)
            .then(() => {
              vm.$toast.success({
                title: "Signature and Initial Default",
                message: "User's singature and initial have set to default!"
              })
            })
        })
        .catch(errors => {
          console.log(errors)
        })
      
      store.dispatch(AUTH_LOADING, false)
    },

    /** Delete Signature */
    onDeleteSignInitial: function (id) {
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      if(confirm("Do you really want to delete?")) {

        vm.softDeleteSignature(id)
          .then(response => {
            store.dispatch(SIGNATURE_SOFTDELETE, id)
              .then(() => {
                vm.$toast.warn({
                  title: "Signature and Initial Deleted",
                  message: "User's signature and initial have deleted!"
                })
              })
          })
          .catch(errors => {
            console.log(errors)
          })
      }

      store.dispatch(AUTH_LOADING, false)
    },
    
    /** Show Signature Data */
    onShowSignInitial: function (id) {
      this.user_selected_sign = id
      this.user_sign = this.userSelectedSign

      this.form_data.signature_text = this.user_sign.text
      this.form_data.initial = this.user_sign.initial || ''
      this.onSyncLanguage(this.user_sign.language)
      this.form_data.font_face = this.user_sign.font_face
      this.form_data.font_size = this.user_sign.font_size


      this.showSignInitialModal()
    },

    /** Download Image */
    onDownloadSignInitial: function () { },


    /** Utils */
    getGeneratedBase64: async function () {
      const el_sign = this.$refs[`generatedSign-${this.config_val.navtab_selected}`][0];
      const el_initial = this.$refs[`generatedInitial-${this.config_val.navtab_selected}`][0];
      // add option type to get the image version
      // if not provided the promise will return 
      // the canvas.
      const options = {
        type: 'dataURL',
        backgroundColor: null
      }
      this.generate_img.signature = await this.$html2canvas(el_sign, options)
      this.generate_img.initial = await this.$html2canvas(el_initial, options)
    },
    onSelectSignature: function (index) {
      this.config_val.navtab_selected = index
    },
    getDataURLSign: function () {
      return this.$refs["drawboard-sign"].getDataURL()
    },
    getDataURLInitial: function() {
      return this.$refs["drawboard-initial"].getDataURL()
    },
    getContainerWidth: function (cn) {
      return parseInt(this.$refs[cn].clientWidth) - 50;
    },
    getContainerHeight: function (cn) {
      return parseInt(this.$refs[cn].clientHeight) - 75;
    },
    onSyncLanguage: function(e) {
      this.form_data.language = e

      this.config_val.lang_short = this.form_data.language == 'English' ? 'gb' : this.form_data.language == 'Korean' ? 'kr' : 'jp'
    },
    showSignInitialModal: function () {
      if (!this.form_data.signature_text) {
        this.form_data.signature_text = this.USER.name
        this.form_data.initial = this.USER.first_name.substring(0,1) + this.USER.last_name.substring(0,1)
      }

      this.$refs["create-signature-modal"].show();
    },
    hideSignInitialModal: function () {
      this.clearSForm()
      this.$refs["create-signature-modal"].hide();
    },
    onChangeName() {
      var matches = this.form_data.signature_text.match(/\b(\w)/g); // ['J','S','O','N']
      this.form_data.initial = matches ? matches.join('') : ''; // JSON
    },
    clearSForm() {
      Object.assign(this.$data, this.$options.data.apply(this))
    },
    // Timeout Delay
    onDelay (fn, ms) {
      let timer = 0
      return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
      }
    }
  },
}
</script>

<style lang="scss" scoped>

</style>