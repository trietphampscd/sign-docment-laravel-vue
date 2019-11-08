<template>
  <div v-if="ownLoading || loading">
    <div class="loading">
      <div :class="{
            'box': true, 
            'login-box': noSidebar
      }">
        <div class="loader"></div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'CustomLoader',
  data() {
    return {}
  },
  props: ['ownLoading', 'noSidebar'],
  mounted() {},
  computed: {
    ...mapGetters(['loading'])
  },
  methods: {
    // onSidebar() {
    //   return !!(window.innerWidth < 500)
    // }
  }
}
</script>

<style lang="scss" scoped>
/** Spinner Loader */
.loading {
  position: fixed;
  top: 0; right: 0;
  bottom: 0; left: 0;
  background: rgba(255, 255, 255, .6);
  z-index: max($numbers: 1050);
}
.login-box {
  left: 50% !important;
}
.box {
  /* background: rgba(255, 255, 255, 0.7); */
  left: 57.4%;
  margin-left: -12.5em;
  font-size: .4rem;
}
.box, .box:after {
    border-radius: 20%;
    width: 25em;
    height: 25em;
    display: block;
    position: fixed;
    top: 50%;
    margin-top: -13em;
}
.loader {
    left: 50%;
    margin-left: -4em;
    font-size: .4rem;
    border: .8em solid rgba(218, 219, 223, 1);
    border-left: .8em solid rgba(138, 100, 84, 1);
    animation: spin 1.1s infinite linear;
}
.loader, .loader:after {
    border-radius: 50%;
    width: 8em;
    height: 8em;
    display: block;
    position: absolute;
    top: 50%;
    margin-top: -4.05em;
}

@keyframes spin {
  0% {
    transform: rotate(360deg);
  }
  100% {
    transform: rotate(0deg);
  }
}
</style>