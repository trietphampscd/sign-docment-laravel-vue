<template>
  <div class="side-navbar">
    <div class="navbar-title">
      <a class="navbar-logo" href="javascript:;">
        <img src="/img/logo_white.png" />
      </a>
    </div>

    <div class="navbar-gohome-box">
      <div class="navbar-gohome-container">
        <button class="btn btn-block btn-md btn-coffee" style="padding: 1rem;">Close and go to Home page</button>
      </div>
    </div>

    <div class="navbar-logout-box">
      <div class="navbar-logout-container">
        <button class="btn btn-md btn-block btn-transparent btn-withicon"><font-awesome-icon icon="sign-out-alt" />Logout</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "SideNavbar"
}
</script>

<style>

</style>
