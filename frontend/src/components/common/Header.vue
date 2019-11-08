<template>
  <div class="header" style="position: relative;">
    <div class="header-plan-box">
      <button class="btn btn-md btn-coffee">Upgrade Your Plan</button>
    </div>

    <DocumentStep></DocumentStep>

    <div class="user-dropdown-box">
      <b-dropdown text="Leonardo Marques" class="user-dropdown">
        <b-dropdown-item><font-awesome-icon icon="user" />Profile</b-dropdown-item>
        <b-dropdown-item><font-awesome-icon icon="cog" />Settings</b-dropdown-item>
      </b-dropdown>
      <img src="/img/avatars/avatar.png" class="avatar"/>
    </div>

    <div class="notification-dropdown-box">
      <b-dropdown class="notification-dropdown dropdown-transparent">
      </b-dropdown>
      <font-awesome-icon icon="bell" />
      <span class="badge badge-coffee">2</span>
    </div>

    <div class="language-select-box">
      <b-dropdown text="" class="language-select-mini dropdown-transparent">
        <b-dropdown-item><img src="/img/flags/uk.png" class="flag"/></b-dropdown-item>
        <b-dropdown-item><img src="/img/flags/jp.png" class="flag"/></b-dropdown-item>
        <b-dropdown-item><img src="/img/flags/kr.png" class="flag"/></b-dropdown-item>
      </b-dropdown>
      <img src="/img/flags/uk.png" class="flag"/>
    </div>
  </div>
</template>

<script>
import DocumentStep from './DocumentStep'

export default {
  name: "Header",
  components: {
    DocumentStep
  },
}
</script>

<style>

</style>
