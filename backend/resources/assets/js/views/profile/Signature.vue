<template>
  <div class="app flex-row" ref="fileform">
    <div class="w-100 sign-stamp">
      <h1>Signature and Stamp</h1>
      <hr class="mb-4" />
      <div class="row">
        <div class="col-md-6 pr-md-0">
          <div class="content-card">
            <div class="content">
              <div class="d-flex align-items-center">
                <img src="img/icons/contract.svg" />
                <div class="ml-3">
                  <div class="header">Your signature & Initials</div>
                  <div
                    class="comments"
                  >Add cool signiture and initials from here.</div>
                </div>
              </div>
              <b-button variant="primary" v-on:click="createSignature()">
                Add Singiture/Initials
              </b-button>
            </div>
          </div>

          <div
            class="content-card sign-signature pb-0"
            v-for="(item, index) in signatures"
            :key="index"
          >
            <div class="sign">
              <span>Suzanne Thompson</span>
              <span class="ml-4">ST</span>
            </div>
            <div class="actions">
              <div class="action clickable-icon">
                <i class="fa fa-pencil pr-2"></i> Edit
              </div>
              <div class="action clickable-icon">
                <i class="fa fa-download pr-2"></i> Download
              </div>
              <div class="action clickable-icon">
                <i class="fa fa-trash pr-2"></i> Delete
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="content-card">
            <div class="content">
              <div class="d-flex align-items-center">
                <img src="img/icons/stamp.svg" />
                <div class="ml-3">
                  <div class="header">Your stamp</div>
                  <div
                    class="comments"
                  >Add personnel / Corporate seal from here.</div>
                </div>
              </div>
              <b-button variant="primary" v-on:click="createStamp()">Add stamp</b-button>
            </div>
          </div>

          <div class="d-flex flex-wrap">
            <div class="content-card d-flex stamp" v-for="(item, index) in signatures" :key="index">
              <img src="img/payment/stamp.png" />
              <div class="stamp-action">
                <div class="action clickable-icon">
                  <i class="fa fa-pencil pr-2"></i> Edit
                </div>
                <div class="action clickable-icon">
                  <i class="fa fa-download pr-2"></i> Download
                </div>
                <div class="action clickable-icon">
                  <i class="fa fa-trash pr-2"></i> Delete
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal
      id="create-signature-modal"
      ref="create-signature-modal"
      hide-footer
      centered
      size="xl"
    >
      <div class="create-signature-modal">
        <div class="title">Create Your Signature</div>
        <div class="row mb-4">
          <div class="col-4 pr-0 pr-md-3">
            <b-button
              :variant="sign_type == 1?'primary':'outline-primary'"
              v-on:click="sign_type = 1"
              block
            >Choose</b-button>
          </div>
          <div class="col-4 px-2 px-md-3">
            <b-button
              :variant="sign_type == 0?'primary':'outline-primary'"
              v-on:click="sign_type = 0"
              block
            >Draw</b-button>
          </div>
          <div class="col-4 pl-0 pl-md-3">
            <b-button
              :variant="sign_type == 2?'primary':'outline-primary'"
              v-on:click="sign_type = 2"
              block
            >Upload</b-button>
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
                <div class="mt-3">Draw signature</div>
              </div>
              <drawing-board v-if="drawable" class="draw-pan"></drawing-board>
            </div>
            <div class="reset">
              <b-button variant="link" v-on:click="drawable=false">
                <i class="fa fa-undo" /> Reset
              </b-button>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="content-dash draw-initials">
              <div class="draw-placeholder">
                <img src="img/icons/pencil-draw.svg" />
                <div class="mt-3">Draw Initials</div>
              </div>
            </div>
            <div class="reset">
              <b-button variant="link">
                <i class="fa fa-undo" /> Reset
              </b-button>
            </div>
          </div>
        </div>
        <div class="row mb-4" v-if="sign_type==1">
          <div class="col-12">
            <hr class="w-100" />
            <div class="row">
              <div class="col-12 col-lg-2">
                <UserSelect
                  v-bind:value="language"
                  v-bind:items="['English', 'Korean', 'Japanese']"
                  @changeValue="language = $event"
                />
              </div>
              <div class="col-8 col-lg-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="signature" placeholder="signature"
                    name="signature" v-model="signature" v-on:keyup="changeName"
                  />
                </div>
              </div>
              <div class="col-4">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="Initials"
                    placeholder="First Name"
                    name="initials"
                    v-model="initials"
                  />
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
                      <div class="signed-by">CoffeSigned by:</div>
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
            <div class="content-dash draw-signature">
              <div class="draw-placeholder">
                <img src="img/sign/sign.png" class="sign-img" />
              </div>
            </div>
            <div class="reset">
              <b-button variant="link">Replace</b-button>
              <span class="mx-2">|</span>
              <b-button variant="link">Remove</b-button>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="content-dash draw-initials">
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
                <div class="mt-3">Upload Initials</div>
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="footer">
          <div
            class="summary"
          >By clicking Create, I agree that the signature and initials will be the electronic representation of my signature and initials for all purposes when I (or my agent) use them on envelopes, including legally binding contracts - just the same as a pen-and-paper signature or initial.</div>
          <div class="buttons">
            <b-button variant="link" v-on:click="hideSignatureModal">
              <span>
                <i class="fa fa-close"></i> Cancel
              </span>
            </b-button>
            <b-button variant="primary" v-on:click="hideSignatureModal">Create</b-button>
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal id="create-stamp-modal" ref="create-stamp-modal" hide-footer centered size="xl">
      <div class="create-signature-modal">
        <div class="title">Create Your stamp</div>
        <div class="row mb-4">
          <div class="col-4 px-1 px-lg-4">
            <b-button
              :variant="stamp_type == 0?'primary':'outline-primary'"
              v-on:click="stamp_type = 0"
              block
            >Personnel Seal</b-button>
          </div>
          <div class="col-4 px-1 px-lg-4">
            <b-button
              :variant="stamp_type == 1?'primary':'outline-primary'"
              v-on:click="stamp_type = 1"
              block
            >Corporate Seal</b-button>
          </div>
          <div class="col-4 px-1 px-lg-4">
            <b-button
              :variant="stamp_type == 2?'primary':'outline-primary'"
              v-on:click="stamp_type = 2"
              block
            >Upload</b-button>
          </div>
        </div>
        <div class="row mb-4" v-if="stamp_type==0 || stamp_type==1">
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
                    class="form-control"
                    id="stamp_name"
                    placeholder="Enter your name and press the Create button"
                    name="stamp_name"
                    v-model="stamp_name"
                  />
                </div>
              </div>
              <div class="col-12 col-sm-4 col-lg-2">
                <b-button variant="primary" block v-on:click="creatStamp()">Create</b-button>
              </div>
            </div>
            <div class="signatures">
              <div class="row" v-if="stamp_language == 'English'">
                <div class="col-12 mb-4 mb-lg-0 col-lg-4" v-for="(item, index) in stamp_types" :key="index" >
                  <div class="sign-result" v-bind:class="index==selected_no?'checked':''" v-on:click="selected_no = index">
                    <img src="img/payment/stamp-2x.png" />
                    <div class="check-box" v-if="index == selected_no">
                      <img src="img/icons/check-2.svg" />
                    </div>
                  </div>
                </div>
                <i class="fa fa-long-arrow-left left-button clickable-icon" />
                <i class="fa fa-long-arrow-right right-button clickable-icon" />
              </div>
              <div class="row" v-if="stamp_language == 'Korean'">
                <div class="col-12 mb-4 mb-lg-0 col-lg-4" v-for="(item, index) in stamp_kr_types" :key="index" >
                  <div class="sign-result" v-bind:class="index==selected_no?'checked':''" v-on:click="selected_no = index">
                    <div class="kr-stamp">{{item}}</div>
                    <div class="check-box" v-if="index == selected_no">
                      <img src="img/icons/check-2.svg" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" v-if="stamp_language == 'Japanese'">
                <div class="col-12 mb-4 mb-lg-0 col-lg-4" v-for="(item, index) in stamp_jp_types" :key="index" >
                  <div class="sign-result" v-bind:class="index==selected_no?'checked':''" v-on:click="selected_no = index">
                    <div class="kr-stamp jp-stamp">{{item}}</div>
                    <div class="check-box" v-if="index == selected_no">
                      <img src="img/icons/check-2.svg" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mb-4" v-if="stamp_type==2">
          <div class="col-12">
            <div class="content-dash draw-initials">
              <input type="file" ref="file" style="display: none" @change="onFileChange" />
              <div
                class="draw-placeholder clickable-icon"
                v-on:click="selectStamp()"
                v-if="img_file.length<=0"
              >
                <img src="img/icons/upload.svg" />
                <div class="mt-3">Upload Stamp</div>
              </div>
              <div class="selected-stamp" v-if="img_file.length>0">
                <img v-bind:src="img_file" />
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="footer">
          <div
            class="summary"
          >By clicking Adding, I agree that the Stamp will be the electronic representation of my Stamp for all purposes when I (or my agent) use them on envelopes, including legally binding contracts - just the same as a pen-and-paper stamp.</div>
          <div class="buttons">
            <b-button variant="link" v-on:click="hideStampModal">
              <span>
                <i class="fa fa-close"></i> Cancel
              </span>
            </b-button>
            <b-button variant="primary" v-on:click="hideStampModal">Create</b-button>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import DrawingBoard from "../../components/DrawingBoard";
export default {
  name: "SignatureStamp",
  components: {
    UserSelect,
    UserIcon,
    DrawingBoard
  },
  data() {

    return {
      language: "English",
      stamp_language: "English",
      drawable: false,
      img_file: "",
      initials_file: "",
      stamp_type: 0,
      selected_stamp_no: 0,
      stamp_types: ["", "", ""],
      stamp_kr_types: ["오늘 뉴스", "정한 한"],
      stamp_jp_types: ["音信", "産毛"],
      stamp_name: "",
      fontsize_change: false,

      selected_no: 0,
      stamp_font: [],
      signature_types: [
        ["Mrs Saint Delafield", "Badhead Typeface", "Banthers", "Connoisseurs", "Cutepunk_Regular", "Elrotex Basic", "GreatVibes-Regular", "KLSweetPineappleRegular", "Mightype Script", "pops_08_REGULAR", "somethingwild-Regular"],
        ["AsobiMemogaki-Regular-1-01", "crayon_1-1", "RiiPopkkR", "RiiT_F", "sjis_sp_setofont", "GenEiLateGoN_v2", "GenEiAntiquePv5-M", "GenEiGothicN-Regular"],
        ["KimNamyun", "KCC-eunyoung", "Goyang", "SangSangFlowerRoad", "InkLipquid", "OTEnjoystoriesBA", "Dovemayo-Medium", "SDMiSaeng", "HSGyoulnoonkot", "Jeju Hallasan"]],
      fontsize_list: [
        ["26px", "29px", "19px", "29px", "29px", "14px", "21px", "29px", "19px", "18px", "29px"],
        ["21px", "17px", "15px", "15px", "15px", "15px", "15px", "15px"],
        ["27px", "23px", "21px", "26px", "23px", "23px", "16px", "24px", "17px", "16px"],
        ],
      signature: "Suzanne Thompson",
      initials: "ST",
      sign_type: 1,
      signatures: ["", "", "", ""],
      stams: ["", "", "", ""]
    };
  },
  
  created() {
    window.addEventListener('resize', this.handleResize)
    this.handleResize();
  },
  destroyed() {
    window.removeEventListener('resize', this.handleResize)
  },
  methods: {
    handleResize() {
      if(window.innerWidth <500) {
        this.fontsize_change = true;
      } else {
        this.fontsize_change = false;
      }
      
    },
    creatStamp() {
      this.stamp_name = this.stamp_name.replace(' ','');
      if (this.stamp_name.length>2){
        this.stamp_name = this.stamp_name.substr(0, 2) + ' ' + this.stamp_name.substr(2,2);
      }
      if (this.stamp_language == 'Korean') {
        this.stamp_kr_types.push(this.stamp_name);
        console.log(this.stamp_kr_types);
      } else if(this.stamp_language =="Japanese") {
        this.stamp_jp_types.push(this.stamp_name);
      }
      this.stamp_name = "";

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
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files || !files.length) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        this.img_file = e.target.result;
        console.log(this.img_file);
      };
      reader.readAsDataURL(files[0]);
    },
    selectStamp() {
      this.$refs.file.click();
    },

    onInitialsFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files || !files.length) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        this.initials_file = e.target.result;
      };
      reader.readAsDataURL(files[0]);
    },
    selectInitials() {
      this.$refs.upload_initials_file.click();
    },

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
    }
  }
};
</script>

<style lang="scss">
@import "./Signature.scss";
</style>

