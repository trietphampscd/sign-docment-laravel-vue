<template>
  <b-container fluid class="auth-page login">

    <error></error>

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
        <b-form @submit.prevent="onLogin">
          <h3 class="form-title">Welcome Back!</h3>
          <span class="form-subtitle text-gray">New in CoffeeSign? <b-link href="javascript:;" @click="toSignup" class="text-coffee-light link">Create an account</b-link></span>
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
          <b-form-group id="grpPassword"
                        :class="{
                          'form-group-withicon': true,
                          'form-group-focus': validator.password.focus && !validator.password.error && (!validator.password.blured || validPassword(form.password)),
                          'form-group-password': true,
                          'form-group-invalid': validator.password.blured && !validPassword(form.password),
                          'form-group-valid': validator.password.blured && (validPassword(form.password) && !validator.password.error)
                        }">
            <b-form-input id="password"
                        :class="{
                          'form-control-coffee': true,
                          'password': true,
                          'input-valid': validator.password.blured && (validPassword(form.password) && !validator.password.error),
                          'input-invalid': !validPassword(form.password) && validator.password.blured
                        }"
                        @blur="validator.password.blured = true, validator.password.focus = false"
                        @focus="validator.password.focus = true"
                        :type="isShowPassword?'text':'password'"
                        v-model="form.password"
                        placeholder="Password">
            </b-form-input>
            <div class="eye" @click="onShowPassword"><font-awesome-icon icon="eye-slash" v-if="isShowPassword" /><font-awesome-icon icon="eye" v-else /></div>
            <font-awesome-icon icon="lock" />
            <p class="validation-error" v-if="!validPassword(form.password) && validator.password.blured">{{ validator.password.text }}</p>
          </b-form-group>
          <b-form-group id="remember">
            <b-form-checkbox id="remember_me"
                          class="form-checkbox-coffee pull-left"
                          v-model="form.remember_me">Remeber Me
            </b-form-checkbox>
            <b-link href="javascript:;" @click="toPassword" class="text-coffee-light link pull-right">Forgot Password?</b-link>
          </b-form-group>
          <input type="submit" class="btn btn-block btn-md btn-coffee" value="Sign In"/>
        </b-form>
        <div class="signup-withsocial">
          <p class="text-gray">Or you can signin with:</p>
          <div class="social-links">
            <facebook-login appId="2349951131937784" class="button btn-facebook" @login="onFBLogin" @logout="onFBLogout" @sdk-loaded="onFBSdkLoaded" :key="fbkey"></facebook-login>
            <GoogleLogin class="button btn-google" :params="googleLoginParams" :onSuccess="onGLoginSuccess" :onFailure="onGLoginFailure"></GoogleLogin>
            <b-link href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1592490922&redirect_uri=https://www.coffeesign.io/api/auth/lineauth&state=signup12&scope=openid%20email"><img src="../../../images/line.png"/></b-link>
            <KakaoLogin api-key="b2f99b39dd3b4d340a7c0ef10260c2e4" :on-success="onKakaoLoginSuccess" :on-failure="onKakaoLoginFailure" />
          </div>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import Error from '../../components/common/Error.modal'
import { authentication } from '../../mixins/authentication'
import { AUTH_SUCCESS, AUTH_ERROR, AUTH_MODAL_BTN, AUTH_SET_FB } from '../../store/actions.type'
import jwtDecode from 'jwt-decode'
import facebookLogin from 'facebook-login-vuejs'
import GoogleLogin from 'vue-google-login'
import KakaoLogin from 'vue-kakao-login'
import axios from 'axios'

// import '../../../scss/custom.scss';
export default {
    name: "Login",
    components: {
        Error,
        facebookLogin, GoogleLogin, KakaoLogin
    },
    mixins: [authentication],
    data() {
        return {
            form: {
                email: '',
                password: '',
                remember_me: false,
            },
            sform: {
                email: '',
                password: '',
                password_confirmation: '',
                remember_me: false,
                provider_name: ''
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
                password: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Password must be 6 ~ 12 length characters',
                    error: false,
                    last: ''
                },
            },
            isLoginFailed: false,
            isShowPassword: false,
            FB: undefined,
            isFBSuccess: false,
            isFBConnected: false,
            fbkey: 0,

            googleLoginParams: {
                client_id: '111229380791-13ep2n0iakp1j4kfimhdmk40l1ioggcl.apps.googleusercontent.com'
            }
        }
    },
    mounted() {
        this.FB = this.$store.getters.FB
        

        if (this.$route.params.email != undefined) {
            this.onLLoginDone(this.$route.params.email)
        } else {
            if (this.FB == null) {
                this.fbkey += 1
                return
            }
        }
    },
    methods: {
        onLogin(evt) {
            var vm = this
            vm.isLoginFailed = false
            
            if (!vm.validate()) {
                return
            }

            vm.login(vm.form)
                .then(response => {
                    vm.$store.dispatch(AUTH_SUCCESS, response.data)
                    vm.toHome()
                })
                .catch(error => {
                    vm.isLoginFailed = true
                    if (error.response.status == 422) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors)
                    } else if (error.response.status == 401) {
                        var errors = error.response.data.errors
                        if (errors.email) {
                            vm.validator.email.error = true
                            vm.validator.email.text = errors.email[0]
                            vm.validator.email.last = vm.form.email
                        } else if (errors.password) {
                            vm.validator.password.error = true
                            vm.validator.password.text = errors.password[0]
                            vm.validator.password.last = vm.form.password
                        }
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors)
                        return
                    }
                    vm.$store.dispatch(AUTH_ERROR, ['Error occured while login.'])
                    vm.$bvModal.show('modal-error')
                })
        },
        onShowPassword() {
            if (this.isShowPassword) {
                this.isShowPassword = false
            } else {
                this.isShowPassword = true
            }
        },
        validate() {
            var vm = this

            vm.validator.email.blured = true
            vm.validator.password.blured = true

            if (vm.validEmail(vm.form.email) && vm.validPassword(vm.form.password)) {
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
        validPassword(password) {
            var vm = this

            if (password == vm.validator.password.last) {
                return
            }

            vm.validator.password.error = false
            vm.validator.password.last = ''

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
        getFBUserData() {
            var vm = this
            vm.FB.api('/me', 'GET', { fields: 'id, name, email' },
                user => {
                    console.log("data api", user)

                    vm.personalID = user.id
                    vm.name = user.name
                    vm.sform.provider_name = 'facebook'
                    vm.sform.email = user.email
                    vm.sform.password = 'fbCSpwd1!'

                    if (vm.isFBSuccess && vm.isFBConnected) {
                        vm.loginWithSocial()
                    }
                }
            )
        },
        onFBSdkLoaded(payload) {
            this.isFBConnected = payload.isFBConnected
            this.FB = payload.FB
            this.$store.dispatch(AUTH_SET_FB, payload.FB)
            if (this.isFBConnected) {
                this.getFBUserData()
            }
        },
        onFBLogin() {
            this.isFBConnected = true
            this.isFBSuccess = true
            this.getFBUserData()
        },
        onFBLogout() {
            this.isFBConnected = false
        },
        // Google login
        onGLoginSuccess(gUser) {
            var vm = this
            var profile = gUser.getBasicProfile()

            vm.sform.email = profile.getEmail()
            vm.sform.password = 'gCSpwd2@'
            vm.sform.provider_name = 'google'
            
            vm.loginWithSocial()
        },
        onGLoginFailure(error) {
            console.log(error)
        },
        onLLoginDone(email) {
            var vm = this

            vm.sform.email = email
            vm.sform.password = 'lCSpwd2@'
            vm.sform.provider_name = 'line'

            vm.loginWithSocial()
        },
        onKakaoLoginSuccess(data) {
            var vm = this

            axios.post('https://www.coffeesign.io/api/auth/kprofile', {
                'token_type': data.token_type,
                'access_token': data.access_token
            }, {
                header: null
            }).then(response => {
                vm.sform.email = response.data.kakao_account.email
                vm.sform.password = 'KCSpwd2@'
                vm.sform.provider_name = 'kakao'

                vm.loginWithSocial()
            })
        },
        onKakaoLoginFailure(error) {
            console.log(error)
        },
        loginWithSocial() {
            var vm = this
            vm.isLoginFailed = false

            vm.login(vm.sform)
                .then(response => {
                    vm.$store.dispatch(AUTH_SUCCESS, response.data)
                    vm.clearSForm()
                    vm.toHome()
                })
                .catch(error => {
                    if (error.response.status == 404) {
                        vm.signupWithSocial()
                        return
                    } else if (error.response.status == 422) {
                        vm.clearSForm()
                    } else if (error.response.status == 401) {
                        vm.clearSForm()
                    } else {
                        vm.clearSForm()
                    }
                    vm.isLoginFailed = true
                    if (error.response.data.errors.email) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.email)
                    } else if (error.response.data.errors.password) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.password)
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Error occured while social log in"])
                    }

                    vm.$bvModal.show('modal-error')
                })
        },
        signupWithSocial() {
            var vm = this
            vm.sform.password_confirmation = vm.sform.password

            localStorage.setItem('signup-form', JSON.stringify(vm.sform))

            vm.signup(vm.sform)
                .then(response => {
                    vm.loginAfterSocialSignup()
                })
                .catch(error => {
                    vm.clearSForm()

                    if (error.response.data.errors.email) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.email)
                    } else if (error.response.data.errors.password) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.password)
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Error occured while social sign up"])
                    }

                    vm.$bvModal.show('modal-error')
                })
        },
        loginAfterSocialSignup() {
            var vm = this
            vm.isLoginFailed = false

            vm.login(vm.sform)
                .then(response => {
                    vm.$store.dispatch(AUTH_SUCCESS, response.data)
                    vm.clearSForm()
                    vm.toHome()
                })
                .catch(error => {
                    vm.clearSForm()
                    vm.isLoginFailed = true

                    if (error.response.data.errors.email) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.email)
                    } else if (error.response.data.errors.password) {
                        vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.password)
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Error occured while log in"])
                    }

                    vm.$bvModal.show('modal-error')
                })
        },

        toSignup() {
            this.$router.push({ name: 'Signup' })
        },
        toPassword() {
            this.$router.push({ name: 'ForgotPassword' })
        },
        toHome() {
            this.$router.push({ name: 'Home' })
        },
        toUpload() {
          
        },
        clearSForm() {
            var vm = this
            vm.sform.email = ''
            vm.sform.password = ''
            vm.sform.remember_me = false
            vm.sform.provider_name = ''
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./authentication.scss";
</style>
