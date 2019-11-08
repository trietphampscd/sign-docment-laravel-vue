<template>
  <div class="app flex-row">
    <div class="w-100">
      <div class="d-flex justify-content-between align-items-center">
        <!-- <b-button variant="outline-primary" v-on:click="toggleDoc = !toggleDoc"> -->
        <b-button variant="outline-primary">
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
            class="mb-0 mx-2"
          />
          <b-button variant="outline-primary"  v-on:click="zoom_in()" class="d-none d-sm-inline">
            <UserIcon icon="minus.svg" :button="true" />
          </b-button>
        </div>
        <div class="d-flex align-items-center control-actions">
          <b-button variant="outline-primary" class="mx-1 mx-md-2">
            <UserIcon icon="download_3.svg" :button="true" />
          </b-button>
          <b-button variant="outline-primary">
            <i class="fa fa-print clickable-icon top-menu-icon" />
          </b-button>
        </div>
      </div>
      <hr class="mb-4" />
      <div class="doc-pan">
        <div class="doc-list" v-bind:class="toggleDoc?'': 'closed'">
          <div class="content-container" v-bind:class="toggleDoc?'': 'd-none'">
            <div class="documents">
              <div class="title">
                <span>{{ $t("docsign.documents") }}</span>
              </div>
              <hr />
              <div class="documents-list">
                <div class="document-content" v-for="(file, key) in documentList.data" :key="key">
                  <div v-if="file.images.length > 0">
                    <div
                      class="collapse-header item-title"
                      v-b-toggle="`document_list_toggle_${key}`"
                    >
                      <span class="title">{{file.document_name}}</span>
                      <span class="collapse-icon">
                        <i class="fa fa-caret-left" />
                      </span>
                    </div>
                  </div>
                  <b-collapse :id="`document_list_toggle_${key}`" class="item-pages" visible v-if="file.images.length > 0">
                    <div
                      class="page-content"
                      v-for="(image, imgKey) in file.images"
                      :key="imgKey"
                    >
                      <div
                        :id="`doc_nav_id_${file.id}_${imgKey}`"
                        class="pdf-content"
                        v-on:click="selectPage(file, numpage(image))"
                      >
                        <div class="loader_img" v-if="pageLoading">
                          <i class="fa fa-spinner fa-spin fa-2x" />
                        </div>
                        <img :src="`${image}`" class="w-100 selected-pdf main_img" />
                      </div>
                      <div class="actionsButton">
                        <div class="page-no pull-right">{{imgKey + 1}}</div>
                      </div>
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
              <div v-if="pageLoading" class="pageLoading">
            <i class="fa fa-spinner fa-spin fa-3x" />
          </div>
          <div v-if="!pageLoading" id="document_container">
            <div class="wrap_docs">
              <div
                v-for="(data, key) in pages"
                :key="key"
                :id="`droppable_content_${key + 1}`"
                :data-page_num="key + 1"
                :data-doc_id="data.docId"
                :data-rotate="checkRotate(data, documentList.data[data.pageId])"
                style="position:relative"
                class="droppable_content"
              >
                <div :id="`doc_id_${data.docId}_${data.pageNum}`" class="content-background">
                  <div class="loader_img">
                    <i class="fa fa-spinner fa-spin fa-2x" />
                  </div>
                  <img
                    :src="`${data.image}`"
                    class="w-100 main_img img_zoom"
                    v-bind:class="key==viewPage?'selected-pdf':''"
                  />
                </div>
              </div>
            </div>
          </div>
              <!-- <div class="content-card mb-4 mx-auto" :style="{width: percent}">
                <span >CoffeeSign Envoloped ID: 64343EAB33-C3234-43</span>
                <pdf :src="viewSrc" class="w-100" :page="viewPage"></pdf>
              </div> -->
            </div>
          </div>
          <div class="text-center">
            <b-button variant="link" class="mr-0 mr-sm-5" >Sign later</b-button>
            <b-button variant="other" class="px-2 px-sm-5" v-on:click="moveNextPage()">Start Signing</b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "bootstrap/dist/js/bootstrap.min.js";
import "bootstrap-multiselect/dist/css/bootstrap-multiselect.css";

import { mapGetters } from "vuex";
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import {
  GET_DOCUMENT_REQUEST,
  GET_BLOD_FROM_URL,
  ADD_ANNTATION,
  GET_ANNTATION,
  EDIT_ANNTATION,
  DELETE_ANNTATION,
  ADD_ROTATE_DOCUMENTS,
  ADD_DELETE_DOCUMENTS,
  GET_RECIPIENTS
} from "../../store/modules/document";
import store from "../../store/store";
import { GET_DOCS } from "../../store/actions.type";
import pdf from "vue-pdf";
import draggable from "vuedraggable";
import { addParamsToBlob, userListDefaultColors } from "../../helpers";
import { prepareTools } from "../../helpers/prepareHandle";
import {
  prepareHandle,
  initialPrepare,
  history,
  unredoButton,
  userSideBarHandle,
  addCommentToDocument,
  rotateFunction,
  deleteSuccessHandle
} from "../../helpers/prepareHandle";
import {
  generalDefaultButton,
} from "../../helpers/signHandle";
import config from "../../config/config";
import { EventBus } from "../../config/event-bus";

export default {
  name: "Prepare",
  components: {
    pdf,
    UserIcon,
    UserSelect,
    draggable
  },
  computed: {
    ...mapGetters(["addDocument", [GET_DOCUMENT_REQUEST], "getRecipients"])
  },
  data() {
    return {
      documentList: [],
      pages: [],
      sign_items: [],
      zoom_list: ['10%','20%', '30%', '50%', '75%','100%', "120%", "150%", "200%"],
      percent: "100%",
      viewSrc: null,
      viewPage: 0,
      currentPage: 0,
      pageCount: 0,
      src: null,
      numPages: undefined,
      annotations: [],
      pageLoading: true,
      nextLoading: false,
      commentButtonActive: false,
      recipientsList: [],
      items:[],

      toggleDoc: true,
    };
  },
  mounted() {
    // $("<span>geeks Writer !!!</span>").appendTo(`#doc_id_80_1`); 
    let vm = this;
    let backendUrl = `${config.BASE_URL}`;
    let document_id = vm.$route.query.document_id;
    vm.pages = [];
    vm.documentList = [];

    store
      .dispatch(GET_DOCS, document_id)
      .then(res => {
        vm.recipientsList = vm.getRecipients;
      })
      .catch(err => {});
    store
      .dispatch(GET_RECIPIENTS, vm.$route.query.document_id)
      .then(res => {
        if (res.status && res.list) {
          vm.items = res.list.map((item, key) => {
            const children = prepareTools.map(tool => tool);
            return {
              ...item,
              color: userListDefaultColors[key],
              icon: "fa fa-user",
              children: children
            };
          });
          
        }
      });
    store.dispatch(GET_ANNTATION, document_id).then(res => {
      if (res.status && res.annotations && res.annotations.length > 0) {
        vm.annotations = res.annotations;
      }
    });

    this.initialFunction();
  },
  methods: {
    initialFunction() {
      let vm = this;
      let backendUrl = `${config.BASE_URL}`;
      let document_id = vm.$route.query.document_id;
      vm.pages = [];
      vm.documentList = [];

      store
        .dispatch(GET_DOCUMENT_REQUEST, { document_id, show_image: 1 })
        .then(res => {
          vm.documentList = vm[GET_DOCUMENT_REQUEST];
          if (
            vm.documentList &&
            vm.documentList.data &&
            vm.documentList.data.length > 0
          ) {
            vm.documentList.data.map((v, docKey) => {
              v.images = v.images &&
                v.images.length > 0 &&
                v.images.filter((img, imgKey) => {
                  let checkPageDelete =
                    v.document_action.length > 0 &&
                    v.document_action.find(
                      ac => ac.page === imgKey + 1 && ac.delete === 1
                    );
                  !checkPageDelete && vm.pages.push({
                    docId: v.id,
                    pageId: docKey,
                    pageNum: this.numpage(img),
                    image: img
                  });   
                  return !checkPageDelete;
                  
                });
            });
            vm.pageLoading = false;
            initialPrepare(vm.pages);
            generalDefaultButton(vm.annotations, vm.items);

            // general drop
            prepareHandle(
              vm.pages.map((v, key) => key),
              this.recipientsList,
              this.prepareEvent
            );
          }
        // console.log('vm.documentList.data', vm.documentList.data[0])
        // console.log('vm.pages', vm.pages)

        });
      vm.$root.$on("bv::scrollspy::activate", vm.onActivate);
    },
    numpage(imgUrl) {
      let arrayImgUrl = imgUrl.split("/").pop();
      return parseInt(arrayImgUrl.split(".")[0].slice(-1));
    },
    checkRotate(data, document) {
      let getRotate =
        document &&
        document.document_action &&
        document.document_action.length > 0 &&
        document.document_action.find(v => v.page === data.pageNum + 1);
      return getRotate && getRotate.rotate;
    },
    changePercent(e) {
      this.percent = e;
      this.changeZoom(e);
    },
    changeZoom(e){
      var zoomScale = Number(parseInt(e.replace("%", ""))) / 100;
      for (
        var i = 0;
        i < document.getElementsByClassName("droppable_content").length;
        i++
      ) {
        this.setZoom(
          zoomScale,
          document.getElementsByClassName("droppable_content")[i]
        );
      }
    },
    setZoom(zoom, el) {
      var transformOrigin = [0, 0];
      el = el || instance.getContainer();
      var p = ["webkit", "moz", "ms", "o"],
        s = "scale(" + zoom + ")",
        oString =
          transformOrigin[0] * 100 + "% " + transformOrigin[1] * 100 + "%";

      for (var i = 0; i < p.length; i++) {
        el.style[p[i] + "Transform"] = s;
        el.style[p[i] + "TransformOrigin"] = oString;
      }

      el.style["transform"] = s;
      el.style["transformOrigin"] = oString;
      el.style["height"] = "auto";
      el.childNodes[0].style["height"] = "auto";
    },
    zoom_out() {
      this.percent_no = this.zoom_list.indexOf(this.percent);
      if(this.percent_no<8){
        this.percent_no++;
      }
      this.percent = this.zoom_list[this.percent_no];
      this.changeZoom(this.percent);
    },
    zoom_in() {
      this.percent_no = this.zoom_list.indexOf(this.percent);
      if(this.percent_no>0){
        this.percent_no--;
      }
      this.percent = this.zoom_list[this.percent_no];
      this.changeZoom(this.percent);
    },
    onActivate(target) {
      // console.log("target", target);
    },
    selectPage(data, imgKey) {
      let scrollId = `doc_id_${data.id}_${imgKey}`;
      var scrollTo = document.getElementById(scrollId);
      scrollTo.scrollIntoView();
    },
    moveNextPage() {
      this.nextLoading = true;
      this.$router.push(
        "/sign/signing?document_id=" + this.$route.query.document_id
      );
    },
    checkUserTool() {
      return $(`div[id^='droppable_content_'] .user-drag`).length > 0;
    },
    moveBackPage() {
      this.$router.push(
        "/docu-sign/add-recipients?document_id=" + this.$route.query.document_id
      );
    },
    prepareEvent(data, element) {
      const vm = this,
        document_id = vm.$route.query.document_id;
      let sendData = data.send_data;
      sendData.annotation_id = document_id;
      data.annotation_id && (sendData.id = data.annotation_id);

      var checkElemnts = $(".tool-sign:hidden");
      vm.$store.dispatch(ADD_ANNTATION, sendData).then(res => {
        let get_annotation_id = element.data("annotation_id");
        if (res.status && res.annotation_id && !get_annotation_id) {
          if (
            !sendData.id &&
            history.redo_list &&
            history.redo_list.length > 0
          ) {
            history.redo_list.map((val, key) => {
              let annotation_id = val.id;
              vm.$store.dispatch(DELETE_ANNTATION, annotation_id).then(res => {
                checkElemnts[key].remove();
              });
            });
          }
          element.attr("data-annotation_id", res.annotation_id);
          element.addClass("tool-sign tool_sign_" + res.annotation_id);
        }
        if (sendData.text && sendData.recipient_arr) {
          element.popover("hide");
          element.css("opacity", 0.5);
        }
        vm.$store.dispatch(GET_ANNTATION, document_id).then(res2 => {
          if (res2.status && res2.annotations && res2.annotations.length > 0) {
            vm.annotations = res2.annotations;
          }
        });
      });
    }
  },
};
</script>

<style lang="scss">
@import "./TestSignCheck.scss";
</style>

