<template>
<div class="container">
  <h3>CoffeeSign</h3>
  <span class="form-subtitle text-gray">To signout <b-link href="javascript:;" @click="toLogin" class="text-coffee-light link">Click Here</b-link></span>
</div>
</template>

<script>

import config from '../config/config'

export default {
    name: "Home",
    data() {
        return {}
    },
    created() {
        this.checkSocialLogin()
    },
    methods: {
        checkSocialLogin() {
            var urlParams = new URLSearchParams(window.location.search)
            var code = urlParams.get('code')

            if (code != undefined) {
                // this.$router.push({ name: 'LoginC', params: { code: code } })
                window.location.href = config.CLIENT_URL + '/login/' + code
            }
        },
        toLogin() {
            this.$router.push({ name: 'Login' })
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
