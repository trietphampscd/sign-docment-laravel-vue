<template>
  <b-container fluid class="signup auth-page">
    
    <confirm></confirm>
    <error></error>

    <b-row>
      <b-col xl="5" lg="6" md="12" sm="12" xs="12" class="signup-left">

        <b-row class="header">
          <b-col xl="7" offset-xl="3" lg="8" offset-lg="2" md="8" offset-md="2" class="wrapper">
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
          <b-col xl="7" offset-xl="3" lg="8" offset-lg="2" md="8" offset-md="2" class="wrapper">
            <b-form @submit.prevent="onSignup">
              <h3 class="form-title">Create an account</h3>
              <span class="form-subtitle text-gray">Already have a CoffeeSign account? <b-link href="javascript:;" @click="toLogin" class="text-coffee-light link">Sign in</b-link></span>
              <b-form-group id="grpEmail"
                            :class="{
                              'form-group-withicon': true,
                              'form-group-focus': validator.email.focus && !validator.email.error && (!validator.email.blured || validEmail(form.email)),
                              'form-group-invalid': validator.email.blured && (!validEmail(form.email) || validator.email.error),
                              'form-group-valid': validator.email.blured && (validEmail(form.email) && !validator.email.error)
                            }">
                <b-form-input id="email"
                            :class="{
                              'form-control-coffee': true,
                              'input-invalid': validator.email.blured && (!validEmail(form.email) || validator.email.error),
                              'input-valid': validator.email.blured && (validEmail(form.email) && !validator.email.error)
                            }"
                            @blur="validator.email.blured = true, validator.email.focus = false"
                            @focus ="validator.email.focus = true"
                            type="email"
                            v-model="form.email"
                            placeholder="Your email address"> 
                </b-form-input>
                <font-awesome-icon icon="envelope-open" />
                <p class="validation-error" v-if="validator.email.blured && (!validEmail(form.email) || validator.email.error)">{{ validator.email.text }}</p>
              </b-form-group>
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
                              placeholder="Password">
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
                              placeholder="Confirm password">
                </b-form-input>
                <div class="eye" href="javascript:;" @click="onShowPasswordConfirm"><font-awesome-icon icon="eye-slash" v-if="isShowPasswordConfirm" /><font-awesome-icon icon="eye" v-else /></div>
                <font-awesome-icon icon="lock" />
                <p class="validation-error" v-if="!matchPassword(form.password, form.password_confirmation) && validator.password_confirmation.blured">{{ validator.password_confirmation.text }}</p>
              </b-form-group>
              <input type="submit" class="btn btn-block btn-md btn-coffee" value="Sing Up and Get Started"/>
            </b-form>
            <span class="text-gray" style="display: block; margin-top: 20px;">By proceeding your agree to the <a href="javascript:;" class="text-coffee-light link">Terms of Use</a> and <a href="javascript:;" class="text-coffee-light link">Privacy Policy</a></span>
            <div class="signup-withsocial">
              <p class="text-gray">Or you can signup with:</p>
              <div class="social-links">
                <facebook-login appId="2349951131937784" class="button btn-facebook" @login="onFBLogin" @logout="onFBLogout" @sdk-loaded="onFBSdkLoaded"></facebook-login>
                <GoogleLogin class="button btn-google" :params="googleLoginParams" :onSuccess="onGLoginSuccess" :onFailure="onGLoginFailure"></GoogleLogin>
                <b-link href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1592490922&redirect_uri=https://www.coffeesign.io/api/auth/lineauth&state=signup12&scope=openid%20email"><img src="../../../images/line.png"/></b-link>
                <KakaoLogin api-key="b2f99b39dd3b4d340a7c0ef10260c2e4" :on-success="onKakaoLoginSuccess" :on-failure="onKakaoLoginFailure" />
              </div>
            </div>
          </b-col>
        </b-row>

      </b-col>

      <b-col xl="7" lg="6" class="signup-right pb-5">
        <h1>What is CoffeeSign?</h1>
        <p>CoffeeSign is a 3-step e-signature service that helps your business grow faster.</p>
        <p>Save your time and money with us.</p>

        <div class="carousel-container">
          <carousel :per-page="1" :autoplay="true" :mouse-drag="true" mouse-drag.boolean="false"
                    :navigationEnabled="false"
                    :paginationEnabled="true"
                    :loop="false"
                    :interval="8000"
                    showControls
                    :paginationColor="'rgba(255, 255, 255, 0.3)'"
                    :paginationActiveColor="'rgba(255, 255, 255, 1)'"
                    :paginationSize="7"
                    :paginationPadding="3"
                    >
            <slide class="pos-rel">
              <a target="_blank">
                <img src="../../../images/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="../../../images/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="../../../images/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="../../../images/slide01.png" class="img-responsive"/>
              </a>
            </slide>
          </carousel>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
import {
    Carousel,
    Slide
} from 'vue-carousel'
import Confirm from '../../components/signup/Confirm.modal'
import Error from '../../components/common/Error.modal'
import facebookLogin from 'facebook-login-vuejs'
import GoogleLogin from 'vue-google-login'
import KakaoLogin from 'vue-kakao-login'
import { authentication } from '../../mixins/authentication'
import { AUTH_SUCCESS, AUTH_ERROR, AUTH_MODAL_BTN, AUTH_SET_FB } from '../../store/actions.type'
import axios from 'axios'

export default {
    name: "Signup",
    mixins: [authentication],
    components: {
        Confirm, Error,
        Carousel,
        Slide,
        facebookLogin,
        GoogleLogin,
        KakaoLogin
    },
    data() {
        return {
            form: {
                email: '',
                password: '',
                password_confirmation: '',
                provider_name: ''
            },
            sform: {
                email: '',
                password: '',
                password_confirmation: '',
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
                    text: ''
                },
                password_confirmation: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Password does not match'
                }
            },
            isShowPassword: false,
            isShowPasswordConfirm: false,
            FB: undefined,
            isFBSuccess: false,
            isFBConnected: false,

            googleLoginParams: {
                client_id: '111229380791-13ep2n0iakp1j4kfimhdmk40l1ioggcl.apps.googleusercontent.com'
            }
        }
    },
    mounted() {
        this.FB = this.$store.getters.FB

        if (this.$route.params.email != undefined) {
            this.onLLoginDone(this.$route.params.email)
        }
    },
    methods: {
        onSignup() {
            var vm = this
            vm.form.provider_name = ''

            if (!vm.validate()) {
                return
            }

            localStorage.setItem('signup-form', JSON.stringify(vm.form))

            vm.signup(vm.form)
                .then(response => {
                    vm.onSuccess()
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        var errors = error.response.data.errors
                        if (errors.email.length > 0) {
                            vm.validator.email.error = true
                            vm.validator.email.text = errors.email[0]
                            vm.validator.email.last = vm.form.email
                        }
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Failed to sign up."])
                        vm.$bvModal.show('modal-error')
                    }
                })
        },
        onSuccess() {
            this.$bvModal.show('modal-confirm')
        },
        onFailed() {
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

            vm.validator.email.blured = true
            vm.validator.password.blured = true
            vm.validator.password_confirmation.blured = true

            if (vm.validEmail(vm.form.email) && vm.validPassword(vm.form.password) && vm.matchPassword(vm.form.password, vm.form.password_confirmation)) {
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
        // Facebook related module
        getFBUserData() {
            var vm = this

            if (!vm.isFBConnected || vm.FB == null) {
                return
            }

            vm.FB.api('/me', 'GET', { fields: 'id, name, email' },
                user => {
                    // vm.personalID = user.id
                    // vm.name = user.name
                    vm.sform.email = user.email
                    vm.sform.password = 'fbCSpwd1!'
                    vm.sform.password_confirmation = 'fbCSpwd1!'
                    vm.sform.provider_name = 'facebook'

                    if (vm.isFBSuccess && vm.isFBConnected) {
                        vm.signupWithSocial()
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
            vm.sform.password_confirmation = 'gCSpwd2@'
            vm.sform.provider_name = 'google'
            
            vm.signupWithSocial()
        },
        onGLoginFailure(error) {
            console.log(error)
        },
        // Line login
        onLLoginDone(email) {
            var vm = this

            vm.sform.email = email
            vm.sform.password = 'lCSpwd2@'
            vm.sform.password_confirmation = 'lCSpwd2@'
            vm.sform.provider_name = 'line'

            vm.signupWithSocial()
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
                vm.sform.password_confirmation = 'KCSpwd2@'
                vm.sform.provider_name = 'kakao'

                vm.signupWithSocial()
            })
        },
        onKakaoLoginFailure(error) {
            console.log(error)
        },
        signupWithSocial() {
            var vm = this

            localStorage.setItem('signup-form', JSON.stringify(vm.sform))

            vm.signup(vm.sform)
                .then(response => {
                    vm.loginAfterSocialSignup()
                })
                .catch(error => {
                    if (error.response.status == 422) {
                        vm.clearSForm()
                        var errors = error.response.data.errors
                        if (errors.email.length > 0) {
                            vm.$store.dispatch(AUTH_ERROR, error.response.data.errors.email)
                            vm.$store.dispatch(AUTH_MODAL_BTN, 'login')
                            vm.$bvModal.show('modal-error')
                        }
                    } else {
                        vm.$store.dispatch(AUTH_ERROR, ["Failed to sign up using your social account."])
                        vm.$bvModal.show('modal-error')
                    }
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
                    vm.$store.dispatch(AUTH_ERROR, ["Failed to log in using your social account."])
                    vm.$bvModal.show('modal-error')
                })
        },
        toLogin() {
            this.$router.push({ name: 'Login' })
        },
        toHome() {
            this.$router.push({ name: 'Home' })
        },
        clearSForm() {
            var vm = this
            vm.sform.provider_name = ''
            vm.sform.email = ''
            vm.sform.password = ''
            vm.sform.password_confirmation = ''
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./authentication.scss";
</style>
