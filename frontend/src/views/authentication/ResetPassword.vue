<template>
  <b-container fluid class="auth-page reset-password">

    <invalid></invalid>
    <error></error>

    <b-row class="container header">
      <b-col md="12">
        
        <AuthHeader />

      </b-col>
    </b-row>

    <b-row class="auth-form">
      <b-col lg="5" md="8" offset-md="2" class="wrapper">
        <b-form @submit.prevent="onReset">
          <h3 class="form-title">{{ $t("auth.resettitle") }}</h3>
          <span class="form-subtitle text-gray">{{ $t("auth.forgotsubtitle") }} <b-link href="javascript:;" @click="toLogin" class="text-coffee-light link">{{ $t("auth.forgotsubtitle2") }}</b-link></span>
          <b-form-group id="grpPassword"
                        :class="{
                          'form-group-withicon': true,
                          'form-group-focus': validator.password.focus && (!validator.password.blured || validPassword(form.password)),
                          'form-group-password': true,
                          'form-group-invalid': validator.password.blured && !validPassword(form.password),
                          'form-group-valid': validator.password.blured && validPassword(form.password)
                        }">
            <b-form-input id="password"
                        :class="{
                          'form-control-coffee': true,
                          'password': true,
                          'input-valid': validator.password.blured && (validPassword(form.password)),
                          'input-invalid': !validPassword(form.password) && validator.password.blured
                        }"
                        @blur="validator.password.blured = true, validator.password.focus = false"
                        @focus="validator.password.focus = true"
                        :type="isShowPassword?'text':'password'"
                        v-model="form.password"
                        :placeholder="$t('auth.input.password')">
            </b-form-input>
            <div class="eye" href="javascript:;" @click="onShowPassword"><font-awesome-icon icon="eye-slash" v-if="isShowPassword" /><font-awesome-icon icon="eye" v-else /></div>
              <font-awesome-icon icon="lock" />
            <p class="validation-error" v-if="!validPassword(form.password) && validator.password.blured">{{ validator.password.text }}</p>
          </b-form-group>
          <b-form-group id="grpPasswordConfirmation"
                        :class="{
                          'form-group-withicon': true,
                          'form-group-focus': validator.password_confirmation.focus && (!validator.password_confirmation.blured || matchPassword(form.password, form.password_confirmation)),
                          'form-group-password': true,
                          'form-group-valid': validator.password_confirmation.blured && matchPassword(form.password, form.password_confirmation),
                          'form-group-invalid': !matchPassword(form.password, form.password_confirmation) && validator.password_confirmation.blured
                        }">
            <b-form-input id="password_confirmation"
                        :class="{
                          'form-control-coffee': true,
                          'password': true,
                          'input-valid': validator.password_confirmation.blured && (matchPassword(form.password, form.password_confirmation)),
                          'input-invalid': !matchPassword(form.password, form.password_confirmation) && validator.password_confirmation.blured
                        }"
                        @blur="validator.password_confirmation.blured = true, validator.password_confirmation.focus = false"
                        @focus="validator.password_confirmation.focus = true"
                        :type="isShowPasswordConfirm?'text':'password'"
                        v-model="form.password_confirmation"
                        :placeholder="$t('auth.input.confirmpass')">
            </b-form-input>
            <div class="eye" href="javascript:;" @click="onShowPasswordConfirm"><font-awesome-icon icon="eye-slash" v-if="isShowPasswordConfirm" /><font-awesome-icon icon="eye" v-else /></div>
            <font-awesome-icon icon="lock" />
            <p class="validation-error" v-if="!matchPassword(form.password, form.password_confirmation) && validator.password_confirmation.blured">{{ validator.password_confirmation.text }}</p>
            </b-form-group>
          <input type="submit" class="btn btn-block btn-md btn-coffee" :value="$t('auth.button.resetpass')"/>
        </b-form>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import AuthHeader from './AuthHeader'
import Invalid from '../../components/password/Invalid.modal'
import Error from '../../components/common/Error.modal'

import { authentication } from '../../mixins/authentication'

export default {
    name: "ResetPassword",
    mixins: [authentication],
    components: {
        AuthHeader,
        Invalid
    },
    data() {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: '',
                token: ''
            },
            validator: {
                password: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: ''
                },
                password_confirmation: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Password does not match'
                }
            },
            token: '',
            isRequestFailed: false,
            isShowPassword: false,
            isShowPasswordConfirm: false
        }
    },
    mounted() {
        this.token = this.$route.params.token
        this.find()
    },
    methods: {
        find() {
            var vm = this
            vm.findPasswordToken({ token: vm.token }) 
                .then(response => {
                    vm.form.email = response.data.email
                    vm.form.token = response.data.token
                })
                .catch(error => {
                    vm.onInvaildToken()
                })
        },
        onReset() {
            var vm = this

            if (!vm.validate()) {
                return
            }

            vm.resetPassword(vm.form)
                .then(response => {
                    vm.onSuccess()
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors)
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Failed to reset password."])
                    }
                    vm.$bvModal.show('modal-error')
                })
        },
        onSuccess() {
            this.toLogin()
        },
        onInvaildToken() {
            this.$bvModal.show('modal-invalidpwdtoken')
        },
        onShowPassword() {
            if (this.isShowPassword) {
                this.isShowPassword = false
            } else {
                this.isShowPassword = true
            }
        },
        onShowPasswordConfirm() {
            if (this.isShowPasswordConfirm) {
                this.isShowPasswordConfirm = false
            } else {
                this.isShowPasswordConfirm = true
            }
        },
        validate() {
            var vm = this

            vm.validator.password.blured = true
            vm.validator.password_confirmation.blured = true

            if (vm.validPassword(vm.form.password) && vm.matchPassword(vm.form.password, vm.form.password_confirmation)) {
                return true
            } else {
                return false
            }
        },
        validPassword(password) {
            var vm = this
            if (password.length < 6 || password.length > 12) {
                vm.validator.password.valid = false
                vm.validator.password.text = 'Password must be 6 ~ 12 length characters'
                return false
            } else if (!(/[a-z]/.test(password))) {
                vm.validator.password.valid = false
                vm.validator.password.text = 'Password must contain at least 1 lower case letter'
                return false
            } else if (!(/[A-Z]/.test(password))) {
                vm.validator.password.valid = false
                vm.validator.password.text = 'Password must contain at least 1 capital letter'
                return false
            } else if (!(/[0-9]/.test(password))) {
                vm.validator.password.valid = false
                vm.validator.password.text = 'Password must contain at least 1 number'
                return false
            } else if (!(/[!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/.test(password))) {
                vm.validator.password.valid = false
                vm.validator.password.text = 'Password must contain at least 1 special character'
                return false
            }

            return true
        },
        matchPassword(password, password_confirmation) {
            var vm = this
            if (password_confirmation.length < 6 || password_confirmation.length > 12) {
                vm.validator.password_confirmation.valid = false
                vm.validator.password_confirmation.text = 'Password must be 6 ~ 12 length characters'
                return false
            } else if (!(/[a-z]/.test(password_confirmation))) {
                vm.validator.password_confirmation.valid = false
                vm.validator.password_confirmation.text = 'Password must contain at least 1 lower case letter'
                return false
            } else if (!(/[A-Z]/.test(password_confirmation))) {
                vm.validator.password_confirmation.valid = false
                vm.validator.password_confirmation.text = 'Password must contain at least 1 capital letter'
                return false
            } else if (!(/[0-9]/.test(password_confirmation))) {
                vm.validator.password_confirmation.valid = false
                vm.validator.password_confirmation.text = 'Password must contain at least 1 number'
                return false
            } else if (!(/[!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/.test(password_confirmation))) {
                vm.validator.password_confirmation.valid = false
                vm.validator.password_confirmation.text = 'Password must contain at least 1 special character'
                return false
            } else if (password !== password_confirmation) {
                vm.validator.password_confirmation.valid = false;
                vm.validator.password_confirmation.text = "Password doesn't match"
                return false
            }

            return true
        },
        toLogin() {
            this.$router.push({ name: 'Login' })
        }
    }
}
</script>

<style lang="scss" scoped>
// @import "./authentication.scss";
</style>
