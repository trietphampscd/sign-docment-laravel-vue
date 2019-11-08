<template>
  <div class="app">
    <div class="app-body">
      <AppSidebar fixed>
        <AppLogo />
        <div class="p-3 side-menu">
          <div class="w-100">
            <b-button block variant="other" v-on:click="gotoStartPage()">{{ $t("Start Now") }}</b-button>
            <b-button
              variant="primary"
              class="d-block d-sm-none"
              block
              style="padding-top: 0.5rem;"
              to="/payment/pricing-plan"
            >{{ $t("Upgrade Your Plan") }}</b-button>
            <div class="prepare-tool-nav">
              <hr class="seperate-bar" />
              <SidebarNav :navItems="computedNav"></SidebarNav>
            </div>
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
            <span class="comments ml-3 mr-1">{{ $t("Your current plan") }}:</span>
            <span>
              <UserIcon icon="smile.png" class="mr-2" />
              {{ $t("Free") }}
            </span>
          </div>
          <div class="sign-doc-type">
            <div
              class="clickable-text mx-1 mx-sm-auto"
              v-on:click="gotoPage('/payment/document-list')"
            >{{ $t("DOCUMENTS") }}</div>
            <div class="clickable-text mx-1 mx-sm-auto">{{ $t("TEMPLATES") }}</div>
          </div>
          <DefaultHeaderDropdownAccnt />
          <!--<AsideToggler class="d-lg-none" mobile />-->
        </AppHeader>
        <div class="container-fluid main-container">
          <router-view></router-view>
        </div>
      </main>
      <AppAside fixed>
        <!--aside-->
        <DefaultAside />
      </AppAside>
    </div>
    <TheFooter>
      <!--footer-->
      <div>
        <span class="ml-1">&copy; 2019 CoffeeSign All rights reserved..</span>
      </div>
    </TheFooter>
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
  Breadcrumb
} from "@coreui/vue";
import DefaultAside from "./DefaultAside";
import DefaultHeaderDropdown from "./DefaultHeaderDropdown";
import DefaultHeaderDropdownNotif from "./DefaultHeaderDropdownNotif";
import DefaultHeaderDropdownAccnt from "./DefaultHeaderDropdownAccnt";
import DefaultHeaderDropdownMssgs from "./DefaultHeaderDropdownMssgs";
import DefaultHeaderDropdownTasks from "./DefaultHeaderDropdownTasks";
import UserIcon from "../components/UserIcon";
import UpgradePlan from "./UpgradePlan";
import Logout from "../components/Logout";
import AppLogo from "../components/AppLogo";
import { landingNavList } from "../helpers/defaultValue";

export default {
  name: "DocumentsContainer",
  components: {
    AppLogo,
    Logout,
    UserIcon,
    UpgradePlan,
    AsideToggler,
    AppHeader,
    AppSidebar,
    AppAside,
    TheFooter,
    Breadcrumb,
    DefaultAside,
    DefaultHeaderDropdown,
    DefaultHeaderDropdownMssgs,
    DefaultHeaderDropdownNotif,
    DefaultHeaderDropdownTasks,
    DefaultHeaderDropdownAccnt,
    SidebarForm,
    SidebarFooter,
    SidebarToggler,
    SidebarHeader,
    SidebarNav,
    SidebarMinimizer
  },
  data() {
    return {
      show_tool_menu: true
    };
  },
  computed: {
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
  mounted() {},
  methods: {
    addTranslateNav(lists = []) {
      return lists.map(v => {
        return { ...v, name: this.$t(v.name) };
      });
    },
    clickMain(e) {
      if (e.target.className == "main") {
        this.$refs.sidebarToggleBtn.toggle();
      }
    },
    gotoPage(page) {
      this.$router.push({ path: page });
    },
    gotoStartPage() {
      this.$router.push({
        path: "/docu-sign/add-document",
        query: { withoutModal: true }
      });
    }
  },
  watch: {
    $route(to) {
      if (to.fullPath == "/prepare") {
        this.show_tool_menu = true;
      } else {
        this.show_tool_menu = false;
      }
    },
    columns(val) {
      console.log("aaaa");
      // this.fieldsForTable = this.filterColumns(val);
    }
  }
};
</script>
<style lang="scss">
@import "./PaymentContainer.scss";
</style>

