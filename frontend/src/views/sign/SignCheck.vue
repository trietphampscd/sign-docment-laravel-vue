<template>
  <div class="app flex-row">
    <div class="w-100">
      <div class="d-flex justify-content-between align-items-center">
        <b-button variant="outline-primary" v-on:click="toggleDoc = !toggleDoc">
          <UserIcon icon="doc_2.svg" :button="true" />
        </b-button>
        
        <div class="d-flex align-items-center control-actions">
          <b-button variant="outline-primary"  v-on:click="zoom_out()" class="d-none d-sm-inline">
            <UserIcon icon="plus.svg" :button="true"/>
          </b-button>
          <UserSelect
            v-bind:value="percent"
            v-bind:items="zoom_list"
            @changeValue="changePercent"
            class="mb-0 mx-1 mx-md-2"
          />
          <b-button variant="outline-primary"  v-on:click="zoom_in()" class="d-none d-sm-inline">
            <UserIcon icon="minus.svg" :button="true" />
          </b-button>
        </div>
        <div class="d-flex align-items-center control-actions">
          <b-button variant="outline-primary">
            <UserIcon icon="delete.svg" :button="true" />
          </b-button>
          <b-button variant="outline-primary" class="mx-1 mx-md-2">
            <UserIcon icon="download_3.svg" :button="true" />
          </b-button>
          <b-button variant="outline-primary">
            <i class="fa fa-print clickable-icon top-menu-icon" />
          </b-button>
        </div>
      </div>
      <hr class="mb-0"/>
      <div class="doc-pan">
        <div class="doc-list" v-bind:class="toggleDoc?'': 'closed'">
          <div class="content-container" v-bind:class="toggleDoc?'': 'd-none'">
            <div class="documents">
              <div class="title">
                <span>DOCUMENTS</span>
              </div>
              <hr />
              <div class="documents-list">
                <div class="document-content">
                  <div class="collapse-header item-title" v-b-toggle.collapse1>
                    <span>Continuous Improvement lorem ipsum sit dollor amet.doc</span>
                    <span class="collapse-icon">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <!-- <i class="collapse-icon"></i> -->
                  </div>
                  <b-collapse id="collapse1" class="item-pages" visible>
                    <div
                      class="page-content"
                      v-for="i in numPages"
                      :key="i"
                      v-on:click="selectPage(src, i)"
                    >
                      <pdf
                        :src="src"
                        :page="i"
                        class="pdf-content"
                        v-bind:class="i==viewPage?'selected-pdf':''"
                      ></pdf>
                      <div class="page-no">{{i}}</div>
                    </div>
                  </b-collapse>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="preview-doc" v-bind:class="toggleDoc?'': 'closed'">
          <div class="sign-pan p-4">
            <div class="wrap-list-doc">
              <div class="content-card mb-4 mx-auto" :style="{width: percent}">
                <span >CoffeeSign Envoloped ID: 64343EAB33-C3234-43</span>
                <pdf :src="viewSrc" class="w-100" :page="viewPage"></pdf>
              </div>
            </div>
          </div>
          <div class="text-center">
            <b-button variant="link" class="mr-0 mr-sm-5" >Sign later</b-button>
            <b-button variant="other" class="px-2 px-sm-5" v-on:click="moveNextPage()">Start Signing</b-button>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="sign-email-check-modal" ref="sign-email-check-modal" hide-footer  hide-header centered size="m" @hide="closeWaitingModal">
      <div class="send-modal" style="padding: 15px;">
        <i class="fa fa-reply" aria-hidden="true" style="font-size:34px"></i>
        <div
          class="comments text-center"
          style="margin:10px 0 20px"
        > 
          Please, Re-enter your email to continue signing document.
        </div>
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              id="email_check"
              placeholder="Enter your email"
              name="email_check"
            />
          </div>
        <div class="comments text-center">
        <button
          type="submit"
          class="btn btn-other"
          style="margin:0 5px"
        > Cancel </button>
        <button
          type="submit"
          class="btn btn-primary"
          style="margin:0 5px"
        > Submit </button>
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
  name: "SignCheck",
  components: {
    pdf,
    UserIcon,
    UserSelect,
    draggable
  },
  data() {
    return {
      toggleDoc: true,
      zoom_list: ['10%','20%', '30%', '50%', '75%','100%', "120%", "150%", "200%"],
      sign_items: [],
      percent: '100%',
      percent_no: 4,
      viewSrc: null,
      viewPage: 0,
      currentPage: 0,
      pageCount: 0,
      src: null,
      numPages: undefined
    };
  },
  mounted() {
    // this.$refs["sign-email-check-modal"].show();
    this.src = pdf.createLoadingTask("doc/1.pdf");
    if(window.innerWidth<500) {
      this.toggleDoc = false;
    }
    this.src.then(pdf => {
      this.numPages = pdf.numPages;
      this.viewPage = 1;
      this.viewSrc = this.src;
    });
  },
  methods: {
    closeWaitingModal() {
      // this.$router.push({ path: '/sign/complition' });
    },
    zoom_out() {
      this.percent_no = this.zoom_list.indexOf(this.percent);
      if(this.percent_no<8){
        this.percent_no++;
      }
      this.percent = this.zoom_list[this.percent_no];
    },
    zoom_in() {
      this.percent_no = this.zoom_list.indexOf(this.percent);
      if(this.percent_no>0){
        this.percent_no--;
      }
      this.percent = this.zoom_list[this.percent_no];
    },
    changePercent(e) {
      this.percent = e;
    },
    selectPage(src, no) {
      this.viewPage = no;
      this.viewSrc = src;
      if(window.innerWidth<500) {
        this.toggleDoc = false;
      }
    },
    moveNextPage() {
      this.$router.push("/sign/signing");
    },
  }
};
</script>

<style lang="scss">
@import "./SignCheck.scss";
</style>

