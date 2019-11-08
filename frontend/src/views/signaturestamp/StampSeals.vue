<template>
  <div class="px-sm-1 px-2">
    <!-- HEADER -->
    <div class="row px-1">
      <div class="col-12 px-1">
        <div class="content-card">
          <div class="content">
            <div class="d-flex align-items-center">
              <img src="img/icons/stamp.svg" />
              <div class="ml-3">
                <div class="header">{{ $t("signature.headerStamp") }}</div>
                <div class="comments">{{ $t('signature.commentStamp') }}</div>
              </div>
            </div>
            <b-button variant="primary" v-on:click="showStampModal">{{ $t('signature.button.addStamp') }}</b-button>
          </div>
        </div>
      </div>
    </div>
    <!-- END HEADER -->

    <!-- LISTING STAMPS -->
    <div class="row px-1">
      <div class="col-sm px-1" v-for="stamp in STAMPS" :key="stamp.id">
        <div class="stamp-related">
        <div class="content-card signatures px-md-3 px-2">
          <div v-if="stamp.uploaded_url != ''" class="listing-stamp sign-result" style="overflow: hidden;">
            <img :src="stamp.uploaded_url" alt="Uploaded Image" />
          </div>
          <!-- List here -->
          <Stamp v-else 
            v-bind:key="stamp.id" 
              :keyItem="stamp.id" 
              :paramStamp="({
                stamp_type: stamp.stamp_type,
                stamp_title: stamp.title,
                stamp_text: stamp.text,
                font_face: stamp.font_face,
                font_size: stamp.font_size,
                uploaded_url: stamp.uploaded_url,
                etc: {
                  position: '',
                  company: ''
                }
              })"
              :configStamp="({
                language: stamp.language,
                str_length: '',
                display_name: ''
              })"
              :classChecked="''" 
              :btnClickHandler="() => { return }"
          />

          <div class="actions">
            <div class="action clickable-icon" v-on:click="onDefaultStamp(stamp.id)">
              <i class="fa fa-pencil pr-2"></i> {{ $t('signature.button.setdefault') }}
            </div>
            <a class="action clickable-icon" style="text-decoration: none" :href="stamp.uploaded_url" target="_blank">
            <!-- <div class="action clickable-icon" v-on:click="onDownloadStamp()"> -->
              <i class="fa fa-download pr-2"></i> {{ $t('signature.button.download') }}
            <!-- </div> -->
            </a>
            <div class="action clickable-icon" v-on:click="onDeleteStamp(stamp.id)">
              <i class="fa fa-trash pr-2"></i> {{ $t('signature.button.delete') }}
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
    <!-- END LISTING STAMPS -->
    
    <!-- MODAL -->
    <b-modal 
      id="create-stamp-modal" 
      ref="create-stamp-modal" 
      hide-footer size="xl" 
      :no-close-on-backdrop="true" 
      :no-close-on-esc="true"
      :hide-header-close="true">
      <div class="create-signature-modal">
        <div class="title">
          {{ $t('signature.modal.titleStamp') }}
        </div>
        <!-- Tab Menu -->
        <div class="row mb-md-4 mb-2">
          <div class="col-4 pr-0 pr-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Personnel' ? 'primary' : 'outline-primary'"
              v-on:click="onSyncTab('Personnel')" 
              block
            >
              {{ $t('signature.modal.tab.personnelSeal') }}
            </b-button>
          </div>
          <div class="col-4 px-2 px-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Corporate' ? 'primary' : 'outline-primary'"
              v-on:click="onSyncTab('Corporate')"
              block
            >
              {{ $t('signature.modal.tab.corporateSeal') }}
            </b-button>
          </div>
          <div class="col-4 pl-0 pl-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="config_val.navtab_index == 'Upload' ? 'primary' : 'outline-primary'"
              v-on:click="onSyncTab('Upload')"
              block
            >
              {{ $t('signature.modal.tab.upload') }}
            </b-button>
          </div>
        </div>
        <!-- End Tab Menu -->
        
        <!-- Generate Stamp Seal -->
        <div>
          <div class="row mb-4" v-if="config_val.navtab_index == 'Personnel'">
            <div class="col px-sm-3 px-1">
              <hr />
              <!-- Forms -->
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
                <!-- Select Title -->
                <div class="col-lg-2 col-sm-3 col-4 px-lg-1 pr-1" hidden>
                  <UserSelect
                    v-bind:value="form_data.title"
                    v-bind:items="['Mr', 'Mrs', 'Ms', 'Miss', 'Dr', 'Prof']"
                    v-model="form_data.title"
                    @changeValue="form_data.title = $event"
                  />
                </div>
                <!-- Input Name -->
                <div class="col-lg-8 col-12 px-lg-1 px-auto">
                  <div class="form-group">
                    <input
                      type="text"
                      :class="{
                        'form-control': true,
                        'input-invalid': (form_data.language == 'English' ? (form_data.stamp_text.length >= 25) : (form_data.stamp_text.length >= 9)),
                        'input-valid': (form_data.language == 'English' ? (form_data.stamp_text.length < 25) : (form_data.stamp_text.length < 9))
                      }"
                      id="stamp_text"
                      name="stamp_text"
                      v-model="form_data.stamp_text"
                      :placeholder="$t('signature.modal.placeholderStamp')"
                      :maxlength="form_data.language == 'English' ? 25 : 9"
                      @changeValue="form_data.stamp_text = $event"
                      v-on:keyup="onValidateInput"
                    />
                    <p v-if="validator.isError" class="validation-error text-left pl-2">
                      {{validator.text}}
                    </p>
                  </div>
                </div>

                <!-- Generate Button -->
                <div class="col-lg-2 col-12 pl-lg-1">
                  <b-button variant="primary" block v-on:click="onGenerateStamp">{{ $t('signature.button.generate') }}</b-button>
                </div>
              </div>

              <!-- Generated Stamp Seal -->              
              <div class="stamp-related">
                <div class="signatures scroll-box">
                  <div v-if="generate_data.length" class="row">
                    <div class="col-lg-4 col-12 my-3" 
                      v-bind:key="index"
                      v-for="(item, index) in generate_data">
                      <div :class="(index == config_val.navtab_selected?'checked':'')">
                        <span class="p-2" :ref="`generatedPers-${index}`">
                          <Stamp
                            v-bind:key="index"
                            :paramStamp="item"
                            :configStamp="{
                              language: item.language == 'English' ? 'gb' : item.language == 'Korean' ? 'kr' : 'jp'
                            }"
                            :classChecked="(index == config_val.navtab_selected?'checked':'')" :btnClickHandler="() => {config_val.navtab_selected = index}"
                          />
                        </span>
                        <div v-if="index == config_val.navtab_selected" class="check-box text-left" style="bottom: 50px; position: relative;">
                          <img src="img/icons/check-2.svg" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-4" v-else-if="config_val.navtab_index == 'Corporate'">
            <div class="col px-sm-3 px-1">
              <hr />
              <!-- Forms -->
              <div class="row mb-1">
                <!-- Select Language -->
                <div class="col-lg-2 col-md-4 pr-md-1">
                  <UserSelect
                    v-bind:value="form_data.language"
                    v-bind:items="['English', 'Korean', 'Japanese']"
                    v-model="form_data.language"
                    @changeValue="onSyncLanguage"
                  />
                </div>
                <!-- Input Name -->
                <div class="col-md pl-md-1">
                  <div class="form-group">
                    <input
                      type="text"
                      :class="{
                        'form-control': true,
                        'input-invalid': (form_data.language == 'English' ? (form_data.stamp_text.length >= 25) : (form_data.stamp_text.length >= 9)),
                        'input-valid': (form_data.language == 'English' ? (form_data.stamp_text.length < 25) : (form_data.stamp_text.length < 9))
                      }"
                      id="stamp_text"
                      name="stamp_text"
                      v-model="form_data.stamp_text"
                      :placeholder="$t('signature.modal.placeholderStamp')"
                      :maxlength="form_data.language == 'English' ? 25 : 9"
                      @changeValue="form_data.stamp_text = $event"
                      v-on:keyup="onValidateInput"
                    />
                    <p v-if="validator.isError" class="validation-error text-left pl-2">
                      {{validator.text}}
                    </p>
                  </div>
                </div>

                <!-- Generate Button -->
                <div class="col-lg-2 col-12 pl-lg-1">
                  <b-button variant="primary" block v-on:click="onGenerateStamp">{{ $t('signature.button.generate') }}</b-button>
                </div>
              </div>

              <!-- Generated Stamp Seal -->
              <div class="stamp-related">
                <div class="signatures scroll-box">
                  <div v-if="generate_data.length" class="row">
                    <div class="col-lg-4 col-12 my-3" 
                      v-bind:key="index"
                      v-for="(item, index) in generate_data">
                      <div :class="(index == config_val.navtab_selected?'checked':'')">
                        <span class="p-2" :ref="`generatedCorp-${index}`">
                          <Stamp
                            v-bind:key="index"
                            :paramStamp="item"
                            :configStamp="{
                              language: item.language == 'English' ? 'gb' : item.language == 'Korean' ? 'kr' : 'jp'
                            }"
                            :classChecked="(index == config_val.navtab_selected?'checked':'')" :btnClickHandler="() => {config_val.navtab_selected = index}"
                          />
                        </span>
                        <div v-if="index == config_val.navtab_selected" class="check-box text-left" style="bottom: 50px; position: relative;">
                          <img src="img/icons/check-2.svg" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Upload Stamp -->
          <div class="row mb-4" v-else>
            <div class="col">
              <hr />
              <!-- Forms -->
              <ImageUpload 
                v-bind:files="stamp_file" 
                v-bind:config_file="({
                  img: 'img/icons/upload.svg',
                  text: $t('signature.modal.uploadStamp')
                })"
                v-on:toggle="toggleStampUpload($event)" 
              />
            </div>
          </div>
        
          <hr />

          <div class="footer">
            <div class="summary">
              {{ $t('signature.modal.tncStamp') }}
            </div>
            <div class="buttons">
              <b-button variant="link" v-on:click="hideStampModal">
                <span>
                  <i class="fa fa-close"></i> {{ $t('signature.button.cancel') }}
                </span>
              </b-button>

              <div v-if="config_val.navtab_index != 'Upload'">
                <b-button variant="primary" v-on:click="onCreateStamp">{{ $t('signature.button.create') }}</b-button>
              </div>
              <div v-else>
                <b-button variant="primary" v-on:click="onUploadStamp">{{ $t('signature.button.create') }}</b-button>
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
import { html2canvas } from 'vue-html2canvas'
import axios from 'axios'
import store from '../../store/store'
import { mapGetters, mapState } from 'vuex'
import { stampseal } from '../../mixins/stampseal'
import Stamp from '../../components/common/Stamp'
import UserSelect from '../../components/UserSelect'
import ImageUpload from '../../components/common/ImageUpload'
import CustomLoader from '../../components/common/CustomLoader'
import { 
  STAMP_GET, 
  STAMP_CREATE,
  STAMP_UPLOAD,
  STAMP_UPDATE, 
  STAMP_SOFTDELETE, 
  SIGNATURE_DESTROY, 
  AUTH_LOADING 
} from '../../store/actions.type'

export default {
  name: 'StampSeals',
  components: {
    UserSelect, Stamp, ImageUpload
  },
  mixins: [stampseal],
  data() {
    return {
      user_selected_stamp: 0,
      user_stamp: '',

      form_data: {
        stamp_type: 'Personnel',
        title: 'Mr',
        stamp_text: '',
        font_face: '',
        font_size: '',
        language: 'English',
        uploaded_url: ''
      },
      s_data: {
        stamp_type: 'Personnel',
        title: '',
        text: '',
        font_face: '',
        font_size: '',
        language: 'English',
        uploaded_url: ''
      },
      generate_data: [],
      generate_img: '',

      stamp_file: [],
      uploadStampImg: {},

      validator: {
        isError: false,
        text: ''
      },

      config_val: {
        navtab_index: 'Personnel',
        navtab_selected: 0,
        lang_short: 'gb',
        languages: {
          /** English */
          English: ["Mrs Saint Delafield", "Badhead Typeface", "Banthers", "Connoisseurs", "Cutepunk_Regular", "Elrotex Basic", "GreatVibes-Regular", "KLSweetPineappleRegular", "Mightype Script", "pops_08_REGULAR", "somethingwild-Regular"],
          /** Korean */
          Korean: ["KimNamyun", "KCC-eunyoung", "Goyang", "SangSangFlowerRoad", "InkLipquid", "Dovemayo-Medium", "SDMiSaeng", "HSGyoulnoonkot", "Jeju Hallasan"],
          /** Japanese */
          Japanese: ["crayon_1-1", "RiiPopkkR", "RiiT_F", "sjis_sp_setofont", "GenEiLateGoN_v2", "GenEiAntiquePv5-M"]
        },
        fontsize: {
          // English
          English: ["26", "29", "19", "29", "29", "14", "21", "29", "19", "18", "29"],
          // Korean
          Korean: ["27", "33", "22", "31", "30", "21", "34", "19", "21"],
          // Japanese
          Japanese: ["22", "18", "20", "21", "20", "20"]
        }
      }
    }
  },
  created() {

  },
  computed: {
    ...mapGetters(['USER', 'STAMPS', 'loading', 'errors']),
    userSelectedSign: function () {
      return this.STAMPS.filter(userstamp => userstamp.id == this.user_selected_stamp)[0]
    }
  },
  mounted() {
    var vm = this

    vm.getStamps()
      .then(response => {
        store.dispatch(STAMP_GET, response.data.data)
      })
      .catch(errors => {
        console.log(errors.response)
      });
  },
  methods: {
    /** Create Personnel Stamp */
    onCreateStamp: function () { 
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      vm.getGeneratedBase64()
        .then(() => {
          let getGenerated = vm.generate_data[vm.config_val.navtab_selected]
          let shortformLang = getGenerated.language == 'English' ? 'gb' : getGenerated.language == 'Korean' ? 'kr' : 'jp'

          vm.s_data = {
            stamp_type: getGenerated.stamp_type,
            title: getGenerated.title,
            stamp_text: getGenerated.stamp_text,
            font_face: getGenerated.font_face,
            font_size: getGenerated.font_size,
            language: shortformLang,
            uploaded_url: vm.generate_img
          }

          console.log(vm.s_data)
          store.dispatch(AUTH_LOADING, false)
        })
        .then(() => {
          vm.createStamp(vm.s_data)
            .then(response => {
              store.dispatch(STAMP_CREATE, response.data.data)
                .then(() => {
                  vm.$toast.success({
                    title: "Stamp Created",
                    message: "User's stamp have created!"
                  });

                  vm.$refs["create-stamp-modal"].hide();
                  store.dispatch(AUTH_LOADING, false)
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

    /** Upload Stamp */
    toggleStampUpload: function (e) { 
      if (!e) return

      let reader = new FileReader();
      reader.onload = e => this.uploadStampImg = e.target.result
      reader.readAsDataURL(e)
    },
    onUploadStamp: function () { 
      var vm = this
      
      store.dispatch(AUTH_LOADING, true)

      let s_image = {
        image: this.uploadStampImg
      }

      vm.uploadStamp(s_image)
        .then(response => {
          store.dispatch(STAMP_UPLOAD, response.data.data)
            .then(() => {
              vm.$toast.success({
                title: "Stamp Uploaded",
                message: "User's stamp have uploaded!"
              });

              vm.$refs["create-stamp-modal"].hide();
              store.dispatch(AUTH_LOADING, false)
            })
        })
        .catch(errors => {
          console.log(errors)
          store.dispatch(AUTH_LOADING, false)
        })
    },

    /** Show Stamp */
    onShowStamp: function () { },

    /** Update Stamp */
    onDefaultStamp: function () { 
      var vm = this

      store.dispatch(AUTH_LOADING, true)

      vm.defaultStamp(id)
        .then(response => {
          store.dispatch(STAMP_UPDATE, response.data.data)
            .then(() => {
              vm.$toast.success({
                title: "Stamp Default",
                message: "User's stamp have set to default!"
              })
            })
        })
        .catch(errors => {
          console.log(errors)
        })
      
      store.dispatch(AUTH_LOADING, false)
    },

    /** Delete Stamp */
    onDeleteStamp: function (id) { 
      var vm = this

      if (confirm("Do you really want to delete?")) {
        store.dispatch(AUTH_LOADING, true)

        vm.softDeleteStamp(id)
          .then(response => {
            store.dispatch(STAMP_SOFTDELETE, id)
              .then(() => {
                vm.$toast.warn({
                  title: "Stamp Deleted",
                  message: "User's stamp seal have deleted!"
                })

                store.dispatch(AUTH_LOADING, false)
              })
          })
          .catch(errors => {
            console.log(errors)
            store.dispatch(AUTH_LOADING, false)
          })
      }
    },

    /** Download Stamp */
    onDownloadStamp: function () {},


    
    
    /** Utils */
    getGeneratedBase64: async function () {
      let refsGet = this.config_val.navtab_index == "Personnel" ? 'generatedPers' : 'generatedCorp'
      
      
      let el_sign = this.$refs[`${refsGet}-${this.config_val.navtab_selected}`][0]
      el_sign.style.border = 'none';
      let tempref = this.$refs[`${refsGet}-${this.config_val.navtab_selected}`][0].firstChild.firstChild
      tempref.style.border = "0"
      
      // add option type to get the image version
      // if not provided the promise will return 
      // the canvas.
      const options = {
        type: 'dataURL',
        backgroundColor: null,
      }
      // var mycanvas = document.createElement("canvas");
      // mycanvas.id = "mycanvas";
      // document.body.appendChild(mycanvas);
      
      this.generate_img = await this.$html2canvas(el_sign, options)

      console.log(this.generate_img)
    },
    onGenerateStamp: async function () {
      var vm = this
      
      store.dispatch(AUTH_LOADING, true)
      
      // Clear old generated_data
      vm.generate_data.splice(0, vm.generate_data.length)
      vm.config_val.navtab_selected = 0
      await vm.$nextTick() // wait to clear

      let lang = vm.form_data.language
      let langFace = vm.config_val.languages[lang]
      let langSize = vm.config_val.fontsize[lang]

      langFace.forEach((face, index) => {
          let generate = {
            stamp_type: vm.config_val.navtab_index,
            title: vm.form_data.title,
            stamp_text: vm.form_data.stamp_text,
            font_face: face,
            font_size: langSize[index],
            language: lang,
            uploaded_url: '',
            etc: {
              position: '',
              company: ''
            }
          }
          vm.generate_data.push(generate)
        })
      
      store.dispatch(AUTH_LOADING, false)
    },
    onSyncLanguage: function(e) {
      this.form_data.language = e

      this.config_val.lang_short = this.form_data.language == 'English' ? 'gb' : this.form_data.language == 'Korean' ? 'kr' : 'jp'
    },
    onSyncTab : function (e) {
      this.config_val.navtab_index = e

      this.clearGenerated()
    },
    onValidateInput: function () {
      if (this.form_data.language == 'English') {
        if (this.form_data.stamp_text.length >= 25) {
          this.validator.isError = true
          this.validator.text = 'Only can generate less than 25 characters'
        }
        else {
          this.validator.isError = false
          this.validator.text = ''
        }  
      }
      else if (this.form_data.language == 'Korean' || this.form_data.language == 'Japanese') {
        if (this.form_data.stamp_text.length >= 9) {
          this.validator.isError = true
          this.validator.text = 'Only can generate less than 9 characters'
        }
        else {
          this.validator.isError = false
          this.validator.text = ''
        }
      }
      else {
        this.validator.isError = false
        this.validator.text = ''
      }      
    },
    showStampModal: function () {
      this.$refs["create-stamp-modal"].show()  
    },
    hideStampModal: function () {
      this.$refs["create-stamp-modal"].hide()
      this.clearGenerated()
    },
    clearSForm() {
      Object.assign(this.$data, this.$options.data.apply(this))
    },
    clearGenerated() {
      this.config_val.navtab_selected = 0
      this.form_data.stamp_text = ''

      this.generate_data.splice(0, this.generate_data.length)
    },
    // Timeout Delay
    onDelay (fn, ms) {
      let timer = 0
      return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
      }
    }
  }
}
</script>

<style lang="scss">
.scroll-box {
  max-height: 350px;
  overflow-y: auto;
}
</style>