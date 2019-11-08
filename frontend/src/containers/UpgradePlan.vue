<template>
  <b-button variant="primary" class="ml-3" v-on:click="upgradePlan()">{{$t('layout.button')}}</b-button>
</template>

<script>
export default {
  name: "UpgradePlan",
  methods: {
    upgradePlan() {
      this.$router.push({ path: "/payment/pricing-plan" });
    }
  }
};
</script>
