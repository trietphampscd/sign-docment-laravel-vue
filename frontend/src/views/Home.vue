<template>
  <div class="container p-5">
    <h3>CoffeeSign</h3>
    <span class="form-subtitle text-gray">    
      <b-link href="javascript:;" @click="onLanding" class="text-coffee-light link"> Jump to Dashboard </b-link>
      To signout <b-link href="javascript:;" @click="onLogout" class="text-coffee-light link">Click Here.</b-link>
      </span>
  </div>
</template>

<script>

import config from '../config/config'
import { authentication } from '../mixins/authentication'

export default {
  name: "Home",
  data() {
    return {}
  },
  mixins: [authentication],
  created() {
    // this.checkSocialLogin()
  },
  methods: {
    checkSocialLogin() {
      var urlParams = new URLSearchParams(window.location.search)
      var code = urlParams.get('code')

      if (code != undefined) {
        // this.$router.push({ name: 'LoginC', params: { code: code } })
        window.location.href = config.BASE_URL + '/login/' + code
      }
      this.$router.go()
    },
    onLogout: function() {
      this.logout()
    },
    toLogin() {
      this.$router.push({ name: 'Login' })
    },
    onLanding() {
      this.$router.push({ name: 'LandingPage' })
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
