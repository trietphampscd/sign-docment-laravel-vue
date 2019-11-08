<template>
  <b-container fluid class="signup auth-page">
    

    <custom-loader :own-loading="pageLoading" :no-sidebar="nosidebar"></custom-loader>
    <confirm></confirm>
    <error></error>

    <b-row>
      <b-col xl="5" lg="6" md="12" sm="12" xs="12" class="signup-left">

        <b-row class="header">
          <b-col xl="7" offset-xl="3" lg="8" offset-lg="2" md="8" offset-md="2" class="wrapper">

            <AuthHeader />

          </b-col>
        </b-row>

        <b-row class="auth-form">
          <b-col xl="7" offset-xl="3" lg="8" offset-lg="2" md="8" offset-md="2" class="wrapper">
            <b-form @submit.prevent="onSignup">
              <b-row class="no-gutters">
              <h3 class="form-title w-100">{{ $t("auth.signup") }}</h3>
              <span class="form-subtitle text-gray w-100">{{ $t("auth.signupsubtitle") }}&nbsp;<b-link href="javascript:;" @click="toLogin" class="text-coffee-light link">{{ $t("auth.signupsubtitle2") }}</b-link></span>

              <b-form-group id="grpFName"
                            :class="{
                              'form-group-withicon': true,
                              'col-md-6 col-12 pr-md-1 pr-auto': true,
                              'form-group-focus': validator.first_name.focus && !validator.first_name.error && (!validator.first_name.blured || validName(form.first_name)),
                              'form-group-invalid': validator.first_name.blured && (!validName(form.first_name) || validator.first_name.error),
                              'form-group-valid': validator.first_name.blured && (validName(form.first_name) && !validator.first_name.error)
                            }">
                <b-form-input id="first_name"
                            :class="{
                              'form-control-coffee': true,
                              'input-invalid': validator.first_name.blured && (!validName(form.first_name) || validator.first_name.error),
                              'input-valid': validator.first_name.blured && (validName(form.first_name) && !validator.first_name.error)
                            }"
                            @blur="validator.first_name.blured = true, validator.first_name.focus = false"
                            @focus ="validator.first_name.focus = true"
                            type="text"
                            v-model="form.first_name"
                            :placeholder="$t('auth.input.firstname')">
                </b-form-input>
                <font-awesome-icon icon="user" />
                <p class="validation-error" v-if="validator.first_name.blured && (!validName(form.first_name) || validator.first_name.error)">{{ validator.first_name.text }}</p>
              </b-form-group>

              <b-form-group id="grpLName"
                            :class="{
                              'form-group-withicon': true,
                              'col-md-6 col-12 pl-md-1 pl-auto': true,
                              'form-group-focus': validator.last_name.focus && !validator.last_name.error && (!validator.last_name.blured || validName(form.last_name)),
                              'form-group-invalid': validator.last_name.blured && (!validName(form.last_name) || validator.last_name.error),
                              'form-group-valid': validator.last_name.blured && (validName(form.last_name) && !validator.last_name.error)
                            }">
                <b-form-input id="last_name"
                            :class="{
                              'form-control-coffee': true,
                              'input-invalid': validator.last_name.blured && (!validName(form.last_name) || validator.last_name.error),
                              'input-valid': validator.last_name.blured && (validName(form.last_name) && !validator.last_name.error)
                            }"
                            @blur="validator.last_name.blured = true, validator.last_name.focus = false"
                            @focus ="validator.last_name.focus = true"
                            type="text"
                            v-model="form.last_name"
                            :placeholder="$t('auth.input.lastname')">
                </b-form-input>
                <font-awesome-icon icon="user" />
                <p class="validation-error" v-if="validator.last_name.blured && (!validName(form.last_name) || validator.last_name.error)">{{ validator.last_name.text }}</p>
              </b-form-group>

              <b-form-group id="grpEmail"
                            :class="{
                              'form-group-withicon': true,
                              'col-12': true,
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
                            :placeholder="$t('auth.input.email')"> 
                </b-form-input>
                <font-awesome-icon icon="envelope-open" />
                <p class="validation-error" v-if="validator.email.blured && (!validEmail(form.email) || validator.email.error)">{{ validator.email.text }}</p>
              </b-form-group>
              <b-form-group id="grpPassword"
                            :class="{
                              'form-group-withicon': true,
                              'col-12': true,
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
                              'col-12': true,
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
              <input type="submit" class="btn btn-block btn-md btn-coffee" :value="$t('auth.button.signup')"/>
              </b-row>
            </b-form>
            <span class="text-gray" style="display: block; margin-top: 20px;">{{ $t("auth.tnc") }} <a href="javascript:;" class="text-coffee-light link">{{ $t("auth.tnc2") }}</a> {{ $t("auth.tnc3") }} <a href="javascript:;" class="text-coffee-light link">{{ $t("auth.tnc4") }}</a></span>
            <div class="signup-withsocial">
              <p class="text-gray">{{ $t("auth.socupcomment") }}</p>
              <div class="social-links">
                <facebook-login 
                  :appId="fbAppId" 
                  class="button btn-facebook" 
                  @login="onFBLogin" 
                  @logout="onFBLogout" 
                  @sdk-loaded="onFBSdkLoaded" 
                  :key="fbkey">
                </facebook-login>
                <GoogleLogin 
                  class="button btn-google" 
                  :params="googleLoginParams" 
                  :onSuccess="onGLoginSuccess" 
                  :onFailure="onGLoginFailure">
                </GoogleLogin>
                <b-link :href="`https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=${lineLoginParams.client_id}&redirect_uri=${server_url}/api/auth/lineauth&state=signup12&scope=openid%20profile%20email`"><img src="/img/social/line.png"/></b-link>
                <KakaoLogin :api-key="kakaoLoginParams.api_key" :on-success="onKakaoLoginSuccess" :on-failure="onKakaoLoginFailure" />
              </div>

              <!-- <div class="social-links">
                <facebook-login appId="2349951131937784" class="button btn-facebook" @login="onFBLogin" @logout="onFBLogout" @sdk-loaded="onFBSdkLoaded"></facebook-login>
                <GoogleLogin class="button btn-google" :params="googleLoginParams" :onSuccess="onGLoginSuccess" :onFailure="onGLoginFailure"></GoogleLogin>
                <b-link href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1592490922&redirect_uri=https://www.coffeesign.io/api/auth/lineauth&state=signup12&scope=openid%20email"><img src="/img/social/line.png"/></b-link>
                <KakaoLogin api-key="b2f99b39dd3b4d340a7c0ef10260c2e4" :on-success="onKakaoLoginSuccess" :on-failure="onKakaoLoginFailure" />
              </div> -->

            </div>
          </b-col>
        </b-row>

      </b-col>

      <b-col xl="7" lg="6" class="signup-right pb-5">
        <h1>{{ $t("auth.carousel.title") }}</h1>
        <p>{{ $t("auth.carousel.subtitle") }}</p>
        <p>{{ $t("auth.carousel.subtitle2") }}</p>

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
                <img src="/img/carousel/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="/img/carousel/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="/img/carousel/slide01.png" class="img-responsive"/>
              </a>
            </slide>
            <slide class="pos-rel">
              <a target="_blank">
                <img src="/img/carousel/slide01.png" class="img-responsive"/>
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
import AuthHeader from './AuthHeader'
import Confirm from '../../components/signup/Confirm.modal'
import Error from '../../components/common/Error.modal'
import facebookLogin from 'facebook-login-vuejs'
import GoogleLogin from 'vue-google-login'
import KakaoLogin from 'vue-kakao-login'
import { authentication } from '../../mixins/authentication'
import { AUTH_REQUEST, AUTH_SUCCESS, AUTH_ERROR, AUTH_MODAL_BTN, AUTH_SET_FB } from '../../store/actions.type'
import axios from 'axios'
import CustomLoader from '../../components/common/CustomLoader'
import { mapGetters } from 'vuex'

export default {
    name: "Signup",
    mixins: [authentication],
    components: {
        AuthHeader,
        Confirm, Error,
        'custom-loader': CustomLoader,
        Carousel,
        Slide,
        facebookLogin,
        GoogleLogin,
        KakaoLogin
    },
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                provider_name: ''
            },
            sform: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                provider_name: ''
            },
            validator: {
                first_name: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Please input a valid First Name',
                    error: false,
                    last: ''
                },
                last_name: {
                    valid: true,
                    focus: false,
                    blured: false,
                    text: 'Please input a valid Last Name',
                    error: false,
                    last: ''
                },
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
                },
            },
            isShowPassword: false,
            isShowPasswordConfirm: false,
            pageLoading: false,
            nosidebar: true,

            /** URL Integration */
            client_url: process.env.VUE_APP_URL,
            server_url: process.env.VUE_APP_SERVER_URL,
            /** Facebook OAuth */
            FB: undefined,
            fbAppId: process.env.VUE_APP_FB_APP_ID,
            isFBSuccess: false,
            isFBConnected: false,
            fbkey: 0,
            /** Google OAuth2 */
            googleLoginParams: {
              client_id: process.env.VUE_APP_GOOGLE_CLIENT_ID
            },
            /** Line OAuth2 */
            lineLoginParams: {
              client_id: process.env.VUE_APP_LINE_CLIENT_ID
            },
            /** Kakao OAuth */
            kakaoLoginParams: {
              api_key: process.env.VUE_APP_KAKAO_API_KEY
            }
        }
    },
    computed: {
      ...mapGetters(['loading'])
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
            
            vm.pageLoading = true;

            localStorage.setItem('signup-form', JSON.stringify(vm.form))

            vm.signup(vm.form)
                .then(response => {
                    vm.onSuccess()

                    vm.pageLoading = false
                })
                .catch(error => {
                    vm.pageLoading = false
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

            vm.validator.first_name.blured = true
            vm.validator.last_name.blured = true
            vm.validator.email.blured = true
            vm.validator.password.blured = true
            vm.validator.password_confirmation.blured = true

            if (vm.validName(vm.form.first_name) && vm.validName(vm.form.last_name) && vm.validEmail(vm.form.email) && vm.validPassword(vm.form.password) && vm.matchPassword(vm.form.password, vm.form.password_confirmation)) {
                return true
            } else {
                return false
            }
        },
        validName(name) {
          // TEMP WORKING ON LATER (SOFIAN)
          var vm = this

          return true
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

            vm.FB.api('/me', 'GET', { fields: 'id, name, first_name, last_name, email' },
                user => {
                    // vm.personalID = user.id
                    // vm.name = user.name
                    vm.sform.email = user.email
                    vm.sform.password = 'fbCSpwd1!'
                    vm.sform.password_confirmation = 'fbCSpwd1!'
                    vm.sform.provider_name = 'facebook'
                    vm.sform.first_name = user.first_name || 'New'
                    vm.sform.last_name = user.last_name || 'User'

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
            vm.sform.first_name = profile.getGivenName()
            vm.sform.last_name = profile.getFamilyName()
            
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
            vm.sform.first_name = 'New'
            vm.sform.last_name = 'User'

            vm.signupWithSocial()
        },
        onKakaoLoginSuccess(data) {
            var vm = this
            axios.post(vm.server_url+'/api/auth/kprofile', {
                'token_type': data.token_type,
                'access_token': data.access_token
            }, {
                header: null
            }).then(response => {
                vm.sform.email = response.data.kakao_account.email
                vm.sform.password = 'KCSpwd2@'
                vm.sform.password_confirmation = 'KCSpwd2@'
                vm.sform.provider_name = 'kakao'
                vm.sform.first_name = response.data.properties.nickname || ' '
                vm.sform.last_name = ' '

                vm.signupWithSocial()
            })
        },
        onKakaoLoginFailure(error) {
            console.log(error)
        },
        signupWithSocial() {
            var vm = this
            vm.pageLoading = true

            localStorage.setItem('signup-form', JSON.stringify(vm.sform))

            vm.signup(vm.sform)
                .then(response => {
                    vm.pageLoading = false
                    vm.loginAfterSocialSignup()
                })
                .catch(error => {
                    vm.pageLoading = false
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
            vm.pageLoading = true

            vm.login(vm.sform)
                .then(response => {
                    vm.$store.dispatch(AUTH_REQUEST, response.data)
                    vm.clearSForm()
                    vm.toHome()

                    vm.pageLoading = false
                })
                .catch(error => {
                    vm.clearSForm()
                    vm.isLoginFailed = true
                    vm.pageLoading = false
                    vm.$store.dispatch(AUTH_ERROR, ["Failed to log in using your social account."])
                    vm.$bvModal.show('modal-error')
                })
        },
        toLogin() {
            this.$router.push({ name: 'Login' })
        },
        toHome() {
            this.$router.push({ name: 'LandingPage' })
        },
        clearSForm() {
            var vm = this
            vm.sform.provider_name = ''
            vm.sform.first_name = ''
            vm.sform.last_name = ''
            vm.sform.email = ''
            vm.sform.password = ''
            vm.sform.password_confirmation = ''
        }
    }
}
</script>

<style lang="scss" scoped>
// @import "./authentication.scss";
</style>
