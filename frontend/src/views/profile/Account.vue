<template>
  <div class="app flex-row" ref="fileform">

    <custom-loader :own-loading="validator.pageLoading" :no-sidebar="validator.nosidebar"></custom-loader>

    <div class="w-100" id="account">
      <h1>{{ $t("profile.account.title") }}</h1>
      <hr class="mb-4" />
      <div class="profile-header content-card">
        <div class="user-happy">
          <div class="avatar-tag">
            <div class="change-avatar" v-on:click="changeAvatar">Change</div>
            <img :src="getAvatar" ref="avatarhead" :alt="getUser.email" style="min-width: 150px; width: 100%; height: auto" @error="errorAvatar" />
          </div>
          <div class="ml-3">
            <div class="user-name">{{ getName }}</div>
            <span class="comments">{{ getEmail }}</span>
            <div>
              <b-button variant="link" class="p-0 mt-3" v-on:click="changePassword">{{ $t("profile.account.password") }}</b-button>
            </div>
          </div>
        </div>
        <div class="user-connection" hidden>
          <div class="connect-social">{{ $t("profile.account.networks") }}</div>
          <div class="socials">
            <b-button variant="link" class="p-0">
              <UserIcon icon="fb.svg" class="mr-1 mr-sm-3 social-link" />
            </b-button>
            <b-button variant="link" class="p-0">
              <UserIcon icon="g_plus.svg" class="mr-1 mr-sm-3 social-link" />
            </b-button>
            <b-button variant="link" class="p-0">
              <UserIcon icon="line_disabled.svg" class="mr-1 mr-sm-3 social-link" />
            </b-button>
            <b-button variant="link" class="p-0">
              <UserIcon icon="talk_disabled.svg" class="social-link" />
            </b-button>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <div class="content-card">
            <div class="content-header">
              <strong>{{ $t("profile.account.header") }}</strong>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="first_name"
                    :placeholder="$t('profile.account.placeholder.firstname')"
                    name="first_name"
                    v-bind:class="{'input-error': isError(form_data.first_name)}"
                    v-model="form_data.first_name"
                  />
                  <div
                    v-if="isError(form_data.first_name)"
                    class="error-text"
                  >{{ $t('profile.account.error.first') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="last_name"
                    :placeholder="$t('profile.account.placeholder.lastname')"
                    name="last_name"
                    v-bind:class="{'input-error': isError(form_data.last_name)}"
                    v-model="form_data.last_name"
                  />
                  <div v-if="isError(form_data.last_name)" class="error-text">{{ $t('profile.account.error.last') }}</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <template>
                <ul class="user-custom-select" 
                    v-bind:class="{'input-error': form_data.purpose == 'Purpose of using' && form_data.error_flag}">
                  <HeaderDropdown>
                    <template slot="header">
                      <div style="width:100%; overflow:hidden;">{{form_data.purpose}}</div>
                    </template>
                    <template slot="dropdown" >
                      <b-dropdown-item v-on:click="changePurposeValue('', 'Purpose of using')">{{ $t('profile.account.placeholder.purpose') }}</b-dropdown-item>
                      <b-dropdown-item
                        v-for="(purp, index) in purposeSizes"
                        :key="index"
                        :value="purp.value ? purp.value : ''"
                        @click="changePurposeValue(purp.value, purp.name)"
                      >{{purp.name}}</b-dropdown-item>
                    </template>
                  </HeaderDropdown>
                </ul>
                <div v-if="form_data.purpose == 'Purpose of using' && form_data.error_flag" class="error-text">{{ $t("profile.account.error.purpose") }}</div>
              </template>
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="company_name"
                    :placeholder="$t('profile.account.placeholder.company')"
                    name="company_name"
                    v-model="form_data.company_name"
                    v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.company_name)}"
                  />
                  <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.company_name)" class="error-text">{{ $t("profile.account.error.company") }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <template>
                  <ul class="user-custom-select"
                      v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.employee == 'Employee' && form_data.error_flag}">
                    <HeaderDropdown>
                      <template slot="header">
                        <div style="width:100%; overflow:hidden;">{{form_data.employee}}</div>
                      </template>
                      <template slot="dropdown">
                        <b-dropdown-item v-on:click="changeEmployeeValue('', 'Employee')">{{ $t('profile.account.placeholder.employee') }}</b-dropdown-item>
                        <b-dropdown-item
                          v-for="(emp, index) in employeeSizes"
                          :key="index"
                          :value="emp.id ? emp.id : ''"
                          v-on:click="changeEmployeeValue(emp.id, emp.size)"
                        >{{emp.size == '1001-3000' ? 'More than 1000' : emp.size}}</b-dropdown-item>
                      </template>
                    </HeaderDropdown>
                  </ul>
                  <div v-if="form_data.purpose == 'My Business' && form_data.employee == 'Employee' && form_data.error_flag" class="error-text">{{ $t('profile.account.error.employee') }}</div>
                </template>
              </div>
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="department_name" 
                    :placeholder="$t('profile.account.placeholder.job')"
                    name="department_name"
                    v-model="form_data.title"
                    v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.error_flag && isError(form_data.title)}"
                  />
                  <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && form_data.title == ''" class="error-text">{{ $t('profile.account.error.job') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <template>
                    <ul class="user-custom-select"
                      v-bind:class="{'input-error': form_data.purpose == 'My Business' && form_data.industry == 'Select Industry' && form_data.error_flag}">
                      <HeaderDropdown>
                        <template slot="header">
                          <div style="width:100%; overflow:hidden;">{{form_data.industry}}</div>
                        </template>
                        <template slot="dropdown">
                          <b-dropdown-item v-on:click="changeIndustryValue('', 'Select Industry')">{{ $t('profile.account.placeholder.industry') }}</b-dropdown-item>
                          <b-dropdown-item
                            v-for="(indus, index) in industrySizes"
                            :key="index"
                            :value="indus.id ? indus.id : ''"
                            v-on:click="changeIndustryValue(indus.id, indus.industry_name)"
                          >{{indus.industry_name}}</b-dropdown-item>
                        </template>
                      </HeaderDropdown>
                    </ul>
                    <div v-if="form_data.purpose == 'My Business' && form_data.error_flag && form_data.industry == 'Select Industry'" class="error-text">{{ $t('profile.account.error.industry') }}</div>
                  </template>
                </div>
              </div>
            </div>
            <button v-on:click="updateProfile" class="btn btn-primary w-100">{{ $t('layout.documents.modal.button') }}</button>
          </div>
        </div>
      </div>
    </div>
    
    <b-modal id="change-password-modal" ref="change-password-modal"
       hide-footer centered size="xl">
      <div class="change-password-modal">
        <div class="text-center"><img src="img/icons/mail password.svg" /></div>
        <div class="title">{{ $t("profile.account.password") }}</div>
        <div class="content-card text-left">


          <b-form @submit.prevent="savePassword">
            <b-row class="no-gutters">
              <b-form-group id="grpPassword"
                            :class="{
                              'form-group-withicon': true,
                              'col-12': true,
                              'form-group-focus': validator.oldPassword.focus && (!validator.oldPassword.blured || validOldPassword(form_data.oldPassword)),
                              'form-group-password': true,
                              'form-group-invalid': validator.oldPassword.blured && !validOldPassword(form_data.oldPassword),
                              'form-group-valid': validator.oldPassword.blured && validOldPassword(form_data.oldPassword)
                            }">
                <b-form-input id="oldPassword"
                              :class="{
                                'form-control-coffee': true,
                                'password': true,
                                'input-valid': validator.oldPassword.blured && (validOldPassword(form_data.oldPassword)),
                                'input-invalid': !validOldPassword(form_data.oldPassword) && validator.oldPassword.blured
                              }"
                              @blur="validator.oldPassword.blured = true, validator.oldPassword.focus = false"
                              @focus="validator.oldPassword.focus = true"
                              :type="showOldPwd?'text':'password'"
                              v-model="form_data.oldPassword"
                              :placeholder="$t('auth.input.password')">
                </b-form-input>
                <div class="eye" href="javascript:;" @click="showOldPwd = !showOldPwd"><font-awesome-icon icon="eye-slash" v-if="showOldPwd" /><font-awesome-icon icon="eye" v-else /></div>
                <font-awesome-icon icon="lock" />
                <p class="validation-error" v-if="(!validOldPassword(form_data.oldPassword) || validator.oldPassword.error) && validator.oldPassword.blured">{{ validator.oldPassword.text }}</p>
              </b-form-group>
              <b-form-group id="grpNewPassword"
                            :class="{
                              'form-group-withicon': true,
                              'col-12': true,
                              'form-group-focus': validator.newPassword.focus && (!validator.newPassword.blured || validOldPassword(form_data.newPassword)),
                              'form-group-password': true,
                              'form-group-invalid': validator.newPassword.blured && !validOldPassword(form_data.newPassword),
                              'form-group-valid': validator.newPassword.blured && validOldPassword(form_data.newPassword)
                            }">
                <b-form-input id="newPassword"
                              :class="{
                                'form-control-coffee': true,
                                'password': true,
                                'input-valid': validator.newPassword.blured && (validNewPassword(form_data.newPassword)),
                                'input-invalid': !validNewPassword(form_data.newPassword) && validator.newPassword.blured
                              }"
                              @blur="validator.newPassword.blured = true, validator.newPassword.focus = false"
                              @focus="validator.newPassword.focus = true"
                              :type="showNewPwd?'text':'password'"
                              v-model="form_data.newPassword"
                              :placeholder="$t('profile.account.placeholder.new')">
                </b-form-input>
                <div class="eye" href="javascript:;" @click="showNewPwd = !showNewPwd"><font-awesome-icon icon="eye-slash" v-if="showNewPwd" /><font-awesome-icon icon="eye" v-else /></div>
                <font-awesome-icon icon="lock" />
                <p class="validation-error" v-if="(!validNewPassword(form_data.newPassword) || validator.newPassword.error) && validator.newPassword.blured">{{ validator.newPassword.text }}</p>
              </b-form-group>
              <b-form-group id="grpConfirmPassword"
                            :class="{
                              'form-group-withicon': true,
                              'col-12': true,
                              'form-group-focus': validator.confirmPassword.focus && (!validator.confirmPassword.blured || matchPassword(form_data.newPassword, form_data.confirmPassword)),
                              'form-group-password': true,
                              'form-group-invalid': validator.confirmPassword.blured && !matchPassword(form_data.newPassword, form_data.confirmPassword),
                              'form-group-valid': validator.confirmPassword.blured && matchPassword(form_data.newPassword, form_data.confirmPassword)
                            }">
                <b-form-input id="confirmPassword"
                              :class="{
                                'form-control-coffee': true,
                                'password': true,
                                'input-valid': validator.confirmPassword.blured && (matchPassword(form_data.newPassword, form_data.confirmPassword)),
                                'input-invalid': !matchPassword(form_data.newPassword, form_data.confirmPassword) && validator.confirmPassword.blured
                              }"
                              @blur="validator.confirmPassword.blured = true, validator.confirmPassword.focus = false"
                              @focus="validator.confirmPassword.focus = true"
                              :type="showNewConfirm?'text':'password'"
                              v-model="form_data.confirmPassword"
                              :placeholder="$t('profile.account.placeholder.confirm')">
                </b-form-input>
                <div class="eye" href="javascript:;" @click="showNewConfirm = !showNewConfirm"><font-awesome-icon icon="eye-slash" v-if="showNewConfirm" /><font-awesome-icon icon="eye" v-else /></div>
                <font-awesome-icon icon="lock" />
                <p class="validation-error" v-if="(!matchPassword(form_data.newPassword, form_data.confirmPassword) || validator.confirmPassword.error) && validator.confirmPassword.blured">{{ validator.confirmPassword.text }}</p>
              </b-form-group>
            </b-row>

            <div class="text-center">
              <b-button variant="outline-primary" class="mr-3" v-on:click="cancelChange" >{{ $t("profile.account.cancel") }}</b-button>
              <button type="submit" class="btn btn-primary">{{ $t("profile.account.save") }}</button>
            </div>
          </b-form>
        </div>
      </div>
    </b-modal>
    
    <b-modal id="change-avatar-modal" ref="change-avatar-modal" hide-footer centered size="xl">
      <div class="change-avatar-modal">
        <div class="title">{{ $t("profile.account.avatar") }}</div>

        <div class="img-control-btns">
           <input 
            type="file" 
            ref="avatar_file"
            style="display: none"  
            @change="uploadImg($event, 1)" />
          <b-button variant="outline-primary" v-on:click="changeAvatarImg">{{ $t("profile.account.otheravatar") }}</b-button>
          <div>
            <i class="fa fa-rotate-left clickable-icon" v-on:click="rotateLeft"/>
            <i class="fa fa-rotate-right clickable-icon mx-3" v-on:click="rotateRight"/>
          </div>
        </div>
        
        <div class="cut">
          <vue-cropper v-if="imgPreview"
            ref="cropper" 
            :img="option.img" 
            :src="avatar"
            :output-size="option.size" 
            :output-type="option.outputType" 
            :info="true" :full="option.full" 
            :fixed="fixed"
            :can-move="option.canMove" 
            :can-move-box="option.canMoveBox" 
            :fixed-box="option.fixedBox" 
            :original="option.original"
            :auto-crop="option.autoCrop" 
            :auto-crop-width="option.autoCropWidth" 
            :auto-crop-height="option.autoCropHeight" 
            :center-box="option.centerBox"
            @real-time="realTime" :high="option.high"
            @img-load="imgLoad" mode="cover"
          ></vue-cropper>

          <p class="title" style="font-size: 20px !important" v-else>{{ $t('profile.account.otheravatarsubtitle') }}</p>
        </div>

        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelAvatar" >{{ $t("profile.account.cancel") }}</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="saveAvatar">{{ $t("profile.account.save") }}</button>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import Vue from 'vue'
import { VueCropper } from 'vue-cropper'
import { mapGetters, mapState } from 'vuex'
import { HeaderDropdown } from '@coreui/vue'
import { 
  CHANGE_IMAGE_REQUEST, 
  GET_USER_INFOR_REQUEST, 
  USER_UPDATE_REQUEST,
  USER_UPDATE_PASSAUTH_REQUEST,
  CHECK_CLIENT,
  AUTH_ERROR, 
  AUTH_LOADING
} from '../../store/actions.type'
import { firstDocuSign } from '../../mixins/firstDocuSign'
import UserSelect from '../../components/UserSelect'
import UserIcon from '../../components/UserIcon'
import { EventBus } from '../../config/event-bus'
import store from '../../store/store'
import CustomLoader from '../../components/common/CustomLoader'

import 'cropperjs/dist/cropper.css';

export default {
  name: "Account",
  components: {
    UserIcon,
    UserSelect,
    VueCropper,
    HeaderDropdown,
    'custom-loader': CustomLoader
  },
  mixins: [firstDocuSign],
  data() {
    return {
      form_data: {
        error_flag: false,
        first_name: '',
        last_name: '',
        purpose: "Purpose of using",
        company_name: '',
        employee: "Number of Employees",
        title: '',
        industry: "Select Industry",
        oldPassword: '',
        newPassword: '',
        confirmPassword: ''
      },
      client_form: {
        first_name: '',
        last_name: '',
        company_name: '',
        account_type: 0,
        company_size_id: 1,
        industry_id: 1,
        department_name: ''
      },
      password_form: {
        password_old: '',
        password_new: '',
        password_confirm: ''
      },
      employeeSizes : [],
      purposeSizes : [
        {
          value: "Personnel",
          name: "My Personnel use"
        },
        {
          value: "Business",
          name: "My Business"
        }
      ],
      industrySizes : [],

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
        purpose: {
          valid: true,
          focus: false,
          blured: false,
          text: ''
        },
        company_name: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Please input a valid Company Name',
          error: false,
          last: ''
        },
        employee: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Number of Employee',
          error: false,
          last: ''
        },
        title: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Please select a valid Department',
          error: false,
          last: ''
        },
        industry: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Please select a valid Industry',
          error: false,
          last: ''
        },
        oldPassword: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Please input your current password',
          error: false,
        },
        newPassword: {
          valid: true,
          focus: false,
          blured: false,
          text: ''
        },
        confirmPassword: {
          valid: true,
          focus: false,
          blured: false,
          text: 'Password does not match',
          error: false,
        },
        pageLoading: false,
        nosidebar: false
      },
      isOldPassword: false,  
      isShowPassword: false,
      isShowPasswordConfirm: false,
      showOldPwd: false,
      showNewPwd: false,
      showNewConfirm: false,

      files: [],
      imgPreview: false,
      avatar: './img/avatars/default.png',
      previews: {},
      cropped: null,
      option: {
        img: this.test && this.test.user && this.test.user.avatar ? this.test.user.avatar :'./img/avatars/default.png',
        size: 1,
        full: false,
        outputType: 'png',
        canMove: true,
        fixedBox: false,
        original: false,
        canMoveBox: true,
        autoCrop: true,
        autoCropWidth: 200,
        autoCropHeight: 150,
        centerBox: false,
        high: true
      },
      fixed: true,
      fixedNumber: [1, 2],      
    };
  },
  computed: {
    ...mapGetters(['getUser']),
    getAvatar() {
      return this.getUser.avatar || './img/avatars/default.png'
    },
    getName() {
      return (this.getUser.first_name + ' ' + this.getUser.last_name) || 'New User'
    },
    getEmail() {
      return this.getUser.email
    }
  },
  created() {
    store.dispatch(GET_USER_INFOR_REQUEST)
    .then(response => {
      response.user.avatar ? this.option.img = response.user.avatar : this.option.img;
    })
    .catch(error => {
      console.log(error.response)
    });
  },
  mounted() {
    this.getAllData()
    this.getCompanySizesDoc()
    this.getIndustriesDoc()
  },
  methods: {
    /** User Default */
    getAllData() {
      this.form_data.first_name = this.getUser.first_name || ''
      this.form_data.last_name = this.getUser.last_name || ''

      if (this.getUser.client) {
        this.client_form.account_type = this.getUser.client.account_type
        this.form_data.purpose = this.getUser.client.account_type
        
        this.form_data.company_name = this.getUser.client.company_name || ''
        this.form_data.title = this.getUser.client.department_name || ''

        this.client_form.company_size_id = this.getUser.client.company_size_id;
        this.form_data.employee = this.getUser.client.size_from + '-' + this.getUser.client.size_to;

        this.client_form.industry_id = this.getUser.client.industry_id;
        this.form_data.industry = this.getUser.client.industry_name;
      }
    },

    /** Default Field */
    getCompanySizesDoc() {
      var vm = this;
      vm.getCompanySizes()
        .then( res => {
          this.employeeSizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    getIndustriesDoc() {
      var vm = this;
      vm.getIndustries()
        .then( res => {
          this.industrySizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    getDepartmentsDoc() {
      var vm = this;
      vm.getDepartments()
        .then( res => {
          // this.purposeSizes = res.data;
        })
        .catch( error => {
          console.log('error', error);
        })
    },
    
    /** Update Avatar */
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files || !files.length) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        this.avatar = e.target.result;
      }.bind(this);
      reader.readAsDataURL(files[0]);
    },
    uploadImg(e, num) {
      var file = e.target.files[0]
      if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
        return false
      }
      this.imgPreview = true
      var reader = new FileReader()
      reader.onload = (e) => {
        let data
        if (typeof e.target.result === 'object') {
          data = window.URL.createObjectURL(new Blob([e.target.result]))
        } else {
          data = e.target.result
        }
        if (num === 1) {
          this.option.img = data
        } else if (num === 2) {
          this.example2.img = data
        }
      }
      reader.readAsArrayBuffer(file)
    },   
    saveAvatar(type) {
      this.$store.dispatch(AUTH_LOADING, true)

      this.$refs.cropper.getCropData((data) => {
        store.dispatch(CHANGE_IMAGE_REQUEST, {avatar: data})
        .then(response => {
          this.$toast.success({
            title: "Avatar Updated",
            message: "User's avatar have updated!"
          });
          this.option.img = response.user.avatar;
          this.$refs['avatarhead'].src = response.user.avatar;
          this.$refs['change-avatar-modal'].hide();
          this.$store.dispatch(AUTH_LOADING, false)
          this.$router.push({ name: 'LandingPage' })
        })
        .catch(error => {
          this.$store.dispatch(AUTH_LOADING, false)
          this.$toast.error({
            title: "Error!",
            message: error.response.data
          });
          console.log(error) 
        });
      })
    },
    changeAvatarImg() {
      this.$refs.avatar_file.click();
    },
    changeAvatar() {
      this.nosidebar = true
      this.$refs['change-avatar-modal'].show();
    },
    cancelAvatar() {
      this.$refs['change-avatar-modal'].hide();
      this.nosidebar = false
    },
    rotateLeft() {
      this.$refs.cropper.rotateLeft()
    },
    rotateRight() {
      this.$refs.cropper.rotateRight()
    },  
    rotate(rotationAngle) {
      this.$refs['cropper'].rotate(rotationAngle);
    },
    imgLoad(msg) {
      console.log(msg)
    }, 
    realTime(data) {
      this.previews = data
    },

    /** Update Password */
    savePassword() {
      var vm = this
      
      vm.form_data.error_flag = true;

      if (!vm.validate()) {
        return
      }

      vm.$store.dispatch(AUTH_LOADING, true)

      vm.password_form.password_old = vm.form_data.oldPassword
      vm.password_form.password_new = vm.form_data.newPassword
      vm.password_form.password_confirm = vm.form_data.confirmPassword

      vm.$store.dispatch(USER_UPDATE_PASSAUTH_REQUEST, vm.password_form)
        .then(response => {
          // Success
          vm.$store.dispatch(AUTH_LOADING, false)
          vm.clearPassForm()
          
          vm.$toast.success({
            title: "Password Changed",
            message: "User's password have changed!"
          });
          // console.log(response.data)
        })
        .catch(error => {
          vm.$store.dispatch(AUTH_LOADING, false)
          vm.clearPassForm()

          if (error.response.status == 422) {
            vm.$store.dispatch(AUTH_ERROR, error.response.data.errors)
          } else if (error.response.status == 401) {
            var errors = error.response.data.errors
            if (errors.password) {
              vm.validator.oldPassword.error = true
              vm.validator.oldPassword.text = errors.password[0]
            }            
            vm.$toast.error({
              title: "Error!",
              message: error.response.data.errors.password[0]
            });
            return
          }

          vm.$store.dispatch(AUTH_ERROR, ['Error occured while login.'])
          vm.$bvModal.show('modal-error')
        })
      this.$refs['change-password-modal'].hide()
    },
    changePassword() {
      this.$refs['change-password-modal'].show()
    },
    cancelChange() {
      this.$refs['change-password-modal'].hide()
    },

    /** Update Profile */
    updateProfile() {
      var vm = this
      vm.form_data.error_flag = true;
      if (vm.isError(vm.form_data.first_name)) return;
      if (vm.isError(vm.form_data.last_name)) return;

      if(vm.form_data.purpose != "Purpose of using"){
        if (vm.form_data.industry === 'My Personnel use' && (vm.isError(vm.form_data.title) || vm.isError(vm.form_data.last_name) || vm.form_data.industry === 'Select Industry' || vm.form_data.employee == 'Employee')) return;
        
        vm.$store.dispatch(AUTH_LOADING, true)

        vm.client_form.id = this.$store.state.authentication.user.id
        vm.client_form.first_name = vm.form_data.first_name;
        vm.client_form.last_name = vm.form_data.last_name;
        vm.client_form.company_name = vm.form_data.company_name;
        vm.client_form.department_name = vm.form_data.title;

        console.log(vm.client_form)

        vm.$store.dispatch(USER_UPDATE_REQUEST, vm.client_form)
          .then(response => {
            // Success
            vm.$store.dispatch(AUTH_LOADING, false)
            this.$toast.success({
              title: "Profile Updated",
              message: "User's info have updated!"
            });
          })
          .catch(error => {
            vm.$store.dispatch(AUTH_LOADING, false)            
            this.$toast.error({
              title: "Error!",
              message: error.response.data
            });
            console.log(error.response)
          })
      }
    },

    /** Utils */
    changePurposeValue(key, value) {
      this.client_form.account_type = key;
      this.form_data.purpose = value;
    },
    changeIndustryValue(key, value) {
      this.client_form.industry_id = key;
      this.form_data.industry = value;
    },
    changeEmployeeValue(key, value) {
      this.client_form.company_size_id = key;
      this.form_data.employee = value;
    },
    clearPassForm() {
      var vm = this
      vm.form_data.oldPassword = ''
      vm.form_data.newPassword = ''
      vm.form_data.confirmPassword = ''

      vm.password_form.password_old = ''
      vm.password_form.password_new = ''
      vm.password_form.password_confirm = ''

      vm.validator.oldPassword.error = false
      vm.validator.oldPassword.text = ''
      vm.validator.newPassword.error = false
      vm.validator.newPassword.text = ''
      vm.validator.confirmPassword.error = false
      vm.validator.confirmPassword.text = ''
    },

    /** Error Handling */
    validate() {
      var vm = this
      
      vm.validator.oldPassword.blured = true
      vm.validator.newPassword.blured = true
      vm.validator.confirmPassword.blured = true

      if (vm.validOldPassword(vm.form_data.oldPassword) && vm.validNewPassword(vm.form_data.newPassword) && vm.matchPassword(vm.form_data.newPassword, vm.form_data.confirmPassword)) {
        return true
      } else {
        return false
      }
    },
    validOldPassword(oldPassword) {
      var vm = this
      if (oldPassword.length < 6 || oldPassword.length > 12) {
        vm.validator.oldPassword.valid = false
        vm.validator.oldPassword.text = 'Password must be 6 ~ 12 length characters'
        return false
      } else if (!(/[a-z]/.test(oldPassword))) {
        vm.validator.oldPassword.valid = false
        vm.validator.oldPassword.text = 'Password must contain at least 1 lower case letter'
        return false
      } else if (!(/[A-Z]/.test(oldPassword))) {
        vm.validator.oldPassword.valid = false
        vm.validator.oldPassword.text = 'Password must contain at least 1 capital letter'
        return false
      } else if (!(/[0-9]/.test(oldPassword))) {
        vm.validator.oldPassword.valid = false
        vm.validator.oldPassword.text = 'Password must contain at least 1 number'
        return false
      } else if (!(/[!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/.test(oldPassword))) {
        vm.validator.oldPassword.valid = false
        vm.validator.oldPassword.text = 'Password must contain at least 1 special character'
        return false
      }

      return true
    },
    validNewPassword(newPassword) {
      var vm = this
      if (newPassword.length < 6 || newPassword.length > 12) {
        vm.validator.newPassword.valid = false
        vm.validator.newPassword.text = 'Password must be 6 ~ 12 length characters'
        return false
      } else if (!(/[a-z]/.test(newPassword))) {
        vm.validator.newPassword.valid = false
        vm.validator.newPassword.text = 'Password must contain at least 1 lower case letter'
        return false
      } else if (!(/[A-Z]/.test(newPassword))) {
        vm.validator.newPassword.valid = false
        vm.validator.newPassword.text = 'Password must contain at least 1 capital letter'
        return false
      } else if (!(/[0-9]/.test(newPassword))) {
        vm.validator.newPassword.valid = false
        vm.validator.newPassword.text = 'Password must contain at least 1 number'
        return false
      } else if (!(/[!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/.test(newPassword))) {
        vm.validator.newPassword.valid = false
        vm.validator.newPassword.text = 'Password must contain at least 1 special character'
        return false
      }

      return true
    },
    matchPassword(newPassword, confirmPassword) {
      var vm = this
      if (confirmPassword.length < 6 || confirmPassword.length > 12) {
        vm.validator.confirmPassword.valid = false
        vm.validator.confirmPassword.text = 'Password must be 6 ~ 12 length characters'
        return false
      } else if (!(/[a-z]/.test(confirmPassword))) {
        vm.validator.confirmPassword.valid = false
        vm.validator.confirmPassword.text = 'Password must contain at least 1 lower case letter'
        return false
      } else if (!(/[A-Z]/.test(confirmPassword))) {
        vm.validator.confirmPassword.valid = false
        vm.validator.confirmPassword.text = 'Password must contain at least 1 capital letter'
        return false
      } else if (!(/[0-9]/.test(confirmPassword))) {
        vm.validator.confirmPassword.valid = false
        vm.validator.confirmPassword.text = 'Password must contain at least 1 number'
        return false
      } else if (!(/[!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]/.test(confirmPassword))) {
        vm.validator.confirmPassword.valid = false
        vm.validator.confirmPassword.text = 'Password must contain at least 1 special character'
        return false
      } else if (newPassword !== confirmPassword) {
        vm.validator.confirmPassword.valid = false;
        vm.validator.confirmPassword.text = "Password doesn't match"
        return false
      }

      return true
    },
    isError(value) {
      return (
        this.form_data.error_flag &&
        (value === undefined ||
          value === null ||
          (typeof value === "object" && Object.keys(value).length === 0) ||
          (typeof value === "string" && value.trim().length === 0))
      );
    },
     errorAvatar() {
      this.$refs['avatarhead'].src = './img/avatars/default.png'
    }
  }
};
</script>

<style lang="scss">
@import "./Account.scss";
</style>

