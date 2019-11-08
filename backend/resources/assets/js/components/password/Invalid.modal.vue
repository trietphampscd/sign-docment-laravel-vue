<template>
  <div>
    <b-modal id="modal-invalidpwdtoken" class="modal-size-lg" size="lg" centered hide-header-close no-close-on-backdrop no-close-on-esc>
      <template slot="modal-header" slot-scope="{ close }">
        <img src="../../../images/logo.png"/>
      </template>
      <template slot="default">
        <img src="../../../images/envelope-error.png"/>
        <h3>Reset password token is invalid or expired</h3>
        <button @click="toLogin" class="btn btn-md btn-coffee">Go to the Sign In page</button>
      </template>
    </b-modal>
  </div>
</template>

<script>
export default {
    methods: {
        toLogin() {
            this.$router.push({ name: 'Login' })
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
