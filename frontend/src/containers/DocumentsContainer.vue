<template>
  <div class="app">
    <div class="app-body">
      <AppSidebar fixed>
        <AppLogo></AppLogo>
        <div class="p-3 side-menu">
          <div class="w-100">
            <b-button block variant="primary" v-on:click="gotoPage('/landing')">{{ $t('layout.documents.sidebar.button') }}</b-button>

            <b-button
              variant="primary"
              class="d-block d-sm-none"
              block
              style="padding-top: 0.5rem;"
              to="/payment/pricing-plan"
            >{{ $t('layout.button') }}</b-button>
            <div class="prepare-tool-nav" v-if="show_tool_menu">
              <hr class="seperate-bar" />
              <UserSidebarEx :navItems="nav" @dragTool="dragTool"></UserSidebarEx>
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
          <div class="doc-timeline">
            <div class="timeline-item">
              <i :class="getStyle(0)" />
              <span class="timeline-title" :class="getTextStyle(0)">{{ $t('layout.documents.header.upload') }}</span>
              <div class="header-radio-dashline"></div>
            </div>
            <div class="timeline-item">
              <i :class="getStyle(1)" />
              <span class="timeline-title" :class="getTextStyle(1)">{{ $t('layout.documents.header.recipients') }}</span>
              <div class="header-radio-dashline"></div>
            </div>
            <div class="timeline-item">
              <i :class="getStyle(2)" />
              <span class="timeline-title" :class="getTextStyle(2)">{{ $t('layout.documents.header.prepare') }}</span>
              <div class="header-radio-dashline"></div>
            </div>
            <div class="timeline-item">
              <i :class="getStyle(3)" />
              <span class="timeline-title" :class="getTextStyle(3)">{{ $t('layout.documents.header.review') }}</span>
            </div>
          </div>
          <DefaultHeaderDropdownAccnt />
        </AppHeader>
        <div class="container-fluid main-container">
          <router-view></router-view>
        </div>
      </main>
      <!-- <AppAside fixed>
        <DefaultAside />
      </AppAside> -->
    </div>
    <TheFooter>
      <!--footer-->
      <div>
        <span class="ml-1">&copy; 2019 CoffeeSign All rights reserved..</span>
      </div>
    </TheFooter>
    <b-modal
      id="modal-1"
      ref="welcomemodal"
      @hide="hideModal"
      hide-footer
      centered
      hide-header
      size="lg"
    >
      <div class="welcome-modal">
        <div class="welcome-header">
          <img class="navbar-brand-full" src="img/logo_white.svg" alt height="65" width="200" />
        </div>
        <div class="welcome-body">
          <h1>{{ $t('layout.documents.modal.title') }}</h1>
          <div class="subtitle">{{ $t('layout.documents.modal.subtitle') }}</div>
          <div class="summary">{{ $t('layout.documents.modal.summary') }}</div>
          <div class="w-100">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="first_name"
                    :placeholder="firstName"
                    name="first_name"
                    v-bind:class="{'input-error': isError(form_data.first_name)}"
                    v-model="form_data.first_name"
                  />
                  <div
                    v-if="isError(form_data.first_name)"
                    class="error-text"
                  >{{ $t('layout.documents.modal.error.name.first') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="last_name"
                    :placeholder="laststName"
                    name="last_name"
                    v-bind:class="{'input-error': isError(form_data.last_name)}"
                    v-model="form_data.last_name"
                  />
                  <div v-if="isError(form_data.last_name)" class="error-text">{{ $t('layout.documents.modal.error.name.last') }}</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <template>
                <ul class="user-custom-select" 
                    v-bind:class="{'input-error': form_data.purpose == 'Purpose of using' && form_data.error_flag}"
                >
                  <HeaderDropdown>
                    <template slot="header">
                      <div style="width:100%; overflow:hidden;">{{$t(form_data.purpose)}}</div>
                    </template>
                    <template slot="dropdown" >
                      <b-dropdown-item v-on:click="changePurposeValue('', 'Purpose of using')">{{ $t('layout.documents.modal.purpose') }}</b-dropdown-item>
                      <b-dropdown-item
                        v-for="(purp, index) in computedPurposeSizes"
                        :key="index"
                        :value="purp.value ? purp.value : ''"
                        @click="changePurposeValue(purp.value, purp.name)"
                      >{{$t(purp.name)}}</b-dropdown-item>
                    </template>
                  </HeaderDropdown>
                </ul>
                <div v-if="form_data.purpose == 'Purpose of using' && form_data.error_flag" class="error-text">{{ $t('layout.documents.modal.error.purpose') }}</div>
              </template>
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="company_name"
                    :placeholder="company"
                    name="company_name"
                    v-model="form_data.company_name"
                    v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.company_name)}"
                  />
                   <!-- v-bind:class="{'input-error': isError(form_data.company)}" -->
                  <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.company_name)" class="error-text">{{ $t('layout.documents.modal.error.company') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <template>
                  <ul class="user-custom-select"
                      v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.employee == 'Employee' && form_data.error_flag}"
                  >
                    <HeaderDropdown>
                      <template slot="header">
                        <div style="width:100%; overflow:hidden;">{{$t(form_data.employee == "1001-3000" ? $t('layout.documents.modal.more') : form_data.employee)}}</div>
                      </template>
                      <template slot="dropdown">
                        <b-dropdown-item v-on:click="changeEmployeeValue('', 'Employee')">{{ $t('layout.documents.modal.employee') }}</b-dropdown-item>
                        <b-dropdown-item
                          v-for="(emp, index) in employeeSizes"
                          :key="index"
                          :value="emp.id ? emp.id : ''"
                          v-on:click="changeEmployeeValue(emp.id, emp.size)"
                        >{{emp.size == "1001-3000" ? $t('layout.documents.modal.more') : emp.size}}</b-dropdown-item>
                      </template>
                    </HeaderDropdown>
                  </ul>
                  <div v-if="form_data.purpose == 'My Business' && form_data.employee == 'Employee' && form_data.error_flag" class="error-text">{{$t('layout.documents.modal.error.employee') }}</div>
                </template>
              </div>
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="job_title"
                    :placeholder="jobTitle"
                    name="job_title"
                    v-model="form_data.title"
                    v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.title)}"
                  />
                  <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.title)" class="error-text">{{$t('layout.documents.modal.error.title') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <template>
                    <ul class="user-custom-select"
                      v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.industry == 'Select Industry' && form_data.error_flag}"
                  >
                      <HeaderDropdown>
                        <template slot="header">
                          <div style="width:100%; overflow:hidden;">{{$t(form_data.industry)}}</div>
                        </template>
                        <template slot="dropdown">
                          <b-dropdown-item v-on:click="changeIndustryValue('', 'Select Industry')">{{ $t('layout.documents.modal.industry') }}</b-dropdown-item>
                          <b-dropdown-item
                            v-for="(indus, index) in industrySizes"
                            :key="index"
                            :value="indus.id ? indus.id : ''"
                            v-on:click="changeIndustryValue(indus.id, indus.industry_name)"
                          >{{$t(indus.industry_name) }}</b-dropdown-item>
                        </template>
                      </HeaderDropdown>
                    </ul>
                    <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && form_data.industry == 'Select Industry'" class="error-text">{{$t('layout.documents.modal.error.industry') }}</div>
                  </template>
                </div>
              </div>
            </div>
            <button v-on:click="getStarted()" class="btn btn-primary w-100">{{ $t('layout.documents.modal.button') }}</button>
          </div>
        </div>
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
  Breadcrumb,
  HeaderDropdown
} from "@coreui/vue";
import DefaultAside from "./DefaultAside";
import DefaultHeaderDropdown from "./DefaultHeaderDropdown";
import DefaultHeaderDropdownNotif from "./DefaultHeaderDropdownNotif";
import DefaultHeaderDropdownAccnt from "./DefaultHeaderDropdownAccnt";
import DefaultHeaderDropdownMssgs from "./DefaultHeaderDropdownMssgs";
import DefaultHeaderDropdownTasks from "./DefaultHeaderDropdownTasks";
import UpgradePlan from "./UpgradePlan";
import Logout from "../components/Logout";
import UserSelect from "../components/UserSelect";
import AppLogo from "../components/AppLogo";
import UserSidebarEx from "../components/UserSidebarEx";
import { firstDocuSign } from '../mixins/firstDocuSign';
import { EventBus } from '../config/event-bus';
import { purposeSizes } from "../helpers/defaultValue";

import { mapGetters, mapState } from 'vuex'
import store from '../store/store'
import { CHECK_CLIENT } from '../store/actions.type'


export default {
  name: "DocumentsContainer",
  components: {
    UserSidebarEx,
    AppLogo,
    UserSelect,
    Logout,
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
    SidebarMinimizer,
    HeaderDropdown
  },
  mixins: [firstDocuSign],
  data() {
    return {
      currentStepNo: 0,
      confirmed: false,
      form_data: {
        error_flag: false,
        first_name: "",
        last_name: "",
        company_name: "",
        purpose: "Purpose of using",
        employee: "Number of Employees",
        title: "",
        industry: "Select Industry",
      },
      client_form: {
        first_name    : " ",
        last_name     : " ",
        company_name  : " ",
        account_type  : "",
        company_size_id:1,
        industry_id   :1,
        department_name :""
      },
      employeeSizes : [],
      purposeSizes : [
        {
          value: "Personnel",
          name: "My Personnel use"
        },
        {
          value: "Business",
          name: "My Business"
        }
      ],
      industrySizes : [],
      nav: [
        {
          name: "Roger Waters",
          icon: "fa fa-user",
          color: "#ee9964"
        },
        {
          name: "Barrett Nash-Willi",
          icon: "fa fa-user",
          color: "#60ccd8"
        },
        {
          name: "Barrett Nash-Willi",
          icon: "fa fa-user",
          color: "#d5c45c"
        }
      ],
      show_tool_menu: false,
      icon_next_step: "['fas', 'check-circle']",
    };
  },
  computed: {
    ...mapGetters(["_isClient"]),
    name() {
      return this.$route.name;
    },
    list() {
      return this.$route.matched.filter(
        route => route.name || route.meta.label
      );
    },
    computedPurposeSizes() {
      // return this.addTranslate(this.purposeSizes);
      return this.purposeSizes;
    },
    firstName() {
      return this.$t('profile.account.placeholder.first');
    },
    laststName() {
      return this.$t('profile.account.placeholder.last');
    },
    company() {
      return this.$t('profile.account.placeholder.company');
    },
    jobTitle() {
      return this.$t('profile.account.placeholder.job');
    }
  },
  created() {
  },
  mounted() {
    let vm = this;
    store.dispatch(CHECK_CLIENT)
      .then( res => {
        if(!this._isClient){
          this.$refs["welcomemodal"].show();
        }
        if(res.status && res.client){
          EventBus.$emit('clientInfo', res.client);
        }
      })
      .catch( err => {
        if(!this._isClient){
          this.$refs["welcomemodal"].show();
        }
      });
    this.confirmed = false;
    // if (!this.$route.query.withoutModal) {
    //   this.$refs["welcomemodal"].show();
    // }
    this.setOptions();
    this.getCompanySizesDoc();
    this.getIndustriesDoc();
    // this.getDepartmentsDoc();
  },
  methods: {
    addTranslate(lists = []) {
      return lists.map(v => {
        return { ...v, name: this.$t(v.name) };
      });
    },
    getCompanySizesDoc() {
      var vm = this;
      vm.getCompanySizes()
        .then( res => {
          this.employeeSizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    getIndustriesDoc() {
      var vm = this;
      vm.getIndustries()
        .then( res => {
          this.industrySizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    getDepartmentsDoc() {
      var vm = this;
      vm.getDepartments()
        .then( res => {
          // this.purposeSizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    dragTool() {
      this.$refs.sidebarToggleBtn.toggle();
    },
    clickMain(e) {
      if (e.target.className == "main") {
        this.$refs.sidebarToggleBtn.toggle();
      }
    },
    hideModal(e) {
      if (!this.confirmed) {
        e.preventDefault();
        this.gotoPage("/landing");
      }
    },
    getStarted() {
      var vm = this;
      vm.form_data.error_flag = true;
      if (vm.isError(vm.form_data.first_name)) return;
      if (vm.isError(vm.form_data.last_name)) return;
      if(vm.form_data.purpose != this.$t("Purpose of using")){
        if (vm.form_data.industry === 'My Personnel use' && (vm.isError(vm.form_data.title) || vm.isError(vm.form_data.last_name) || vm.form_data.industry === 'Select Industry' || vm.form_data.employee == 'Employee')) return;
        vm.client_form.first_name = vm.form_data.first_name;
        vm.client_form.last_name = vm.form_data.last_name;
        vm.client_form.company_name = vm.form_data.company_name;
        vm.client_form.department_name = vm.form_data.title;
        
        vm.createClient(vm.client_form)
          .then( res => {
            if(res.data.status) {
              this.confirmed = true;
              this.$refs["welcomemodal"].hide();  
            }
          })
          .catch( error => {
            console.log('error', error)
          })
      }

    },
    changePurposeValue(key, value) {
      this.client_form.account_type = key;
      this.form_data.purpose = value;
    },
    changeIndustryValue(key, value) {
      this.client_form.industry_id = key;
      this.form_data.industry = value;
    },
    changeEmployeeValue(key, value) {
      this.client_form.company_size_id = key;
      this.form_data.employee = value;
    },
    isError(value) {
      return (
        this.form_data.error_flag &&
        ( value === undefined ||
          value === null ||
          (typeof value === "object" && Object.keys(value).length === 0) ||
          (typeof value === "string" && value.trim().length === 0)
        )
      );
    },
    gotoPage(page) {
      this.$router.push({ path: page });
    },
    getStyle(no) {
      // if(true && this.currentStepNo == no) {
      //   return "fa fa-check-circle ready";
      // }else 
      if (this.currentStepNo == no) {
        return "fa fa-circle-o ready";
      } else if (this.currentStepNo > no) {
        return "fa fa-check-circle ready";
      } else {
        return "fa fa-circle-o waiting";
      }
    },
    normalizeIconArgs (no) {
      if (this.currentStepNo == no) {
        return { prefix: 'fas', iconName: 'circle' };
      } else if (this.currentStepNo > no) {
        return { prefix: 'fas', iconName: 'check-circle' };
      } else {
        return { prefix: 'fas', iconName: 'circle' };
      }
    },
    getTextStyle(no) {
      if (this.currentStepNo >= no) {
        return "";
      } else {
        return "waiting";
      }
    },
    setOptions() {
      // console.log(this.$router.history.current.fullPath, "/docu-sign/add-document?document_id="+this.$route.query.document_id)
      if (this.$router.history.current.fullPath == "/docu-sign/add-document?document_id="+this.$route.query.document_id) {
        this.currentStepNo = 0;
      } else if (
        this.$router.history.current.fullPath == "/docu-sign/add-recipients?document_id="+this.$route.query.document_id
      ) {
        this.currentStepNo = 1;
      } else if (
        this.$router.history.current.fullPath == "/docu-sign/prepare?document_id="+this.$route.query.document_id
      ) {
        this.currentStepNo = 2;
      } else if (this.$router.history.current.fullPath == "/docu-sign/review?document_id="+this.$route.query.document_id) {
        this.currentStepNo = 3;
      }

      if (this.$router.history.current.fullPath == "/docu-sign/prepare?document_id="+this.$route.query.document_id) {
        this.show_tool_menu = true;
      } else {
        this.show_tool_menu = false;
      }
    }
  },
  watch: {
    $route() {
      this.setOptions();
    }
  }
};
</script>
<style lang="scss">
@import "./DocumentsContainer.scss";
</style>

