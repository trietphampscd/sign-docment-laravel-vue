<template>
  <b-link class="navbar-brand header" to="/landing">
    <img
      class="navbar-brand-full"
      src="img/logo_dark.svg"
      width="125"
      height="41"
      alt="Coffee Sign"
    />
  </b-link>
</template>

<script>
export default {
  name: "AppLogoDark"
};
</script>
<style lang="scss">
.app-header .navbar-brand, .app-header .navbar-brand img {
  @media (max-width: 500px) {
    width: 100px;
  }
}
</style>
