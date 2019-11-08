<template>
  <div class="app flex-row">
    <div class="w-100">
      <div class="row">
        <div class="col-12">
          <div class="sign-history content-card p-5">
            <div v-for="(item, item_index) in sign_items" :key="item_index" class="doc-control">
              <div class="doc-item" :id="`popover-${item_index}`">
                <div
                  class="doc-item-control color-signature"
                  v-bind:style="{color: item.color, borderColor: item.color}"
                >
                  <i :class="item.tool_icon"></i>
                  <span class="ml-2">Click to {{item.tool_name}}</span>
                </div>
                <div class="doc-item-no" v-if="item_index <= signStep">
                  {{ item_index + 1 }}
                </div>
              </div>
              <b-popover :ref="`popover-${item_index}`"  :target="`popover-${item_index}`" placement="bottom">
                <div class="arrow-popover"></div>
                <b-button variant="link" class="mr-3" v-on:click="editItem(item_index)">Edit</b-button>
                <b-button variant="link" v-on:click="removeItem(item_index)">Clear</b-button>
              </b-popover>
            </div>              
          </div>
        </div>
      </div>
    </div>
    <b-modal id="sign-agree-modal" ref="sign-agree-modal"
       hide-footer centered size="xl">
      <div class="sign-agree-modal">
        <div class="text-center"><img src="img/icons/agree.svg" /></div>
        <div class="title">Accept all signatures for electronic signatures</div>
        <div class="agree-items"> 
          <div class="agree-item">
            <i class="fa fa-check mr-2"></i>
            <div class="agree-text">
              I agree to the legal validity of electronic signatures and electronic documents.
            </div>            
          </div>
          <div class="agree-item">
            <i class="fa fa-check mr-2"></i>
            <div class="agree-text">
              The electronic document sent after the signing is accepted as original.
            </div>            
          </div>
          <div class="agree-item">
            <i class="fa fa-check mr-2"></i>
            <div class="agree-text">
              We have verified that all signers have the right to have electronic signatures.
            </div>            
          </div>
          <div class="agree-item">
            <i class="fa fa-check mr-2"></i>
            <div class="agree-text">
              I agree in accordance with the Electronic Signature <b-button variant="link">Terms of Use</b-button> and Electronic Signature <b-button variant="link">Privacy Policy</b-button>.
            </div>            
          </div>
        </div>
        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelAgree()" >Cancel</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="agreeAll()">I agree and sign it</button>
        </div>
      </div>
    </b-modal>
    
    <b-modal id="sign-waiting-modal" ref="sign-waiting-modal" @hide="closeWaitingModal"
       hide-footer hide-header centered size="xl">
      <div class="sign-waiting-modal">
        <img src="img/icons/sand-clock.svg" />
        <div class="title">
          Please wait few minutes <br>
          this process takes 1-3 minutes
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import pdf from "vue-pdf";
import draggable from "vuedraggable";
export default {
  name: "Signing",
  components: {
    pdf,
    UserIcon,
    UserSelect,
    draggable
  },
  data() {
    return {
      sign_items: [
        {
          color: "#775649",
          icon: "fa fa-user",
          name: "Roger Waters",
          tool_icon: "fa fa-pencil",
          tool_name: "Signature",
        },
        {
          color: "#ee9964",
          icon: "fa fa-building",
          name: "Roger Waters",
          tool_icon: "fa fa-building",
          tool_name: "Company",
        },
        {
          color: "#775649",
          icon: "fa fa-user",
          name: "Roger Waters",
          tool_icon: "fa fa-user",
          tool_name: "Full Name",
        },
        {
          color: "#ee9964",
          icon: "fa fa-briefcase",
          name: "Roger Waters",
          tool_icon: "fa fa-briefcase",
          tool_name: "Signature",
        },
        {
          color: "#775649",
          icon: "fa fa-user",
          name: "Roger Waters",
          tool_icon: "fa fa-file-text",
          tool_name: "Text",
        },
        {
          color: "#ef634c",
          icon: "fa fa-user",
          name: "Roger Waters",
          tool_icon: "fa fa-calendar",
          tool_name: "Attachments",
        },
      ],
      signStep: -1,
      percent: "50%",
      viewSrc: null,
      viewPage: 0,
      currentPage: 0,
      pageCount: 0,
      src: null,
      numPages: undefined
    };
  },
  mounted() {
    this.src = pdf.createLoadingTask("doc/1.pdf");

    this.src.then(pdf => {
      this.numPages = pdf.numPages;
      this.viewPage = 1;
      this.viewSrc = this.src;
    });

    this.$root.$on('finishSign', () => {
      this.openAgreeModal();
    })
    this.$root.$on('nextSignEdit', () => {
      this.editItem(this.signStep+1);
    })
  },
  methods: {
    removeItem(item_index) {
      console.log(item_index);
      // this.$refs[`popover-${item_index}`].$emit('close');
      // this.sign_items.splice(item_index,1); 
    },
    editItem(item_index) {
      console.log(item_index);
      if(this.signStep+1 != item_index) return;
      if(this.signStep >= this.sign_items.length-1) return;
      this.signStep = item_index;
      this.$root.$emit('nextStep', item_index+1, this.sign_items.length -1 == item_index);
    },
    closeWaitingModal() {
      this.$router.push({ path: '/sign/complition' });
    },
    cancelAgree() {
      this.$refs["sign-agree-modal"].hide();
    },
    agreeAll() {
      this.$refs["sign-agree-modal"].hide();
      this.$refs["sign-waiting-modal"].show();
    },
    openAgreeModal() {
      this.$refs["sign-agree-modal"].show();
    },
    changePercent(e) {
      this.percent = e;
    },
    selectPage(src, no) {
      this.viewPage = no;
      this.viewSrc = src;
    }
  }
};
</script>

<style lang="scss">
@import "./Signing.scss";
</style>

