<template>
  <div class="app flex-row">
    <div class="w-100">
      <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $t("docsign.prepare.title") }}</h1>
        <div class="d-flex align-items-center control-actions">
          <b-button variant="outline-primary" @click="undo">
            <!-- <UserIcon icon="past_changes.svg" :button="true"/> -->
            <template>
              <img src="img/icons/past_changes.svg" class="icon-hover" />
            </template>
          </b-button>
          <b-button variant="outline-primary" @click="redo">
            <!-- <UserIcon icon="next_changes.svg" :button="true" /> -->
            <template>
              <img src="img/icons/next_changes.svg" class="icon-hover" />
            </template>
          </b-button>
          <UserSelect
            v-bind:value="percent"
            v-bind:items="['10%','20%', '30%', '50%', '75%','100%', '200%']"
            @changeValue="changePercent"
            class="mb-0 mx-2"
          />
          <b-button
            variant="outline-primary"
            @click="commentsHandle"
            v-bind:class="commentButtonActive && 'commentButtonActive'"
          >
            <UserIcon icon="comment.svg" :button="true" />
          </b-button>
        </div>
      </div>
      <hr class="mb-4" />
      <div class="row">
        <div class="col-md-9 pr-3 pr-sm-0">
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
        </div>

        <div class="col-md-3 pl-3">
          <div class="aaa">
            <div class="content-container">
              <div class="documents">
                <div class="title">
                  <span>{{ $t("docsign.documents") }}</span>
                  <!-- <div class="actions">
                    <i class="fa fa-rotate-left mr-2 clickable-icon" @click="rotateHandle('left')"></i>
                    <i
                      class="fa fa-rotate-right mr-2 clickable-icon"
                      @click="rotateHandle('right')"
                    ></i>
                    <i class="fa fa-trash clickable-icon" @click="deleteHandle"></i>
                  </div>-->
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
                          <i
                            class="fa fa-rotate-left mr-2 clickable-icon"
                            @click="rotateHandle('left', file.id, numpage(image))"
                          />
                          <i
                            class="fa fa-rotate-right mr-2 clickable-icon"
                            @click="rotateHandle('right', file.id, numpage(image))"
                          />
                          <i
                            class="fa fa-trash clickable-icon"
                            @click="deleteHandle(file.id, image)"
                          ></i>
                          <div class="page-no pull-right">{{imgKey + 1}}</div>
                        </div>
                      </div>
                    </b-collapse>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-end flex-wrap pt-4">
              <button
                class="btn btn-outline-primary min-width-124px m-1"
                v-on:click="moveBackPage()"
              >{{ $t("docsign.back") }}</button>
              <button
                class="btn btn-primary min-width-124px m-1"
                v-on:click="moveNextPage()"
                style="position: relative"
              >
                <i
                  v-if="nextLoading"
                  class="fa fa-spinner fa-spin fa-3x"
                  style="position: absolute; top: 0; left: 33%;"
                />
                {{ $t("docsign.next") }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "bootstrap/dist/js/bootstrap.min.js";
import "bootstrap-multiselect/dist/js/bootstrap-multiselect.js";
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
  ADD_DELETE_DOCUMENTS
} from "../../store/modules/document";
import store from "../../store/store";
import { GET_DOCS } from "../../store/actions.type";
import pdf from "vue-pdf";
import draggable from "vuedraggable";
import { addParamsToBlob } from "../../helpers";
import {
  prepareHandle,
  generalDefaultButton,
  initialPrepare,
  history,
  unredoButton,
  userSideBarHandle,
  addCommentToDocument,
  rotateFunction,
  deleteSuccessHandle
} from "../../helpers/prepareHandle";
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
      recipientsList: []
    };
  },
  mounted() {
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

    store.dispatch(GET_ANNTATION, document_id).then(res => {
      if (res.status && res.annotations && res.annotations.length > 0) {
        vm.annotations = res.annotations;
        EventBus.$on("recipientsList", recipients => {
          this.recipientsList = recipients;
          recipients &&
            generalDefaultButton(vm.annotations, recipients, this.prepareEvent);
        });
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
    deleteHandle(docId, imgUrl) {
      let numPage = this.numpage(imgUrl);
      if(numPage) {
        this.pageLoading = false;
        let sendData = {
          document_id: docId,
          page: numPage,
          delete: 1
        };
        this.$store.dispatch(ADD_DELETE_DOCUMENTS, sendData).then(res => {
          if (res.status) {
            this.pageLoading = true;
            deleteSuccessHandle(docId, numPage);
            this.initialFunction();
          }
        });
      }
    },
    numpage(imgUrl) {
      let arrayImgUrl = imgUrl.split("/").pop();
      return parseInt(arrayImgUrl.split(".")[0].slice(-1));
    },
    checkHasDeletePage(document_action, pageId) {
      let _result =
        document_action.length > 0 &&
        document_action.find(v => v.page === pageId && v.delete === 1);
      return !!_result;
    },
    rotateHandle(location, docId, pageId) {
      let vm = this;

      rotateFunction(location, docId, pageId, sendData => {
        vm.$store.dispatch(ADD_ROTATE_DOCUMENTS, sendData);
      });
    },
    checkRotate(data, document) {
      let getRotate =
        document &&
        document.document_action &&
        document.document_action.length > 0 &&
        document.document_action.find(v => v.page === data.pageNum + 1);
      return getRotate && getRotate.rotate;
    },
    undo() {
      let vm = this;
      let document_id = vm.$route.query.document_id;
      history.undo(vm.annotations);
      if (history && history.restore_state && history.restore_state.id) {
        const annotations_id = history.restore_state.id;
        unredoButton(annotations_id, "undo");
        vm.$store
          .dispatch(EDIT_ANNTATION, { id: annotations_id, display: "none" })
          .then(res => {
            if (res.status && res.annotation_id) {
              vm.$store.dispatch(GET_ANNTATION, document_id).then(res => {
                if (
                  res.status &&
                  res.annotations &&
                  res.annotations.length > 0
                ) {
                  vm.annotations = res.annotations;
                }
              });
            }
          });
      }
    },
    redo() {
      let vm = this;
      let document_id = vm.$route.query.document_id;
      history.redo();
      if (history && history.restore_state && history.restore_state.id) {
        const annotations_id = history.restore_state.id;
        unredoButton(annotations_id, "redo");
        vm.$store
          .dispatch(EDIT_ANNTATION, { id: annotations_id, display: "block" })
          .then(res => {
            if (res.status && res.annotation_id) {
              vm.$store.dispatch(GET_ANNTATION, document_id).then(res => {
                if (
                  res.status &&
                  res.annotations &&
                  res.annotations.length > 0
                ) {
                  vm.annotations = res.annotations;
                }
              });
            }
          });
      }
    },
    commentsHandle() {
      addCommentToDocument(
        this.pages.map((v, key) => key),
        this.recipientsList,
        this.prepareEvent
      );
    },
    changePercent(e) {
      this.percent = e;
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
    onActivate(target) {
      // console.log("target", target);
    },
    selectPage(data, imgKey) {
      let scrollId = `doc_id_${data.id}_${imgKey}`;
      var scrollTo = document.getElementById(scrollId);
      scrollTo.scrollIntoView();
    },
    moveNextPage() {
      if (!this.checkUserTool()) {
        this.$awn.alert("Add at least 1 tool", {
          position: "bottom-left",
          labels: {
            alert: "Danger Message"
          }
        });
        return false;
      }
      this.nextLoading = true;
      this.$router.push(
        "/docu-sign/review?document_id=" + this.$route.query.document_id
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
    addRecipient() {
      this.recipients.push({
        sign_type: "Need to sign",
        com_type: false,
        name: "",
        email: "",
        set_password: false,
        password: "",
        confirm_password: ""
      });
    },
    removeRecipient(index) {
      this.recipients.splice(index, 1);
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
  created() {}
};
</script>

<style lang="scss">
@import "./Prepare.scss";
</style>
