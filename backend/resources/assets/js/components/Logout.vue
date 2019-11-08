<template>
  <div class="w-100">
    <hr class="seperate-bar logout-seperate-bar" />
    <b-button block variant="trans" class="text-left">
      <UserIcon icon="logout_header.svg" class="mr-3"></UserIcon>{{$t('Logout')}}
    </b-button>
  </div>
</template>

<script>
import UserIcon from "./UserIcon";
export default {
  name: "Logout",
  components: {
    UserIcon
  }
};
</script>
<style lang="scss">
.logout-seperate-bar {
  margin-bottom: 5px !important;
}
</style>
