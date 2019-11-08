<template>
  <div>
    <b-modal id="modal-instruction" class="modal-size-lg" size="lg" centered>
      <template slot="modal-header" slot-scope="{ close }">
      </template>
      <template slot="default">
        <img src="../../../images/envelope.png"/>
        <h3>{{message}}</h3>
        <span class="text-gray">If you cannot find your instruction email, please check your spam folder,<br/>or <a class="text-coffee-light link" href="javascript:;" @click="onResend">click here to resend the instruction email.</a></span>
      </template>
    </b-modal>
  </div>
</template>

<script>
import { authentication } from '../../mixins/authentication'

export default {
    name: "InstructionModal",
    mixins: [authentication],
    data() {
        return {
            form: {
                email: ''
            },
            message: "We've sent an instruction email to your inbox."
        }
    },
    mounted() {
    },
    methods: {
        onResend() {
            var vm = this
            vm.message = "Instruction email is being resent to your inbox..."
            vm.form = JSON.parse(localStorage.getItem('forgot-form'))

            vm.createPasswordToken(vm.form)
                .then(response => {
                    vm.onSuccess(response.data)
                })
                .catch(error => {
                    vm.onFailed(error)
                })
        },
        onSuccess(data) {
            var vm = this
            vm.message = "We've resent the instruction email to your inbox."
        },
        onFailed(error) {
            var vm = this
            console.log("Error")
            vm.message = "We couldn't resend the instruction email to your inbox."
        },
    }
}
</script>

<style lang="scss" scoped>

</style>
