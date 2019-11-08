<template>
  <div class="app">
    <div class="app-body payment-container">
      <AppSidebar fixed>
        <AppLogo />
        <div class="p-3 side-menu">
          <div class="sidebar-auto-scoroll">
            <b-button block variant="other" v-on:click="gotoStartPage()">
              {{ getSelected('temp')=="selected" ? "Create Template" :  $t('layout.payment.sidebar.button')  }}
            </b-button>
            <b-button
              variant="primary"
              class="d-block d-sm-none"
              block
              style="padding-top: 0.5rem;"
              v-on:click="gotoPage('/payment/pricing-plan'); toggleSidebar();"
            >{{ $t('layout.button') }}</b-button>
            <div class="prepare-tool-nav">
              <hr class="seperate-bar" />
              <SidebarNav :navItems="computedNav"></SidebarNav>
            </div>
            <!-- <template  v-if="show_add_folder"> -->
              <hr class="seperate-bar" />
              <div class="user-sidebar-folders">
                <SideFolders :folders="folders" :layer_id="''"/>
              </div>
            <!-- </template> -->
          </div>
          <Logout></Logout>
        </div>
      </AppSidebar>
      <main class="main" v-on:click="clickMain">
        <AppHeader class="pr-3">
          <SidebarToggler ref="sidebarToggleBtn" class="d-lg-none" display="md" mobile />
          <!-- <SidebarToggler class="d-md-down-none" display="lg" :defaultOpen=true /> -->
          <UpgradePlan class="d-none d-sm-block"></UpgradePlan>
          <div class="your-cur-plan">
            <span class="comments ml-3 mr-1">{{ $t('layout.payment.header.comments') }}</span>
            <span>
              <UserIcon icon="smile.png" class="mr-2" />{{ $t('layout.payment.header.icon') }}
            </span>
          </div>
          <div class="sign-doc-type">
            <div
              class="clickable-icon mx-1 mx-1 mx-sm-2 mx-md-2 mx-lg-4"
              v-on:click="gotoPage('/payment/document-list')"
              v-bind:class="getSelected('doc')"
            >{{ $t('layout.payment.header.documents') }}</div>
            <div
              class="clickable-icon mx-1 mx-sm-2 mx-md-2 mx-lg-4 "
              v-on:click="gotoPage('/payment/template-list')"
              v-bind:class="getSelected('temp')"
            >{{ $t('layout.payment.header.templates') }}</div>
          </div>
          <DefaultHeaderDropdownAccnt />
          <!--<AsideToggler class="d-lg-none" mobile />-->
        </AppHeader>
        <div class="container-fluid main-container">
          <router-view></router-view>
        </div>
      </main>
    </div>
    <TheFooter>
      <!--footer-->
      <div>
        <span class="ml-1">&copy; 2019 CoffeeSign All rights reserved</span>
      </div>
    </TheFooter>
    <b-modal id="rename-folder-modal" ref="rename-folder-modal" hide-footer centered size="md">
      <div class="rename-folder-modal">
        <div class="title">{{folder_op_type=="new"?"Create":"Rename"}} Folder</div>
        <div class="form-group">
          <input
            type="text"
            class="form-control"
            id="folder_rename"
            placeholder="Folder Name"
            name="folder_rename"
            v-model="folder_rename"
          />
        </div>
        <hr>
        <b-button v-on:click="enterName()" variant="primary" class="min-width-136px">{{folder_op_type=="new"?"Create":"Rename"}}</b-button>
      </div>
    </b-modal>
    
    <b-modal id="move-folder-modal" ref="move-folder-modal" hide-footer centered size="md">
      <div class="rename-folder-modal">
        <div class="title">Move Folder</div>
        <div class="form-group">
          <SideFoldersMove :folders="folders" :layer_id="''" :move_id="move_id"/>
        </div>
        <hr>
        <b-button v-on:click="moveAFolder()" variant="primary" class="min-width-136px">Move</b-button>
      </div>
    </b-modal>
    <b-modal id="delete-folder-modal" ref="delete-folder-modal" hide-footer centered size="md">
      <div class="rename-folder-modal">
        <div class="title">Delete Folder</div>
        <div class="form-group">
          <span>Are you sure delete folder?</span>
        </div>
        <hr>
        <b-button v-on:click="deleteFolder()" variant="primary" class="min-width-136px">Delete</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import {
  Header as AppHeader,
  SidebarToggler,
  Sidebar as AppSidebar,
  SidebarFooter,
  SidebarForm,
  SidebarHeader,
  SidebarMinimizer,
  SidebarNav,
  Aside as AppAside,
  AsideToggler,
  Footer as TheFooter,
} from "@coreui/vue";
import DefaultHeaderDropdownAccnt from "./DefaultHeaderDropdownAccnt";
import UserIcon from "../components/UserIcon";
import UpgradePlan from "./UpgradePlan";
import Logout from "../components/Logout";
import AppLogo from "../components/AppLogo";
import SideFolders from "../components/SideFolders";
import SideFoldersMove from "../components/SideFoldersMove";
import { EventBus } from '../config/event-bus';
import { landingNavList } from "../helpers/defaultValue";
import { restructure } from "../helpers/restructure";

import store from '../store/store'
import { mapGetters } from "vuex";
import {
  FOLDER_GET_REQUEST,
  FOLDER_ADD_REQUEST,
  FOLDER_RENAME_REQUEST,
  FOLDER_DELETE_REQUEST
} from "../store/actions.type";

export default {
  name: "DocumentsContainer",
  components: {
    SideFolders,
    AppLogo,
    Logout,
    UserIcon,
    UpgradePlan,
    AsideToggler,
    AppHeader,
    AppSidebar,
    AppAside,
    TheFooter,
    DefaultHeaderDropdownAccnt,
    SidebarForm,
    SidebarFooter,
    SidebarToggler,
    SidebarHeader,
    SidebarNav,
    SidebarMinimizer,
    SideFoldersMove
  },
  data() {
    return {
      show_add_folder: false,
      current_item: '',
      folder_rename: '',
      folder_remove_id: 0,
      folder_op_type: 'new',
      nav: [
        {
          name: "Account",
          url: "/profile/account",
          icon: "fa fa-user"
        },
        {
          name: "Signature",
          url: "/signature",
          icon: "fa fa-pencil"
        },
        {
          name: "Pricing Plan",
          url: "/payment/upgrade-plan",
          icon: "fa fa-tag"
        },
        {
          name: "Branding",
          url: "/profile/custom-branding",
          icon: "fa fa-id-card"
        }
      ],
      folders: [{
        id: 0,
        name: "Folders",
        parent_id: null,
        children: []
      }],
      show_tool_menu: true,
      id_folder_del: 0,
      move_id: null,
    };
  },
  computed: {
    ...mapGetters({
      get_folders: 'get_folders',
      folder_add: 'folder_add'
    }),
    computedNav() {
      return this.addTranslateNav(landingNavList);
    },
    name() {
      return this.$route.name;
    },
    list() {
      return this.$route.matched.filter(
        route => route.name || route.meta.label
      );
    }
  },
  created() {
    this.getFolders()
  },
  mounted() {
    if (this.$router.history.current.fullPath == "/payment/document-list") {
      this.show_add_folder = true;
    } else {
      this.show_add_folder = false;
    }
    this.$root.$on('renameFolder', (layer_id, name) => {
      this.folder_rename = name;
      this.folder_op_type = "rename";
      this.openRenameDialog(layer_id);
    });
    this.$root.$on('newFolder', (layer_id) => {
      this.folder_rename = "New Folder";
      this.folder_op_type = "new";
      this.openRenameDialog(layer_id);
    });
    this.$root.$on('moveFolder', (layer_id, id) => {
      this.folder_op_type = "move";
      this.openMoveDialog(layer_id, id);
      this.move_id = id;

    });
    this.$root.$on('removeFolder', (layer_id, index, id) => {
      this.folder_op_type = "remove";
      this.folder_remove_id = index;
      this.openDeleteDialog(layer_id, index, id);
      // this.renameFolder(this.folders, layer_id, id);
    });
    this.toggleSidebar();
  },
  methods: {
    getFolders() {
      let vm = this;
      store.dispatch(FOLDER_GET_REQUEST)
      .then( res => {
        let get_folders = vm.get_folders;
        let data_res = restructure(get_folders);
        vm.folders[0].children = data_res;
      })
      .catch( err => {

      });
    },
    addTranslateNav(lists = []) {
      return lists.map(v => {
        return { ...v, name: this.$t(v.name) };
      });
    },
    toggleSidebar() {
      if(window.innerWidth<500){
        console.log("toggled sidebar in paymentContainer");
        this.$refs.sidebarToggleBtn.toggle();
      }
    },
    renameFolder(folders, layer_id) {
      let vm  = this
      const pos = layer_id.indexOf('/', 1);
      const no = parseInt(layer_id.substr(1, pos));
      if(pos<0){
        const no1 = parseInt(layer_id.substr(1));
        if(vm.folder_op_type == "rename") {
          const folder_rename = vm.folder_rename;
          store.dispatch(FOLDER_RENAME_REQUEST, {id: folders[no1].id, doc_folder_name: folder_rename})
          .then( res => {
            res && res.status && this.getFolders();
          })
          .catch( err => {
            console.log('err-------', err)
          });
        } else if(this.folder_op_type == "new") {
          const folder_rename = this.folder_rename;
          store.dispatch(FOLDER_ADD_REQUEST, {parent_id: folders[no1].id, doc_folder_name: folder_rename})
          .then( res => {
            res && res.status && this.getFolders();
            // folders[no1].children.push({name: res.data.doc_folder_name, parent_id: res.data.parent_id, id: res.data.id, children: []})
          })
          .catch( err => {
            console.log('err-------', err)
          });
        } else if(this.folder_op_type == "remove") {
          let folder_remove_id = this.folder_remove_id
          store.dispatch(FOLDER_DELETE_REQUEST, this.id_folder_del)
          .then( res => {
            // res && res.status && this.getFolders();
            if( res && res.status) {
              folders[no1].children.splice(parseInt(folder_remove_id), 1);
            }
          })
          .catch( err => {
            console.log('err-------', err)
          });
        }

        this.folder_rename = "";
      } else {
        layer_id = layer_id.substr(pos);
        this.renameFolder(folders[no].children, layer_id);
      }
    },
    deleteFolder() {
      this.renameFolder(this.folders, this.current_item);
      this.$refs['delete-folder-modal'].hide();
    },
    openDeleteDialog(layer_id, index, id) {
      this.current_item = layer_id;
      this.id_folder_del = id;
      this.$refs['delete-folder-modal'].show();
    },
    enterName() {
      this.renameFolder(this.folders, this.current_item);
      this.$refs['rename-folder-modal'].hide();
    },
    openRenameDialog(layer_id) {
      this.current_item = layer_id;
      this.$refs['rename-folder-modal'].show();
    },
    openMoveDialog(layer_id, id) {
      this.current_item = layer_id;
      this.$refs['move-folder-modal'].show();
    },
    moveAFolder() {
      let folder_id = parseInt(sessionStorage.getItem('folder_id'));
      let parent_id = parseInt(sessionStorage.getItem('parent_id'));
      store.dispatch(FOLDER_RENAME_REQUEST, {id: folder_id, parent_id: parent_id  == 0 ? null : parent_id})
        .then( res => {
          this.$refs['move-folder-modal'].hide();
          res && res.status && this.getFolders();
        })
        .catch( err => {
          console.log('err-------', err)
        });
    },
    getSelected(doc_type) {
      if(doc_type == 'doc' && this.$router.history.current.fullPath == "/payment/document-list") {
        return "selected"
      } else if(doc_type == 'temp' && this.$router.history.current.fullPath == "/payment/template-list") {
        return "selected"
      }
      return "";
    },
    clickMain(e) {
      if (e.target.className == "main") {
        this.toggleSidebar
      }
    },
    gotoPage(page) {
      this.$router.push({ path: page });
    },
    gotoStartPage() {
      console.log(this.$route)
      if(this.$route.path === "/landing"){
        EventBus.$emit('IS_LANDING', true);
      } else {
        EventBus.$emit('IS_LANDING', false);
      }
      this.$router.push({
        path: "/docu-sign/add-document"
      });
    },
  },
  watch: {
    $route(to) {
      if (to.fullPath == "/prepare") {
        this.show_tool_menu = true;
      } else {
        this.show_tool_menu = false;
      }
      
      if (this.$router.history.current.fullPath == "/payment/document-list") {
        this.show_add_folder = true;
      } else {
        this.show_add_folder = false;
      }
    }
  }
};
</script>
<style lang="scss">
@import "./PaymentContainer.scss";
</style>