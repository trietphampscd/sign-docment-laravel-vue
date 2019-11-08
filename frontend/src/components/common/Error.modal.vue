<template>
  <div>
    <b-modal id="modal-error" size="md" centered>
      <template slot="modal-header" v-slope="{ close }">
      </template>
      <template slot="default">
        <b-img src="/img/envelopes/envelope-error.png"></b-img>
        <h3 style="white-space: pre-wrap;">{{errors[0]}}</h3>
        <button v-if="button==null" class="btn btn-md btn-coffee" @click="close">Close</button>
        <button v-if="button=='login'" @click="toLogin" class="btn btn-md btn-coffee">Go to Sign in</button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    name: "ErrorModal",
    data() {
        return {
        }
    },
    mounted() {
    },
    computed: {
        ...mapGetters([
            'errors',
            'button'
        ])
    },
    methods: {
        close() {
            this.$bvModal.hide('modal-error')
        },
        toLogin() {
            this.$bvModal.show('modal-error')
            this.$router.push({ name: 'Login' })
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
