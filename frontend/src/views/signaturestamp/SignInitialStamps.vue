<template>
  <div class="app flex-row" ref="fileform">
    <custom-loader :own-loading="false" :no-sidebar="true"></custom-loader>

    <div class="w-100 sign-stamp">
      <h1>{{ $t("signature.title") }}</h1>
      <hr class="mb-4" />
      <div class="row">
        <!-- SIGNATURES AND INITIALS LISTING -->
        <div class="col-md-6 pr-md-0">          
          <SignatureInitial />
        </div>
        <div class="col-md-6">
          <StampSeals />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CustomLoader from '../../components/common/CustomLoader'
import SignatureInitial from './SignatureInitial'
import StampSeals from './StampSeals'

export default {
  name: 'SignInitialStamps',
  components: {
    'custom-loader': CustomLoader,
    SignatureInitial,
    StampSeals
  },
  data() {
    return {}
  },
  mounted() {

  },
  methods: {

  }
}
</script>

<style lang="scss">
@import "./Signature.scss";
</style>