<template>
  <div class="app flex-row" ref="fileform">
    <div class="w-100" id="account">
      <h1>Personal Information</h1>
      <hr class="mb-4" />
      <div class="profile-header content-card">
        <div class="user-happy">
          <img :src="selected_avatar" class="clickable-icon" v-on:click="changeAvatar()"/>
          <div class="ml-3">
            <div class="user-name">Suzanne Thompson</div>
            <span class="comments">email_example@mail.com</span>
            <div>
              <b-button variant="link" class="p-0 mt-3" v-on:click="changePassword()">Change Password</b-button>
            </div>
          </div>
        </div>
        <div class="user-connection">
          <div class="connect-social">Connect social networks</div>
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
              <strong>Company & Job Title</strong>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="first_name"
                    placeholder="First Name"
                    name="first_name"
                    v-bind:class="{'input-error': isError(form_data.first_name)}"
                    v-model="form_data.first_name"
                  />
                  <div
                    v-if="isError(form_data.first_name)"
                    class="error-text"
                  >Please input Frist Name</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="last_name"
                    placeholder="Last Name"
                    name="last_name"
                    v-bind:class="{'input-error': isError(form_data.last_name)}"
                    v-model="form_data.last_name"
                  />
                  <div v-if="isError(form_data.last_name)" class="error-text">Please input Last Name</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <UserSelect
                :value="form_data.purpose"
                :items="['Purpose of using', 'My Personnel use', 'My Business']"
                @changeValue="changePurposeValue"
              />
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="company"
                    placeholder="Company"
                    name="company"
                    v-model="form_data.company"
                  />
                  <!-- v-bind:class="{'input-error': isError(form_data.company)}"
                  <div v-if="isError(form_data.company)" class="error-text">Please input Company</div>-->
                </div>
              </div>
              <div class="col-sm-6">
                <UserSelect
                  v-bind:value="form_data.employee"
                  v-bind:items="[
                  'Number of Employees',
                  '0-5',
                  '6-10',
                  '11-20',
                  '21-50',
                  '51-100',
                  '101-300',
                  '301-1000',
                  'More then 1000']"
                  @changeValue="changeEmployeeValue"
                />
              </div>
            </div>
            <div class="row" v-if="form_data.purpose != 'My Personnel use'">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="job_title"
                    placeholder="Job Title"
                    name="job_title"
                    v-model="form_data.title"
                  />
                  <!-- v-bind:class="{'input-error': isError(form_data.title)}" -->
                  <!-- <div v-if="isError(form_data.title)" class="error-text">Please input Job Title</div> -->
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <UserSelect
                    v-bind:value="form_data.industry"
                    @changeValue="changeIndustryValue"
                    v-bind:items="[
                    'Select Industry',
                    'Accounting &amp; Tax Accounting',
                    'Business Services / Consulting Business Services',
                    'Construction - General Construction',
                    'Construction - Home Builder Real Estate - Construction',
                    'Debt Settlement Debt Settlement/Loan Modification',
                    'Education Education',
                    'Financial Services - Banking Banking',
                    'Financial Services - Credit Unions Credit Unions',
                    'Financial Services - Other Financial Services',
                    'Financial Services - Wealth &amp; Asset Management Wealth Management',
                    'Government - City Government - City',
                    'Government - County Government - County',
                    'Government - Federal Government - National',
                    'Government - State Government - State',
                    'Healthcare - Health Plans &amp; Payers Insurance - Health',
                    'Healthcare - Providers Healthcare - Providers',
                    'HR Staffing HR Staffing',
                    'Insurance - Agents / Brokers Insurance - Agency',
                    'Insurance - Carriers Insurance - Carriers',
                    'Legal Legal',
                    'Life Sciences - Medical Devices Life Sciences - Medical Devices',
                    'Life Sciences - Other Life Sciences - Other',
                    'Life Sciences - Pharmaceuticals Life Sciences - Pharmaceuticals',
                    'Life Sciences - Wholesale Distributors Life Sciences - Wholesale/Distributor',
                    'Manufacturing',
                    'Mortgage Broker Real Estate - Mortgage',
                    'Not For Profit',
                    'Real Estate - Agent Real Estate - Agent',
                    'Real Estate - Broker/Owner Real Estate - Broker/Owner',
                    'Real Estate - Commercial Real Estate - Commercial',
                    'Real Estate - Property Management Real Estate - Property Management',
                    'Retail Retail',
                    'Sports &amp; Entertainment Travel &amp; Leisure',
                    'Technology - General Technology',
                    'Technology - Startup Technology',
                    'Telecommunications',
                    'Other']"
                  />
                </div>
              </div>
            </div>
            <button v-on:click="getStarted()" class="btn btn-primary w-100">Update</button>
          </div>
        </div>
      </div>
    </div>
    
    <b-modal id="change-password-modal" ref="change-password-modal"
       hide-footer centered size="xl">
      <div class="change-password-modal">
        <div class="text-center"><img src="img/icons/mail password.svg" /></div>
        <div class="title">Change Password</div>
        <div class="content-card">
          <div class="form-group">
            <div class="d-flex-align-center">
              <input
                class="form-control"
                id="cur_pwd"
                placeholder="Current Password*"
                name="cur_pwd"
                required
                :type="showOldPwd ? 'text' : 'password'"
              />
              <i class="fa fa-eye clickable-icon ml-3" v-on:click="showOldPwd = !showOldPwd"/>
            </div>
          </div>
          <div class="form-group">
            <div class="d-flex-align-center">
              <input
                class="form-control"
                id="new_pwd"
                placeholder="New Password*"
                name="new_pwd"
                required
                :type="showNewPwd ? 'text' : 'password'"
                />
                <i class="fa fa-eye clickable-icon ml-3" v-on:click="showNewPwd = !showNewPwd"/>
            </div>
          </div>
          <div class="form-group">
            <div class="d-flex-align-center">
              <input
                class="form-control"
                id="confirm_pwd"
                placeholder="Confirm New Password*"
                name="confirm_pwd"
                required
                :type="showNewConfirm ? 'text' : 'password'"
              />
              <i class="fa fa-eye clickable-icon ml-3" v-on:click="showNewConfirm = !showNewConfirm"/>
            </div>
          </div>
        </div>
        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelChange()" >Cancel</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="savePassword()">Save changes</button>
        </div>
      </div>
    </b-modal>
    
    <b-modal id="change-avatar-modal" ref="change-avatar-modal"
       hide-footer centered size="xl">
      <div class="change-avatar-modal">
        <div class="title">Change Avatar</div>

        <div class="img-control-btns">
          <input type="file" ref="avatar_file"
            style="display: none"  @change="onFileChange" />
          <b-button variant="outline-primary" v-on:click="changeAvatarImg()">Other Image</b-button>
          <div>
            <i class="fa fa-rotate-left clickable-icon" v-on:click="rotate(-90)"/>
            <i class="fa fa-rotate-right clickable-icon mx-3" v-on:click="rotate(90)"/>
          </div>
        </div>
        
        <vue-cropper
          ref="cropper"
          :src="avatar"
          alt="Source Image"
          :cropmove="cropped"
          class="my-4"
          :minContainerHeight="300"
        >
        </vue-cropper>
        <div class="text-center">
          <b-button variant="outline-primary" class="mr-3" v-on:click="cancelAvatar()" >Cancel</b-button>
          <button type="submit" class="btn btn-primary" v-on:click="saveAvatar()">Save changes</button>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";

import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
export default {
  name: "Account",
  components: {
    UserIcon,
    UserSelect,
    VueCropper
  },
  data() {
    return {
      cropped: null,
      avatars: [
        "img/avatars/scott@3x.png",
        "img/avatars/hannah@3x.png",
        "img/avatars/suzanne@3x.png",
      ],
      avatar: "img/avatars/510-510.png",
      selected_avatar: "img/avatars/suzanne@3x.png",
      showOldPwd: false,
      showNewPwd: false,
      showNewConfirm: false,
      form_data: {
        error_flag: false,
        first_name: "",
        last_name: "",
        purpose: "Purpose of using",
        company: "",
        employee: "Number of Employees",
        title: "",
        industry: "Select Industry"
      }
    };
  },
  methods: {
    
    changeAvatarImg() {
      this.$refs.avatar_file.click();
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      if (!files || !files.length) return;
      var reader = new FileReader();
      reader.onload = function(e) {
        this.img_file = e.target.result;
        console.log(this.img_file);
      };
      reader.readAsDataURL(files[0]);
    },
    rotate(rotationAngle) {
      this.$refs['cropper'].rotate(rotationAngle);
    },
    saveAvatar() {
      this.$refs['change-avatar-modal'].hide();
    },
    changeAvatar() {
      this.$refs['change-avatar-modal'].show();
    },
    changePassword() {
      this.$refs['change-password-modal'].show();
    },
    cancelAvatar() {
      this.$refs['change-avatar-modal'].hide();
    },
    cancelChange() {
      this.$refs['change-password-modal'].hide();
    },
    savePassword() {
      this.$refs['change-password-modal'].hide();
    },
    changePurposeValue(e) {
      this.form_data.purpose = e;
    },
    changeIndustryValue(e) {
      this.form_data.industry = e;
    },
    changeEmployeeValue(e) {
      this.form_data.employee = e;
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
    getStarted() {
      this.form_data.error_flag = true;
      if (this.isError(this.form_data.first_name)) return;
      if (this.isError(this.form_data.last_name)) return;
      this.confirmed = true;
    }
  }
};
</script>

<style lang="scss">
@import "./Account.scss";
</style>

