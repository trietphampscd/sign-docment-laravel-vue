<template>
  <div class="app flex-row" ref="fileform">

    <custom-loader :own-loading="false" :no-sidebar="true"></custom-loader>

    <div class="w-100 sign-stamp">
      <h1>{{ $t("signature.title") }}</h1>
      <hr class="mb-4" />
      <div class="row">
        <!-- SIGNATURES AND INITIALS LISTING -->
        <div class="col-md-6 pr-md-0">          
          <SignatureInitial />
          <!-- <div class="content-card">
            <div class="content">
              <div class="d-flex align-items-center">
                <img src="img/icons/contract.svg" />
                <div class="ml-3">
                  <div class="header">{{ $t("signature.headerSign") }}</div>
                  <div
                    class="comments">{{ $t("signature.commentSign") }}</div>
                </div>
              </div>
              <b-button variant="primary" v-on:click="createSignature()">
                {{ $t("signature.button.addSign") }}
              </b-button>
            </div>
          </div>

          <div
            class="content-card sign-signature pb-0"
            v-for="(item, index) in SIGNATURES"
            :key="index"
          >
            <div class="sign">
              <span>{{item.text}}</span>
              <span class="ml-4">{{item.initial}}</span>
            </div>
            <div class="actions">
              <div class="action clickable-icon">
                <i class="fa fa-pencil pr-2"></i> {{ $t('signature.button.edit') }}
              </div>
              <div class="action clickable-icon">
                <i class="fa fa-download pr-2"></i> {{ $t('signature.button.download') }}
              </div>
              <div class="action clickable-icon">
                <i class="fa fa-trash pr-2"></i> {{ $t('signature.button.delete') }}
              </div>
            </div>
          </div> -->
        </div>
        <!-- END SIGNATURES AND INITIALS LISTING -->

        <div class="col-md-6">
          <StampSeals />
        <!--
          <div class="content-card">
            <div class="content">
              <div class="d-flex align-items-center">
                <img src="img/icons/stamp.svg" />
                <div class="ml-3">
                  <div class="header">{{ $t("signature.headerStamp") }}</div>
                  <div
                    class="comments"
                  >{{ $t('signature.commentStamp') }}</div>
                </div>
              </div>
              <b-button variant="primary" v-on:click="createStamp()">{{ $t('signature.button.addStamp') }}</b-button>
            </div>
          </div>

                <div class="signatures pl-sm-2 pr-sm-2 px-auto">
                  <div class="row">
                    <div class="col-sm-6 col-12 px-sm-1 pl-sm-1 pr-md-2 pr-sm-0 p-auto" v-for="(item, index) in STAMPS" :key="index" >
                      <div class="content-card">
                        <Stamp :key="index" v-bind:keyItem="index" :stampItem="getNewItem(item)" :fontFamily="item.font_face" :fontSize="(!fontsize_change?item.font_size:'25px')" purpose="Corporate" :lang="item.language" :classChecked="''" :btnClickHandler="() => { return false }" :styleCustom="'max-height: 180px !important; min-height: 0; max-width: initial; width: 100%; height: auto; padding: 0 !important; margin: auto'" />

                          
                        <div class="actions px-3 py-1">
                          <div class="action clickable-icon">
                            <i class="fa fa-pencil pr-2"></i>&nbsp;{{ $t('signature.button.edit') }}
                          </div>
                          <div class="action clickable-icon">
                            <i class="fa fa-download pr-2"></i>&nbsp;{{ $t('signature.button.download') }}
                          </div>
                          <div class="action clickable-icon">
                            <i class="fa fa-trash pr-2"></i>&nbsp;{{ $t('signature.button.delete') }}
                          </div>
                        </div>                      
                      </div>  
                    </div>
                  </div>
                </div>
          -->
        </div>

      </div>
    </div>

    
    <!-- Signature MODAL -->
    <b-modal id="create-signature-modal" ref="create-signature-modal" hide-footer centered size="xl">
      <div class="create-signature-modal">
        <div class="title">{{ $t('signature.modal.titleSign') }}</div>
        <div class="row mb-md-4 mb-2">
          <div class="col-4 pr-0 pr-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="sign_type == 1?'primary':'outline-primary'"
              v-on:click="sign_type = 1"
              block
            >{{ $t('signature.modal.tab.choose') }}</b-button>
          </div>
          <div class="col-4 px-2 px-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="sign_type == 0?'primary':'outline-primary'"
              v-on:click="sign_type = 0"
              block
            >{{ $t('signature.modal.tab.draw') }}</b-button>
          </div>
          <div class="col-4 pl-0 pl-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="sign_type == 2?'primary':'outline-primary'"
              v-on:click="sign_type = 2"
              block
            >{{ $t('signature.modal.tab.upload') }}</b-button>
          </div>
        </div>

        <div class="row mb-4" v-if="sign_type==0">
          <div class="col-12 col-md-8 mb-3 mb-md-0">
            <div class="content-dash draw-signature">
              <div
                class="draw-placeholder clickable-icon"
                v-if="!drawable"
                v-on:click="drawable=true"
              >
                <img src="img/icons/pencil-draw.svg" />
                <div class="mt-3">{{ $t('signature.modal.drawSign') }}</div>
              </div>
              <drawing-board v-if="drawable" class="draw-pan"></drawing-board>
            </div>
            <div class="reset">
              <b-button variant="link" v-on:click="drawable=false">
                <i class="fa fa-undo" /> {{ $t('signature.button.reset') }}
              </b-button>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="content-dash draw-initials">
              <div
                class="draw-placeholder clickable-icon"
                v-if="!drawableInitial"
                v-on:click="drawableInitial=true"
              >
                <img src="img/icons/pencil-draw.svg" />
                <div class="mt-3">{{ $t('signature.modal.drawInitials') }}</div>
              </div>              
              <drawing-board v-if="drawableInitial" class="draw-pan"></drawing-board>
            </div>
            <div class="reset">
              <b-button variant="link" v-on:click="drawableInitial=false">
                <i class="fa fa-undo" /> {{ $t('signature.button.reset') }}
              </b-button>
            </div>
          </div>
        </div>

        <div class="row mb-4" v-if="sign_type==1">
          <div class="col-12">
            <hr class="w-100" />
            <div class="row">
              <div class="col-12 col-lg-2 pr-lg-1 pr auto">
                <UserSelect
                  v-bind:value="language"
                  v-bind:items="['English', 'Korean', 'Japanese']"
                  @changeValue="language = $event"
                />
              </div>
              <div class="col-8 col-lg-6 pl-lg-1 pr-1">
                <div class="form-group">
                  <input type="text" 
                    :class="{
                      'form-control': true,
                      'input-invalid': (signature.length==14),
                      'input-valid': (signature.length<14),
                    }" 
                    @changeValue="signature=$event"
                    id="signature" :placeholder="$t('signature.modal.placeholderFullname')"
                    name="signature" v-model="signature" v-on:keyup="changeName"
                    :maxlength="14"
                  />
                  <p class="validation-error text-left pl-2" v-if="signature.length==14">{{ validator.text }}</p>
                </div>
              </div>
              <div class="col-4 pl-1">
                <div class="form-group">
                  <input
                    type="text"
                    :class="{
                      'form-control': true,
                      'input-invalid': (initials.length==4),
                      'input-valid': (initials.length<4),
                    }" 
                    id="Initials"
                    :placeholder="$t('signature.modal.placeholderInitials')"
                    name="initials"
                    v-model="initials"
                    :maxlength="4"
                  />
                  <p class="validation-error text-left pl-2" v-if="initials.length==4">{{ validator.text }}</p>
                </div>
              </div>
            </div>
            <div class="signatures">
              <div v-for="(item, index) in signature_types[language=='English'?0:(language=='Korean'?2:1)]" :key="index"
                class="sign-result d-flex-align-center"  
                v-bind:class="index==selected_no?'checked':''"
                v-on:click="selected_no = index"
              >
                <div class="row w-100">
                  <div class="col-10 pr-2 d-flex-align-center pl-40px">
                    <div class="d-flex flex-column justify-content-between ">
                      <div class="signed-by">{{ $t('signature.modal.signby') }}</div>
                      <!-- , -->
                      <div class="signature-text" v-bind:style="{fontFamily: item ,  fontSize: (fontsize_change?fontsize_list[language=='English'?0:(language=='Korean'?2:1)][index]:'29px')}">{{signature}}</div>
                      <div class="signed-id">C55F18C25</div>
                    </div>
                    <div class="right-border"></div>
                  </div>
                  <div class="col-2 pl-0 d-flex-align-center justify-content-center">
                    <div class="signature-text" v-bind:style="{fontFamily: item ,  fontSize: (fontsize_change?fontsize_list[language=='English'?0:(language=='Korean'?2:1)][index]:'29px')}">{{initials}}</div>
                  </div>
                </div>
                <div class="check-box" v-if="selected_no == index">
                  <img src="img/icons/check-2.svg" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mb-4" v-if="sign_type==2">
          <div class="col-12 col-md-8 mb-4 mb-md-0">
            <ImageUpload :files="sign_file" 
              :config_file="({
                text: $t('signature.modal.uploadSign')
              })
            " />

            <!-- <div class="content-dash draw-signature">
              <div class="draw-placeholder">
                <img src="img/sign/sign.png" class="sign-img" />
              </div>
            </div>
            <div class="reset">
              <b-button variant="link">{{ $t('signature.button.replace') }}</b-button>
              <span class="mx-2">|</span>
              <b-button variant="link">{{ $t('signature.button.remove') }}</b-button>
            </div> -->

          </div>
          <div class="col-12 col-md-4">
            <ImageUpload :files="initials_file" 
              :config_file="('config_file', {
                text: $t('signature.modal.uploadInitials')
              })
            " />
            <!-- <div class="content-dash draw-initials">
              <input
                type="file"
                ref="upload_initials_file"
                style="display: none"
                @change="onInitialsFileChange"
              />
              <div
                class="draw-placeholder clickable-icon"
                v-on:click="selectInitials()"
                v-if="initials_file.length<=0"
              >
                <img src="img/icons/upload.svg" />
                <div class="mt-3">{{ $t('signature.modal.uploadInitials') }}</div>
              </div>
            </div> -->
          </div>
        </div>
        <hr />
        <div class="footer">
          <div class="summary">
            {{ $t('signature.modal.tncSign') }}
          </div>
          <div class="buttons">
            <b-button variant="link" v-on:click="hideSignatureModal">
              <span>
                <i class="fa fa-close"></i> {{ $t('signature.button.cancel') }}
              </span>
            </b-button>
            <b-button variant="primary" v-on:click="hideSignatureModal">{{ $t('signature.button.create') }}</b-button>
          </div>
        </div>
      </div>
    </b-modal>

    <!-- STAMP MODAL -->
    <b-modal id="create-stamp-modal" ref="create-stamp-modal" hide-footer centered size="xl">
      <div class="create-signature-modal">
        <div class="title">{{ $t('signature.modal.titleStamp') }}</div>
        <div class="row mb-md-4 mb-2">
          <div class="col-4 pr-0 pr-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="stamp_type == 0?'primary':'outline-primary'"
              v-on:click="stamp_type = 0"
              block
            >{{ $t('signature.modal.tab.personnelSeal') }}</b-button>
          </div>
          <div class="col-4 px-2 px-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="stamp_type == 1?'primary':'outline-primary'"
              v-on:click="stamp_type = 1"
              block
            >{{ $t('signature.modal.tab.corporateSeal') }}</b-button>
          </div>
          <div class="col-4 pl-0 pl-md-3">
            <b-button
              class="stamp-tab-nav"
              :variant="stamp_type == 2?'primary':'outline-primary'"
              v-on:click="stamp_type = 2"
              block
            >{{ $t('signature.modal.tab.upload') }}</b-button>
          </div>
        </div>

        <!-- Personnel Stamp -->
        <div class="row mb-4" v-if="stamp_type==0">
          <div class="col-12">
            <hr class="w-100" />
            <div class="row mb-1">
              <div class="col-12 col-lg-2 pr-lg-1 px-auto">
                <!-- <UserSelect
                  v-bind:value="stamp_language"
                  v-bind:items="['English', 'Korean', 'Japanese']"
                  @changeValue="stamp_language = $event"
                /> -->
              </div>
              <div class="col-12 col-sm-4 col-lg-2">
                <div class="form-group">
                  <UserSelect 
                    v-bind:value="title"
                    :items="['Mr.', 'Mrs.', 'Ms.', 'Miss', 'Dr.', 'Prof.']"
                    @changeValue="title = $event"
                    aria-placeholder="Title"
                  />
                </div>
              </div>
              <div class="col-12 col-sm-8 col-lg-6 px-lg-1 pr-md-auto pl-md-1 px-auto">
                <div class="form-group">
                  <input
                    type="text"
                    :class="{
                      'form-control': true,
                      'input-invalid': (stamp_name.length==14),
                      'input-valid': (stamp_name.length<14),
                    }" 
                    id="stamp_name"
                    :placeholder="$t('signature.modal.placeholderStamp')"
                    name="stamp_name"
                    v-model="stamp_name"
                    :maxlength="14"
                  />
                  <p class="validation-error text-left pl-2" v-if="stamp_name.length==14">{{ validator.text }}</p>
                </div>
              </div>
              <div class="col-12 col-lg-2">
                <b-button variant="primary" block v-on:click="creatStamp">{{ $t('signature.button.create') }}</b-button>
              </div>
            </div>
            <div class="signatures px-lg-5 px-2">
              <div class="row" v-if="stamp_language == 'English' && stamp_types.name">
                <div class="col-lg-4 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index" >
                  <Stamp v-bind:keyItem="index" :stampItem="stamp_types" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)][index]:'25px')" purpose="Personnel" lang="gb" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />

                  <!-- <div class="sign-result" v-bind:class="index==selected_no?'checked':''" v-on:click="selected_no = index">
                    <img src="img/payment/stamp-2x.png" />
                    <div class="check-box" v-if="index == selected_no">
                      <img src="img/icons/check-2.svg" />
                    </div>
                  </div> -->

                </div>
                <!-- <i class="fa fa-long-arrow-left left-button clickable-icon" />
                <i class="fa fa-long-arrow-right right-button clickable-icon" /> -->
              </div>
              <div class="row" v-if="stamp_language == 'Korean' && stamp_kr_types.name">
                <div class="col-lg-4 col-md-6 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index">
                  <Stamp v-bind:keyItem="index" :stampItem="stamp_kr_types" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)][index]:'25px')" :purpose="'Personnel'" lang="kr" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />
                </div>
              </div>
              <div class="row" v-if="stamp_language == 'Japanese' && stamp_jp_types.name">
                <div class="col-lg-4 col-md-6 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index">
                  <Stamp v-bind:keyItem="index" :stampItem="stamp_jp_types" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)][index]:'25px')" :purpose="'Personnel'" lang="jp" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Corporate Stamp -->
        <div class="row mb-4" v-if="stamp_type==1">
          <div class="col-12">
            <hr class="w-100" />
            <div class="row mb-1">
              <div class="col-12 col-sm-4 col-lg-2">
                <UserSelect
                  v-bind:value="stamp_language"
                  v-bind:items="['English', 'Korean', 'Japanese']"
                  @changeValue="stamp_language = $event"
                />
              </div>
              <div class="col-12 col-sm-4 p-sm-0 col-lg-8">
                <div class="form-group">
                  <input
                    type="text"
                    :class="{
                      'form-control': true,
                      'input-invalid': (stamp_name.length==14),
                      'input-valid': (stamp_name.length<14),
                    }" 
                    id="stamp_name"
                    :placeholder="$t('signature.modal.placeholderStamp')"
                    name="stamp_name"
                    v-model="stamp_name"
                    :maxlength="14"
                  />
                  <p class="validation-error text-left pl-2" v-if="stamp_name.length==14">{{ validator.text }}</p>
                </div>
              </div>
              <div class="col-12 col-sm-4 col-lg-2">
                <b-button variant="primary" block v-on:click="creatStamp2">{{ $t('signature.button.create') }}</b-button>
              </div>
            </div>
            
            <div class="signatures">
              <div class="row" v-if="stamp_language == 'English' && stamp_corporate.name">
                <div class="col-lg-4 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index" >
                  <Stamp :key="index" v-bind:keyItem="index" :stampItem="stamp_corporate" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[0][index]:'25px')" purpose="Corporate" lang="gb" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />
                </div>
              </div>
              <div class="row" v-if="stamp_language == 'Korean' && stamp_kr_corporate.name">
                <div class="col-lg-4 col-md-6 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index" >
                  <Stamp :key="index" v-bind:keyItem="index" :stampItem="stamp_kr_corporate" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[2][index]:'25px')" purpose="Corporate" lang="kr" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />
                </div>
              </div>
              <div class="row" v-if="stamp_language == 'Japanese' && stamp_jp_corporate.name">
                <div class="col-lg-4 col-md-6 col-12 my-3" v-for="(item, index) in signature_types[stamp_language=='English'?0:(stamp_language=='Korean'?2:1)]" :key="index" >
                  <Stamp :key="index" v-bind:keyItem="index" :stampItem="stamp_jp_corporate" :fontFamily="item" :fontSize="(!fontsize_change?fontsize_list[1][index]:'25px')" purpose="Corporate" lang="jp" :classChecked="(index == selected_no?'checked':'')" :btnClickHandler="onClickStamp" />
                </div>             
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-4" v-if="stamp_type==2">
          <div class="col-12">

              <ImageUpload :files="stamp_file" />

        <!-- <div class="content-dash draw-initials">
              <input type="file" ref="file" style="display: none" @change="onFileChange" />
              <div
                class="draw-placeholder clickable-icon"
                v-on:click="selectStamp()"
                v-if="img_file.length<=0"
              >
                <img src="img/icons/upload.svg" />
                <div class="mt-3">{{ $t('signature.modal.uploadStamp') }}</div>
              </div>
              <div class="selected-stamp" v-if="img_file.length>0">
                <img v-bind:src="img_file" />
              </div>
            </div> -->

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
            <b-button variant="primary" v-on:click="hideStampModal">{{ $t('signature.button.create') }}</b-button>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex"
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import DrawingBoard from "../../components/DrawingBoard";
import Stamp from "../../components/common/Stamp";
import ImageUpload from '../../components/common/ImageUpload'
import CustomLoader from '../../components/common/CustomLoader'
import SignatureInitial from './SignatureInitial'
import StampSeals from './StampSeals'
import { signation } from '../../mixins/signation'

import {
  STAMP_GET,
  SIGNATURE_GET
} from '../../store/actions.type'

export default {
  name: "SignatureStamp",
  components: {
    UserSelect,
    UserIcon,
    DrawingBoard,
    Stamp,
    ImageUpload,
    SignatureInitial,
    StampSeals,
    'custom-loader': CustomLoader
  },
  mixins: [signation],
  data() {
    return {
      language: "English",
      stamp_language: "English",
      title: "Title",
      drawable: false,
      drawableInitial: false,

      sign_file: [],
      initials_file: [],
      stamp_file: [],
      img_file: "",
      stamp_type: 0,
      selected_stamp_no: 0,
      stamp_name: "",
      fontsize_change: false,
      validator: {
        valid: true,
        focus: true,
        blurred: true,
        text: 'Only can generate less than 10 characters',
        error: ''
      },

      selected_no: 0,
      stamp_font: [],
      signature_types: [
        // English
        ["Arial", "Mrs Saint Delafield", "Badhead Typeface", "Banthers", "Connoisseurs", "Cutepunk_Regular", "Elrotex Basic", "GreatVibes-Regular", "KLSweetPineappleRegular", "Mightype Script", "pops_08_REGULAR", "somethingwild-Regular"],
        // Japanese
        ["AsobiMemogaki-Regular-1-01", "crayon_1-1", "RiiPopkkR", "RiiT_F", "sjis_sp_setofont", "GenEiLateGoN_v2", "GenEiAntiquePv5-M", "GenEiGothicN-Regular"],
        // Korean
        ["KimNamyun", "KCC-eunyoung", "Goyang", "SangSangFlowerRoad", "InkLipquid", "OTEnjoystoriesBA", "Dovemayo-Medium", "SDMiSaeng", "HSGyoulnoonkot", "Jeju Hallasan"]],
      fontsize_list: [
        // English
        ["20px", "26px", "29px", "19px", "29px", "29px", "14px", "21px", "29px", "19px", "18px", "29px"],
        // Japanese
        ["21px", "17px", "15px", "15px", "15px", "15px", "15px", "15px"],
        // Korean
        ["27px", "23px", "21px", "26px", "23px", "23px", "16px", "24px", "17px", "16px"],
      ],

      stamp_types: {
        title: "",
        name: "",
        position: "",
        company: ""
      },
      stamp_kr_types: {
        title: "",
        name: ""
      },
      stamp_jp_types: {
        title: "",
        name: ""
      },

      stamp_corporate: {
        name: "",
        position: "",
        company: ""
      },
      stamp_kr_corporate: {
        name: ""
      },
      stamp_jp_corporate: {
        name: ""
      },
      
      signature: "",
      initials: "",
      sign_type: 1,
      signatures: ["", "", "", ""],
      stams: ["", "", "", ""]
    };
  },  
  computed: {
    ...mapGetters(['USER', 'SIGNATURES', 'STAMPS', 'loading', 'errors']),
  },
  created() {
    window.addEventListener('resize', this.handleResize, {passive: true})
    this.handleResize(); 
  },
  destroyed() {
    window.removeEventListener('resize', this.handleResize, {passive: true})
  },
  mounted() {
    this.signature = this.signature || this.USER.name
    this.initials = this.USER.first_name.charAt(0) + this.USER.last_name.charAt(0)

    // this.stamp_types.name = this.stamp_name || this.USER.name
    this.stamp_types.position = this.USER.client.department_name
    this.stamp_types.company = this.USER.client.company_name

    this.getDefault();
  },
  methods: {
    handleResize() {
      if(window.innerWidth <500) {
        this.fontsize_change = true;
      } else {
        this.fontsize_change = false;
      }      
    },

    getNewItem(item) {
      var tempObj = {
        name: item.text,
        position: "",
        company: ""
      }
      return tempObj
    },

    getDefault() {
      // this.getStamps()
      //   .then(response => {
      //     console.log(response)
      //     this.$store.dispatch(STAMP_GET, response.data.data);
      //   })
      //   .catch(err => {
      //     console.log(err)
      //   })

      // this.getSignatures()
      //   .then(response => {
      //     console.log(response)
      //     this.$store.dispatch(SIGNATURE_GET, response.data.data);
      //   })
      //   .catch(err => {
      //     console.log(err)
      //   })
    },

    creatStamp() {
      this.stamp_types.title = this.title != "Title" ? this.title : '';
      this.stamp_types.name = this.stamp_name;

      this.stamp_name = this.stamp_name.replace(' ','');
      if (this.stamp_name.length>2){
        this.stamp_name = this.stamp_name.substr(0, 2) + ' ' + this.stamp_name.substr(2,2);
      }
        
      this.stamp_kr_types.name = this.stamp_name;
      this.stamp_jp_types.name = this.stamp_name;
      
      this.title = "Title";
      this.stamp_name = "";
    },
    creatStamp2() {
      this.stamp_corporate.name = this.stamp_name;
      this.stamp_kr_corporate.name = this.stamp_name;
      this.stamp_jp_corporate.name = this.stamp_name;
    },
    changeName() {
      var matches = this.signature.match(/\b(\w)/g); // ['J','S','O','N']
      this.initials = matches.join(''); // JSON

    },
    chunk(myArray, chunk_size){
      var index = 0;
      var arrayLength = myArray.length;
      var tempArray = [];
      
      for (index = 0; index < arrayLength; index += chunk_size) {
        var myChunk = myArray.slice(index, index+chunk_size);
        tempArray.push(myChunk);
      }
      return tempArray;
    },
    selectStamp() {
      this.$refs.file.click();
    },

    // onFileChange(e) {
    //   var files = e.target.files || e.dataTransfer.files;
    //   if (!files || !files.length) return;
    //   var reader = new FileReader();
    //   reader.onload = function(e) {
    //     this.img_file = e.target.result;
    //     // console.log(this.img_file);
    //   };
    //   reader.readAsDataURL(files[0]);
    // },
    // onInitialsFileChange(e) {
    //   var files = e.target.files || e.dataTransfer.files;
    //   if (!files || !files.length) return;
    //   var reader = new FileReader();
    //   reader.onload = function(e) {
    //     this.initials_file = e.target.result;
    //   };
    //   reader.readAsDataURL(files[0]);
    // },
    // selectInitials() {
    //   this.$refs.upload_initials_file.click();
    // },
    
    hideSignatureModal() {
      this.$refs["create-signature-modal"].hide();
    },
    createSignature() {
      this.$refs["create-signature-modal"].show();
    },
    hideStampModal() {
      this.$refs["create-stamp-modal"].hide();
    },
    createStamp() {
      this.$refs["create-stamp-modal"].show();
    },
    onClickStamp(keyItem) {
      this.selected_no = keyItem;
    }
  }
};
</script>

<style lang="scss">
@import "./Signature.scss";

/**
 * Stamp Seal Styles
 */
.main-circle {
  text-align: center;
  font-size: 4em;
  width: 270px;
  height: 270px;
  font-weight: 900;
  border: 0.5rem solid #D23;
  border-radius: 50%;
  mask-image: url('/img/sign/grunge.png');
  mask-size: 944px 604px;
  mix-blend-mode: multiply;
}

.circle {
  top: -30px;
  left: 42px;
  width: 170px;
  height: 170px;
  position: relative;
  display: block;
  background-color: transparent;
  color: #D23;
  border: 0.5rem solid #D23;
  border-radius: 50%;
}

.circle:after {
  display: block;
  padding-bottom: 100%;
  width: 100%;
  height: 0;
  border-radius: 50%;
  background-color: #FFF;
  content: "";
}

.circle__inner {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.circle__wrapper {
  display: table;
  width: 100%;
  height: 100%;
}

.circle__content {
  display: table-cell;
  padding: 0m;
  vertical-align: middle;
  text-align: center;
  transform: rotate(0);
  
  min-width: 150px;  
  min-height: 150px;
  mask-position: 2rem 3rem;
}
/*
@media (min-width: 480px) {
  .circle__content {
    font-size: 2em;
  }
}

@media (min-width: 768px) {
  .circle__content {
    font-size: 4em;
  }
}
*/

div.circTxt {
  /*allows for centering*/
  display: inline-block;
  /*adjust as needed*/
  margin-right: 0px;
  margin-bottom: 55px;
  font-size: .5em;
  color: #D23;
  background-color: transparent;
  bottom: 100;
}

.stamp-tab-nav {
  padding: 0px 5px;
  font-size: .8rem;
}



input.form-control, select.form-control {
  &:focus {
    color: #2E221E !important;
    box-shadow: 0 0 3px 0 #775649;
  }

  &.input-invalid {
    border-color: #D22A20;
    color: #D22A20 !important;
    &:-webkit-autofill {
      color: #D22A20;
      -webkit-text-fill-color: #D22A20;
    }
    &:focus {
      box-shadow: 0 0 3px 0 rgba(210, 42, 32, 0.75) !important;
    }
  }

  &.input-valid {
    color: #202020 !important;
    &:-webkit-autofill {
      color: #202020;
      -webkit-text-fill-color: #202020;
    }
  }
}
</style>

