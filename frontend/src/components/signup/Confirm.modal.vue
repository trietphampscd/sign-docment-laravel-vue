<template>
  <div>
    <b-modal id="modal-confirm" size="lg" centered>
      <template slot="modal-header" v-slope="{ close }">
      </template>
      <template slot="default">
        <b-img src="/img/envelopes/envelope.png"></b-img>
        <h3 style="white-space: pre-wrap;">{{message}}</h3>
        <span class="text-gray">If you cannot find your confirmation email, please check your spam folder,<br/>or <a class="text-coffee-light link" href="javascript:;" @click="onResend">click here to resend the confirmation email.</a></span>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { authentication } from '../../mixins/authentication'

export default {
    name: "ConfirmModal",
    mixins: [authentication],
    data() {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: '',
                provider_name: ''
            },
            message: "We've sent a confirmation email to your inbox.\nPlease verify your account before using CoffeeSign."
        }
    },
    methods: {
        onResend() {
            var vm = this
            vm.message = "Confirmation email is being resent to your inbox..."
            vm.form = JSON.parse(localStorage.getItem('signup-form'))

            vm.requestActivationToken(vm.form)
                .then(response => {
                    vm.onSuccess(response.data)
                })
                .catch(error => {
                    vm.onFailed(error)
                })
        },
        onSuccess(data) {
            var vm = this
            vm.message = "We've resent a confirmation email to your inbox.\nPlease verify your account before using CoffeeSign."
        },
        onFailed(error) {
            var vm = this
            vm.message = "We couldn't resent a confirmation email to your inbox.\nPlease verify your account before using CoffeeSign."
        },
        close() {
            this.$bvModal.hide('modal-error')
        },
    }
}
</script>

<style lang="scss" scoped>

</style>
