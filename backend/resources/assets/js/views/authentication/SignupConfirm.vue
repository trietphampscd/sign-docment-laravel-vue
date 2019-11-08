<template>
  <b-container fluid class="signup-confirmed">
    <confirmed></confirmed>
    <invalid></invalid>
  </b-container>
</template>

<script>
import Confirmed from '../../components/signup/Confirmed.modal'
import Invalid from '../../components/signup/Invalid.modal'

import { authentication } from '../../mixins/authentication'

export default {
    name: 'SignupConfirm',
    mixins: [authentication],
    components: {
        Confirmed,
        Invalid,
    },
    data() {
        return {
            isLoaded: false,
            isConfirmed: false,
            token: ''
        }
    },
    mounted() {
        this.token = this.$route.params.token
        this.onConfirm()
        // this.onSuccess()
    },
    methods: {
        onConfirm() {
            var vm = this
            vm.activate({token: vm.token})
                .then(response => {
                    vm.onSuccess()
                })
                .catch(error => {
                    // alert(error.response.data.errors[0])
                    vm.onInvalidToken()
                })
        },
        onSuccess() {
            this.$bvModal.show('modal-confirmed')
        },
        onInvalidToken() {
            this.$bvModal.show('modal-invalidconftoken')
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./authentication.scss";
</style>
