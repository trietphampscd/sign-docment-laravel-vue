<template>
  <div class="w-100">
    <hr class="seperate-bar logout-seperate-bar" />
    <b-button block variant="trans" class="text-left" @click="onLogout">
      <UserIcon icon="logout_header.svg" class="mr-3"></UserIcon>{{ $t('layout.logout') }}
    </b-button>
  </div>
</template>

<script>
import UserIcon from "./UserIcon";
import { authentication } from '../mixins/authentication';
export default {
  name: "Logout",
  components: {
    UserIcon
  },
  mixins: [authentication],
  methods: {
    onLogout: function() {
      this.logout()
    }
  }
};
</script>
<style lang="scss">
.logout-seperate-bar {
  margin-bottom: 5px !important;
}
</style>
