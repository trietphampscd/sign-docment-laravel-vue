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
          v-on:click="changeLanguage(language)">
          <i class="flag-icon flag-custom2" v-bind:class="`flag-icon-${language}`" />
        </b-dropdown-item>
      </template>
    </AppHeaderDropdown>
    <b-nav-item>
      <font-awesome-icon :icon="['fa', 'bell']" size="lg"></font-awesome-icon>
      <b-badge pill variant="primary">2</b-badge>
    </b-nav-item>
    <AppHeaderDropdown right no-caret>
      <template slot="header">
        <!-- <img :src="getUser.avatar || 'img/avatars/default.png'" ref="avatarhead" :alt="getUser.email" class="img-avatar" @error="errorAvatar" /> -->
        <img :src="getAvatar" ref="avatarhead" :alt="getUser.email" class="img-avatar" @error="errorAvatar" />
        <span class="hello">{{ $t("layout.hello") }} {{getUser.first_name}}!</span>
      </template>
      <template slot="dropdown">
        <b-dropdown-item v-on:click="gotoPage('Profile')">
          <i class="fa fa-user" /> {{ $t("Profile") }}
        </b-dropdown-item>
        <b-dropdown-item @click="onLogout">
          <i class="fa fa-sign-out" /> {{ $t("Logout") }}
        </b-dropdown-item>
      </template>
    </AppHeaderDropdown>
  </b-navbar-nav>
</template>

<script>
import { HeaderDropdown as AppHeaderDropdown } from "@coreui/vue"
import { mapGetters, mapState } from 'vuex'
import { GET_USER_INFOR_REQUEST, CHANGE_LANGUAGE_ACTION } from "../store/actions.type"
import { LANGUAGES } from "../i18n"
import { authentication } from '../mixins/authentication'
export default {
  name: "DefaultHeaderDropdownAccnt",
  components: {
    AppHeaderDropdown
  },
  mixins: [authentication],
  data: () => {
    return {
      itemsCount: 42,
      languages: LANGUAGES
    };
  },
  computed: {
    ...mapGetters(['getUser']),
    getAvatar() {
      return this.getUser.avatar || './img/avatars/default.png'
    }
  },
  created(){
    this.$store.dispatch(GET_USER_INFOR_REQUEST)
      .then(response => {})
      .catch(error => {})
  },
  methods: {
    errorAvatar() {
      this.$refs['avatarhead'].src = './img/avatars/default.png'
    },
    gotoPage(name) {
      this.$router.push({ name: name })
    },
    onLogout: function() {
      this.logout()
    },
    changeLanguage(lang) {
      this.$store.dispatch(CHANGE_LANGUAGE_ACTION, {
        lang: lang,
        i18n: this.$root.$i18n
      })
    }
  }
};
</script>
<style lang="scss">
  .language-selector {
    .dropdown-menu {
      min-width: 50px;
      width: 65px;
      .dropdown-item {
        min-width: 48px !important;
      }
    }
    .flag-custom {
      margin: 0;
      // margin-right: 10px;
      border: 1px solid #ccc;
      width: 30px;
    }
    .flag-custom2 {
      margin-left: -1px;
      border: 1px solid #ccc;
      width: 25px;
      height: 20px;
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
        }
      }
    }
  }
</style>
