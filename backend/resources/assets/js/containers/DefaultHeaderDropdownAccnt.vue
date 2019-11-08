<template>
  <b-navbar-nav class="ml-auto">
    <AppHeaderDropdown right class="language-selector">
      <template slot="header">
        <i class="flag-icon h5 flag-custom" :class="`flag-icon-${$i18n.locale}`" />
      </template>
      <template slot="dropdown">
        <b-dropdown-item
          v-for="(language, index) in languages"
          :key="index"
          v-on:click="changeLanguage(language)"
        >
          <i class="flag-icon" v-bind:class="`flag-icon-${language}`" />
        </b-dropdown-item>

        <!-- <b-dropdown-item v-on:click="changeLanguage('gb')">
          <i class="flag-icon flag-icon-gb" />
        </b-dropdown-item>
        <b-dropdown-item v-on:click="changeLanguage('jp')">
          <i class="flag-icon flag-icon-jp" />
        </b-dropdown-item>
        <b-dropdown-item v-on:click="changeLanguage('kr')">
          <i class="flag-icon flag-icon-kr" />
        </b-dropdown-item>-->
      </template>
    </AppHeaderDropdown>
    <b-nav-item>
      <font-awesome-icon :icon="['fa', 'bell']" size="lg"></font-awesome-icon>
      <b-badge pill variant="primary">2</b-badge>
    </b-nav-item>
    <AppHeaderDropdown right no-caret>
      <template slot="header">
        <img :src="user.avatar ? user.avatar : `img/avatars/default.png`" class="img-avatar" alt="" />
        <span class="hello">{{ $t("layout.hello") }}, {{user.first_name}}!</span>
      </template>
      <template slot="dropdown">
        <b-dropdown-item v-on:click="gotoPage('/profile')">
          <i class="fa fa-user" /> {{ $t("Profile") }}
        </b-dropdown-item>
        <b-dropdown-item>
          <i class="fa fa-sign-out" /> {{ $t("Logout") }}
        </b-dropdown-item>
      </template>
    </AppHeaderDropdown>
  </b-navbar-nav>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import store from '../store/store'
import { HeaderDropdown as AppHeaderDropdown } from "@coreui/vue";
import { CHANGE_LANGUAGE_ACTION } from "../store/modules/language";
import { GET_USER_INFOR_REQUEST } from "../store/actions.type";
import { LANGUAGES } from "../i18n";

export default {
  name: "DefaultHeaderDropdownAccnt",
  components: {
    AppHeaderDropdown
  },
  data: () => {
    return {
      itemsCount: 42,
      languages: LANGUAGES
    };
  },
  computed: {
    ...mapGetters({
      user: "getUser"
    })
  },
  created(){
    store.dispatch(GET_USER_INFOR_REQUEST)
    .then( res => {
      // res 
      //   && res.user 
      //     && res.user.avatar ? this.option.img = res.user.avatar : this.option.img;
    })
    .catch( err => {

    });
  },
  methods: {
    gotoPage(url) {
      this.$router.push({ path: url });
    },
    changeLanguage(lang) {
      this.$store.dispatch(CHANGE_LANGUAGE_ACTION, {
        lang: lang,
        i18n: this.$root.$i18n
      });
    }
  }
};
</script>
<style lang="scss">
.language-selector {
  .dropdown-menu {
    min-width: 50px;
    width: 50px;
    .dropdown-item {
      min-width: 48px !important;
    }
  }
  .flag-custom {
    margin: 0;
    margin-right: 10px;
    border: 1px solid #ccc;
    width: 30px;
  }
}
.hello {
  @media (max-width: 1280px) {
    display: none;
  }
}
@media (max-width: 1095px) {
  .img-avatar {
    margin: 0 0 !important;
  }
  .navbar-nav {
    .nav-item {
      min-width: 35px;
      .nav-link {
        padding-left: 0 !important;
        padding-right: 0 !important;
        .badge {
          // left: 15% !important;
        }
      }
    }
  }
}
</style>
