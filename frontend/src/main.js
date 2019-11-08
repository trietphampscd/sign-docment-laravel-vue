// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import './polyfill'
// import cssVars from 'css-vars-ponyfill'
import Vue from 'vue'
import App from './App.vue'
import router from './router/index'
import store from './store/store'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'flag-icon-css/css/flag-icon.min.css'
import CxltToastr from 'cxlt-vue2-toastr'

import $ from 'jquery';
// import "jquery-ui"
import 'jquery-ui-bundle';
import 'jquery-ui-bundle/jquery-ui.css';
import VeeValidate from 'vee-validate';
// FortAwesome Vue
import { library } from '@fortawesome/fontawesome-svg-core'
import { 
  fas,
  faEnvelopeOpen, 
  faLock, 
  faEye, 
  faEyeSlash, 
  faSignOutAlt, 
  faUser, 
  faCog, 
  faBell 
} from '@fortawesome/free-solid-svg-icons'
import { 
  fab,
  faTwitter, 
  faFacebook, 
  faYoutubeSquare 
} from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { EventBus } from "./config/event-bus"
import VueAWN from "vue-awesome-notifications"

window.$ = window.jQuery = $;
Vue.use(BootstrapVue)
Vue.use(VeeValidate, {
  // This is the default
  inject: true,
  // Important to name this something other than 'fields'
  fieldsBagName: 'veeFields',
  // This is not required but avoids possible naming conflicts
  errorBagName: 'veeErrors'
})
library.add(fas, faEnvelopeOpen, faLock, faEye, faEyeSlash, faSignOutAlt, faUser, faCog, faBell)
library.add(fab, faTwitter, faFacebook, faYoutubeSquare)
Vue.component('font-awesome-icon', FontAwesomeIcon)
// End FortAwesome Vue

import VueFilterDateParse from 'vue-filter-date-parse'
import VueFilterDateFormat from 'vue-filter-date-format';
Vue.use(VueFilterDateParse)
Vue.use(VueFilterDateFormat)
let options = {}
Vue.use(VueAWN, options, VueFilterDateParse)
Vue.config.productionTip = false;

var toastrConfigs = {
  position: "top right",
  showDuration: 500,
  delay: 0,
  timeOut: 5000,
  hideDuration: 500,
  progressBar: true
};
Vue.use(CxltToastr, toastrConfigs);

require("vue-awesome-notifications/dist/styles/style.css")
import "./assets/scss/style.scss";
import i18n from './i18n'

new Vue({
  data()
  {
    return {
      uploadFiles: [],
      isLanding: false,
    }
  },
  created(){
    let self = this;
    EventBus.$on('FILES_UPLOAD', (files) => {
      files &&
        files.length > 0 &&
        files.map(file => {
          self.uploadFiles.push(file)
        });
    })
    EventBus.$on('IS_LANDING', (v) => {
      this.isLanding = v;
    })
  },
  el: '#app',
  i18n,
  router,
  store,
  render: h => h(App)
})
