<template>
  <b-container fluid class="auth-page reset-password">

    <instruction></instruction>

    <b-row class="container header">
      <b-col md="12">
        <b-link href="javascript:;" class="logo"><img src="../../../images/logo.png"/></b-link>
        <b-dropdown text="English" class="language-select">
          <b-dropdown-item><img src="../../../images/flags/uk.png" class="flag"/>English</b-dropdown-item>
          <b-dropdown-item><img src="../../../images/flags/jp.png" class="flag"/>日本語</b-dropdown-item>
          <b-dropdown-item><img src="../../../images/flags/kr.png" class="flag"/>한국어</b-dropdown-item>
        </b-dropdown>
        <img src="../../../images/flags/uk.png" class="flag"/>
      </b-col>
    </b-row>

    <b-row class="auth-form">
      <b-col lg="5" md="8" offset-md="2" class="wrapper">
        <b-form @submit.prevent="onRequest">
          <h3 class="form-title">Forgot your password?</h3>
          <span class="form-subtitle text-gray">Back to <b-link href="javascript:;" @click="toLogin" class="text-coffee-light link">Sign In</b-link></span>
          <b-form-group id="grpEmail"
                        :class="{
                          'form-group-withicon': true,
                          'form-group-focus': validator.email.focus && !validator.email.error && (!validator.email.blured || validEmail(form.email)),
                          'form-group-invalid': !validEmail(form.email) && validator.email.blured,
                          'form-group-valid': validator.email.blured && (validEmail(form.email) && !validator.email.error)
                        }">
            <b-form-input id="email"
                        :class="{
                          'form-control-coffee': true,
                          'input-invalid': !validEmail(form.email) && validator.email.blured,
                          'input-valid': validator.email.blured && (validEmail(form.email) && !validator.email.error)
                        }"
                        @blur="validator.email.blured = true, validator.email.focus = false"
                        @focus="validator.email.focus = true"
                        type="email"
                        v-model="form.email"
                        placeholder="Your email address">
            </b-form-input>
            <font-awesome-icon icon="envelope-open" />
            <p class="validation-error" v-if="!validEmail(form.email) && validator.email.blured">{{ validator.email.text }}</p>
          </b-form-group>
          <input type="submit" class="btn btn-block btn-md btn-coffee" value="Submit"/>
        </b-form>
        <span class="text-gray" style="display: block; margin-top: 20px; text-align: center;">You will receive a password reset link at your registered email address,<br/>which is valid for 10 minutes.</span>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Instruction from '../../components/password/Instruction.modal'

import { authentication } from '../../mixins/authentication'

export default {
    name: "ForgotPassword",
    mixins: [authentication],
    components: {
        Instruction
    },
    mounted() {
        // this.onSuccess()
    },
    data() {
        return {
            form: {
                email: ''
            },
            validator: {
                email: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Please input a valid Email',
                    error: false,
                    last: ''
                },
            },
            isRequestFailed: false
        }
    },
    methods: {
        onRequest() {
            var vm = this

            if (!vm.validate()) {
                return
            }

            localStorage.setItem('forgot-form', JSON.stringify(vm.form))

            vm.createPasswordToken(vm.form)
                .then(response => {
                    vm.onSuccess()
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        var errors = error.response.data.errors
                        if (errors.email.length > 0) {
                            vm.validator.email.error = true
                            vm.validator.email.text = errors.email[0]
                            vm.validator.email.last = vm.form.email
                        }
                    }
                })
        },
        onSuccess() {
            this.$bvModal.show('modal-instruction')
        },
        validate() {
            var vm = this

            vm.validator.email.blured = true

            if (vm.validEmail(vm.form.email)) {
                return true
            } else {
                return false
            }
        },
        validEmail(email) {
            var vm = this

            if (email == vm.validator.email.last) {
                return
            }

            vm.validator.email.error = false
            vm.validator.email.last = ''

            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            vm.validator.email.valid = re.test(email)

            var isValid = re.test(email)
            if (isValid) {
                return true
            } else {
                vm.validator.email.valid = false
                vm.validator.email.text = 'Please input a valid Email'
                return false
            }
        },
        toLogin() {
            this.$router.push({ name: 'Login' })
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./authentication.scss";
</style>
