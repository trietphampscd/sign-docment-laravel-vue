import Vue from 'vue'
import App from './App.vue'
import router from './router/index'
import store from './store/store'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap/dist/css/bootstrap.css'
Vue.use(BootstrapVue)

import 'flag-icon-css/css/flag-icon.min.css'

import $ from 'jquery';
window.$ = window.jQuery = $;
// import "jquery-ui"
import 'jquery-ui-bundle';
import 'jquery-ui-bundle/jquery-ui.css';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate, {
  // This is the default
  inject: true,
  // Important to name this something other than 'fields'
  fieldsBagName: 'veeFields',
  // This is not required but avoids possible naming conflicts
  errorBagName: 'veeErrors'
})

import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { EventBus } from "./config/event-bus";
import VueAWN from "vue-awesome-notifications"
import VueFilterDateParse from 'vue-filter-date-parse'
import VueFilterDateFormat from 'vue-filter-date-format';
Vue.use(VueFilterDateParse)
Vue.use(VueFilterDateFormat)
library.add(fab, fas);
let options = {}
Vue.use(VueAWN, options, VueFilterDateParse)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false;
require("vue-awesome-notifications/dist/styles/style.css")
import "../scss/style.scss";
// import "./helpers/kakao.min.js";
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
