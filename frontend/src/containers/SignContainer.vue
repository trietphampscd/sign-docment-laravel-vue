<template>
  <div class="app">
    <div class="app-body">
      <main class="sign-main">
        <AppHeader class="sign-header">
          <AppLogoDark/>
          <div class="doc-timeline">
            <div class="timeline-item">
              <i :class="getStyle(0)" />
              <span class="timeline-title" :class="getTextStyle(0)">Check the document</span>
              <div class="header-radio-dashline"></div>
            </div>
            <div class="timeline-item">
              <i :class="getStyle(1)" />
              <span class="timeline-title" :class="getTextStyle(1)">Signing</span>
              <div class="header-radio-dashline"></div>
            </div>
            <div class="timeline-item">
              <i :class="getStyle(2)" />
              <span class="timeline-title" :class="getTextStyle(2)">Complition</span>
            </div>
          </div>
          <template v-if="currentStepNo == 0">
            <b-button variant="link" class="mr-1 mr-sm-4">Sign Later</b-button>
            <b-button variant="other" class="px-2 px-sm-4" v-on:click="gotoPage('/sign/signing')">Start Signing</b-button>
          </template>
          <template v-if="currentStepNo == 1">
            <b-button variant="link" class="mr-1 mr-sm-4">Finish Later</b-button>
            <div class="d-inline-block position-relative">
              <b-button variant="other" class="px-4 px-sm-4" v-on:click="finishSign()">
                {{ endFlag? 'Finish': 'Next' }}
              </b-button>
              <div class="doc-item-no" v-if="6-signStep > 0">
                {{ 6-signStep }}
              </div>
            </div>
          </template>
        </AppHeader>
        <div class="container-fluid main-container">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import {Header as AppHeader,} from "@coreui/vue";
import AppLogoDark from "../components/AppLogoDark";
export default {
  name: "SignContainer",
  components: {
    AppHeader,
    AppLogoDark,
  },
  data() {
    return {
      signStep: 0,
      endFlag: false,
      currentStepNo: 0,
    };
  },
  computed: {
    name() {
      return this.$route.name;
    },
    list() {
      return this.$route.matched.filter(
        route => route.name || route.meta.label
      );
    }
  },
  mounted() {
    this.setOptions();
    this.$root.$on('nextStep', (no, endFlag) => {
      this.signStep = no;
      this.endFlag = endFlag;
    })
  },
  methods: {
    finishSign() {
      if(this.endFlag){
        this.$root.$emit('finishSign')
      } else {
        this.$root.$emit('nextSignEdit')
      }

    },
    gotoPage(page) {
      this.$router.push({ path: page });
    },
    getStyle(no) {
      if (this.currentStepNo == no) {
        return "fa fa-circle-o ready";
      } else if (this.currentStepNo > no) {
        return "fa fa-check-circle ready";
      } else {
        return "fa fa-circle-o waiting";
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
      if (this.$router.history.current.fullPath == "/sign/check?document_id="+this.$route.query.document_id) {
        this.currentStepNo = 0;
      } else if ( this.$router.history.current.fullPath == "/sign/signing?document_id="+this.$route.query.document_id) {
        this.currentStepNo = 1;
      } else if ( this.$router.history.current.fullPath == "/sign/complition?document_id="+this.$route.query.document_id ) {
        this.currentStepNo = 2;
      } else {
        this.currentStepNo = 0;
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
@import "./SignContainer.scss";
</style>

