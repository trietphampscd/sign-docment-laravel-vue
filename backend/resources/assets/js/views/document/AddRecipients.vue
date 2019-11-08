<template>
  <div class="app flex-row">
    <div class="w-100">
      <h1>{{ $t("layout.documents.header.recipients") }}</h1>
      <hr class="mb-4" />
      <div class="row">
        <div class="col-md-8">
          <div class="recipient" v-for="(recipient, index) in recipients" :key="index">
            <div class="recipient-head">
              <span class="name">{{ $t("docsign.recipients.title") }} #{{index+1}}</span>
              <span v-on:click="removeRecipient(index, recipient.id)">
                <UserIcon icon="delete.svg" :button="true" />
              </span>
            </div>
            <div class="row">
              <div class="col-md-6">
                <ul class="user-custom-select" :id="`user_select_${index+1}`">
                  <HeaderDropdown>
                    <template slot="header">
                      <i class="fa" :class="getTypeIcon(recipient.sign_type_name)"></i>
                      {{$t(recipient.sign_type_name)}}
                    </template>
                    <template slot="dropdown">
                      <b-dropdown-item
                        v-for="(sign, key) in sign_types"
                        :key="key"
                        :value="sign.value"
                        @click="changeSignTypeValue(sign.value, index, sign.name)"
                      >
                        <i :class="sign.class"></i>
                        {{$t(sign.name)}}
                      </b-dropdown-item>
                    </template>
                  </HeaderDropdown>
                </ul>
              </div>
              <div class="col-md-6">
                <b-form-group>
                  <b-button
                    :variant="recipient.com_type?'primary':'outline-primary'"
                    class="w-50"
                    :pressed.sync="recipient.com_type"
                  >{{ $t("docsign.recipients.button.email") }}</b-button>
                  <b-button
                    :variant="!recipient.com_type?'primary':'outline-primary'"
                    class="w-50"
                    :pressed.sync="recipient.com_type"
                    @click="loginWithKakao(recipient.com_type, index)"
                  >
                    {{ $t("docsign.recipients.button.talk") }}
                    <!-- <KakaoLogin api-key="6c4e91afbb15437d319ac9c9d38f1ac5" :on-success="onKakaoLoginSuccess" :on-failure="onKakaoLoginFailure" /> -->
                  </b-button>
                </b-form-group>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <b-form-group>
                  <b-input-group
                    v-bind:class="{'input-error':isRequired(recipient.name, error_flag)}"
                  >
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <i class="fa fa-user"></i>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input
                      type="text"
                      :placeholder="recipientName"
                      name="name"
                      v-model="recipient.name"
                    ></b-form-input>
                  </b-input-group>
                  <div
                    v-if="isRequired(recipient.name, error_flag)"
                    class="error-text"
                  >Please input Recipient Name</div>
                </b-form-group>
              </div>
              <div class="col-md-6">
                <div v-if="recipient.com_type">
                  <b-form-group>
                    <b-input-group
                      :class="{'input-error':isRequired(recipient.email, error_flag) || (recipient &&recipient.email && recipient.email.length > 0 && !isEmail(recipient.email))}"
                    >
                      <b-input-group-prepend>
                        <b-input-group-text>
                          <i class="fa fa-envelope"></i>
                        </b-input-group-text>
                      </b-input-group-prepend>
                      <b-form-input
                        type="email"
                        :placeholder="emailAddress"
                        autocomplete="email"
                        name="email"
                        v-model="recipient.email"
                      ></b-form-input>
                    </b-input-group>
                    <div
                      v-if="recipient &&recipient.email && recipient.email.length > 0 && !isEmail(recipient.email)"
                      class="error-text"
                    >Please enter valid email</div>
                    <div
                      v-else-if="isRequired(recipient.email, error_flag)"
                      class="error-text"
                    >Please input Email</div>
                    <div
                      v-else-if="error_flag && recipient.sign_type_name == 'In personal signer' && checkEmailClient && !isCheckEmailClient(recipient.email, emailClients)"
                      class="error-text"
                    >Email does not exist in the system</div>
                  </b-form-group>
                </div>
                <div v-if="!recipient.com_type">
                  <b-form-group>
                    <ul class="user-custom-select" :id="`user_select_${index+1}`">
                      <HeaderDropdown v-if="friends.length > 0"> 
                        <template slot="header">
                          <i class="fa fa-user"></i>
                          {{recipient.nickname}}
                        </template>
                        <template slot="dropdown">
                          <b-dropdown-item
                            v-for="(friend, key) in friends"
                            :key="key"
                            :value="friend.uuid"
                            @click="changeFriendTypeValue(friend.uuid, index, friend.profile_nickname)"
                          >
                          <i class="fa fa-user"></i>
                            {{friend.profile_nickname}}
                          </b-dropdown-item>
                        </template>
                      </HeaderDropdown>
                      <li v-else style="padding: 9px;width: 100%;"> 
                        <i class="fa fa-user" style="margin-right: 17px;color: #9ea0a5;"></i>
                        <span v-if="recipient && recipient.nickname" disabled>{{ recipient.nickname }}</span>
                        <span v-else>Data not found</span>
                        
                      </li>
                    </ul>
                  </b-form-group>
                </div>
              </div>
            </div>
            <b-form-group>
              <b-form-checkbox
                v-model="recipient.set_password"
              >{{ $t("docsign.recipients.password") }}</b-form-checkbox>
            </b-form-group>
            <div class="row" v-if="recipient.set_password">
              <div class="col-md-6">
                <b-form-group>
                  <b-input-group
                    :class="{'input-error':isRequired(recipient.password, error_flag)}"
                  >
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <!-- <i class="fa fa-lock"></i> -->
                        <UserIcon icon="pass.svg"></UserIcon>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input
                      type="password"
                      :placeholder="password"
                      autocomplete="current-password"
                      name="password"
                      v-model="recipient.password"
                    ></b-form-input>
                  </b-input-group>
                  <div
                    v-if="isRequired(recipient.password, error_flag)"
                    class="error-text"
                  >Please input Password</div>
                </b-form-group>
              </div>
              <div class="col-md-6">
                <b-form-group>
                  <b-input-group
                    :class="{'input-error':error_flag && !isConfirmPass(recipient.password, recipient.confirm_password)}"
                  >
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <!-- <i class="fa fa-lock"></i> -->
                        <UserIcon icon="pass.svg"></UserIcon>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input
                      type="password"
                      :placeholder="confirmPassword"
                      autocomplete="current-password"
                      name="confirm_password"
                      v-model="recipient.confirm_password"
                    ></b-form-input>
                  </b-input-group>
                  <div
                    v-if="error_flag && !isConfirmPass(recipient.password, recipient.confirm_password)"
                    class="error-text"
                  >Passwords don't match</div>
                </b-form-group>
              </div>
            </div>
          </div>
          <button class="btn btn-primary min-width-230px mt-4 mb-3" v-on:click="addRecipient()">
            <UserIcon icon="add-recipien.svg" class="mr-2" />
            {{ $t("docsign.recipients.recipient") }}
          </button>
        </div>
        <div class="col-md-4">
          <div class="document" v-for="(doc, index) in documents" :key="index">
            <div class="document-div">
              <img v-bind:src="getFileType(doc.doc_name)" class="folder-2" />
              <div class="docu-title">{{doc.doc_name}}</div>
              <!-- <div class="docu-pages">{{doc.pages}} {{doc.pages>1?"pages":"page"}}</div> -->
            </div>
          </div>
          <div class="d-flex justify-content-end mt-4 flex-wrap">
            <button
              class="btn btn-outline-primary min-width-124px m-1"
              v-on:click="moveBackPage()"
            >{{ $t("docsign.back") }}</button>
            <button
              class="btn btn-primary min-width-124px m-1"
              v-on:click="moveNextPage()"
              style="position: relative"
            >
              <i
                v-if="pageLoading"
                class="fa fa-spinner fa-spin fa-3x"
                style="position: absolute; top: 0; left: 33%;"
              />
              {{ $t("docsign.next") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import UserIcon from "../../components/UserIcon";
import VueTelInput from "vue-tel-input";
import { HeaderDropdown } from "@coreui/vue";
import { checkForm } from "../../mixins/validate.form";
import { mapGetters, mapState } from "vuex";
import config from "../../config/config";
// import  "../../helpers/kakao.min";
import store from "../../store/store";
// import KakaoLogin from 'vue-kakao-login'
import {
  RECIPIENTS_ADD,
  RECIPIENTS_UPDATE,
  GET_EMAIL_CLIENT,
  GET_DOCS,
  RECIPIENTS_REMOVE,
  GET_FRIENDS_KAKAO_REQUEST
} from "../../store/actions.type";
export default {
  name: "AddRecipients",
  components: {
    UserIcon,
    VueTelInput,
    HeaderDropdown
    // KakaoLogin
  },
  mixins: [checkForm],
  data() {
    return {
      togglePress: false,
      error_flag: false,
      submit: true,
      recipients: [
        {
          sign_type_name: "Needs to Sign",
          action: "sign",
          com_type: true,
          name: "",
          email: "",
          phone: "Select a friend",
          set_password: false,
          password: "",
          confirm_password: "",
          nickname: "Select a friend",
          uuid: "",
        }
      ],
      sign_types: [
        {
          value: "sign",
          name: "Needs to Sign",
          class: "fa fa-pencil"
        },
        {
          value: "copy",
          name: "Receives a Copy",
          class: "fa fa-cc"
        },
        {
          value: "signer",
          name: "In personal signer",
          class: "fa fa-eye"
        }
      ],
      documents: [
        {
          id: 1,
          doc_name: "Continuous Improvement lorem ipsum sit dollor amet.doc",
          doc_pages: 5
        },
        {
          id: 2,
          doc_name: "Ad cum numquam efficiantur.pdf",
          doc_pages: 1
        }
      ],
      friends: [],
      checkEmailClient: false,
      isUpdate: false,
      pageLoading: false,
      accessToken: '',
    };
  },
  computed: {
    ...mapGetters({
      emailClients: "getEmailClients",
      getRecipients: "getRecipients",
      getDocuments: "getDocuments"
    }),
    recipientName() {
      return this.$t("docsign.recipients.placeholder.recipient");
    },
    emailAddress() {
      return this.$t("docsign.recipients.placeholder.email");
    },
    password() {
      return this.$t("docsign.recipients.placeholder.password");
    },
    confirmPassword() {
      return this.$t("docsign.recipients.placeholder.confirm");
    }
  },
  created() {},
  mounted() {
    const scriptId = "kakao_login";
    const script = document.createElement("script");
    script.src = `https://developers.kakao.com/sdk/js/kakao.min.js`;
    script.onload = () => this.initiate(this);
    // script.onerror = error => this.handleError(error)
    script.id = scriptId;
    document.getElementsByTagName("head")[0].appendChild(script);

    store.dispatch(GET_EMAIL_CLIENT);
    store
      .dispatch(GET_DOCS, this.$route.query.document_id)
      .then(res => {
        var up_recipients = this.getRecipients;
        if (up_recipients && Object.keys(up_recipients).length != 0) {
          this.isUpdate = true;
          this.recipients = [];
          up_recipients.map((value, index) => {
            this.recipients.push(value);
          });
        }
        var up_documents = this.getDocuments;
        if (up_documents && Object.keys(up_documents).length != 0) {
          this.documents = [];
          up_documents.map((value, index) => {
            var data = Object.assign(
              {},
              {
                id: value.id,
                doc_name: value.document_name,
                doc_pages: 3
              }
            );
            this.documents.push(data);
          });
        }
      })
      .catch(err => {
        this.$router.push("/docu-sign/add-document");
      });
  },
  methods: {
    initiate: comp => {
      Kakao.init(config.KAKAO.javascriptKey);
      comp.accessToken = Kakao.Auth.getAccessToken();
      console.log('comp.accessToken', comp.accessToken)
      if (comp.accessToken) {
        comp.getTalkFriends()
      }
    },
    loginWithKakao(com_type, index) {
      let _self = this;
      _self.recipients[index].com_type = false;
      if(!_self.accessToken) {
        Kakao.Auth.login({
          scope: "talk_message,friends",
          success: function(resLogin) {
            _self.accessToken = Kakao.Auth.getAccessToken();
            console.log("===login", resLogin);
            _self.getTalkFriends();
          },
          fail: function(res) {
            console.log("===fail", res);
            Kakao.Auth.logout() 
            if(res && res.msg) {
              _self.$awn.alert(res.msg, {
                position: "bottom-left",
                labels: {
                  alert: "Danger Message"
                }
              });
            }
          }
        });
      } else {
        _self.getTalkFriends();
      }
    },
    getTalkFriends(){
      let _self = this;
      Kakao.API.request({
        url: "/v1/api/talk/friends",
        success: async function(res) {
          console.log('friend=======', res)
          _self.friends = []
          if(res && res.elements){
            _self.friends = res.elements;
          }
        },
        fail: function(res) {
          console.log('res____', res)
          Kakao.Auth.logout() 
          if(res && res.msg) {
            _self.$awn.alert(res.msg, {
              position: "bottom-left",
              labels: {
                alert: "Danger Message"
              }
            });
          }
          // Kakao.Auth.getRefreshToken()
          // _self.getTalkFriends()
        }
      });
    },
    onKakaoLoginSuccess(data) {
      store
        .dispatch(GET_FRIENDS_KAKAO_REQUEST, data)
        .then(res => {
          console.log("res-----", res);
        })
        .catch(err => {
          console.log("err____", err);
        });
    },
    onKakaoLoginFailure(data) {
      console.log("err......", data);
    },
    changeSignTypeValue(value, index, name) {
      this.recipients[index].action = value;
      this.recipients[index].sign_type_name = name;
    },
    changeFriendTypeValue(value, index, name) {
      this.recipients[index].uuid = value;
      this.recipients[index].phone = name;
      this.recipients[index].nickname = name;
    },
    getTypeIcon(_type) {
      if (_type == "Needs to Sign") {
        return "fa-pencil";
      } else if (_type == "Receives a Copy") {
        return "fa-cc";
      } else if (_type == "In personal signer") {
        return "fa-eye";
      }
    },
    getFileType(fileName) {
      return "img/icons/" + fileName.split(".").pop() + ".svg";
    },
    validateState(ref) {
      if (
        this.veeFields[ref] &&
        (this.veeFields[ref].dirty || this.veeFields[ref].validated)
      ) {
        return !this.veeErrors.has(ref);
      }
      return null;
    },
    moveNextPage() {
      var vm = this;
      var isRequiredPass = false;
      var isConfPass = false;
      var isRequiredEmail = false;
      var isEmail = false;
      var emailClient = false;
      vm.error_flag = true;
      var err_array = [];

      vm.recipients.map((value, index) => {
        if (value.com_type) {
          isRequiredEmail = vm.isRequired(value.email, vm.error_flag);
          isEmail = !vm.isEmail(value.email);
        }

        if (value.set_password) {
          isRequiredPass = vm.isRequired(value.password, vm.error_flag);
          isConfPass = !vm.isConfirmPass(
            value.password,
            value.confirm_password
          );
        } else {
          isRequiredPass = false;
          isConfPass = false;
        }

        if (value.sign_type_name == "In personal signer") {
          vm.checkEmailClient = true;
          emailClient = !vm.isCheckEmailClient(value.email, vm.emailClients);
          var emailData = vm.emailClients.filter((value2, key) => {
            return value2.email === value.email;
          });
          Object.assign(vm.recipients[index], {
            user_id: emailData[0] ? emailData[0].id : null
          });
        } else {
          emailClient = false;
        }
        // console.log( vm.isRequired(value.name, vm.error_flag), vm.isRequired(value.email, vm.error_flag), !vm.isEmail(value.email), isRequiredPass, isConfPass, emailClient)
        vm.isRequired(value.name, vm.error_flag) ||
        isRequiredEmail ||
        isEmail ||
        isRequiredPass ||
        isConfPass ||
        emailClient
          ? err_array.push(false)
          : err_array.push(true);
      });
      const data = {
        recipients: vm.recipients,
        documents: vm.documents,
        accessToken: vm.accessToken
      };
      if (err_array.indexOf(false) == -1) {
        if (!vm.isUpdate) {
          store
            .dispatch(RECIPIENTS_ADD, data)
            .then(res => {
              this.$router.push(
                "/docu-sign/prepare?document_id=" +
                  this.$route.query.document_id
              );
              vm.pageLoading = true;
            })
            .catch(err => {
              console.log("err", err);
            });
        } else {
          store
            .dispatch(RECIPIENTS_UPDATE, data)
            .then(res => {
              this.$router.push(
                "/docu-sign/prepare?document_id=" +
                  this.$route.query.document_id
              );
              vm.pageLoading = true;
            })
            .catch(err => {
              console.log("err", err);
            });
        }
      }
    },
    moveBackPage() {
      this.$router.push(
        "/docu-sign/add-document?document_id=" + this.$route.query.document_id
      );
    },
    addRecipient() {
      this.recipients.push({
        sign_type_name: "Needs to Sign",
        action: "sign",
        com_type: true,
        name: "",
        email: "",
        phone: "Select a friend",
        set_password: false,
        password: "",
        confirm_password: "",
        nickname: "Select a friend",
        uuid: ""
      });
    },
    removeRecipient(index, reci_id) {
      if (reci_id) {
        store.dispatch(RECIPIENTS_REMOVE, reci_id).then(resp => {
          if (resp && resp.status) {
            this.recipients.splice(index, 1);
          } else {
            window.confirm("Remove file fail");
          }
        });
      } else {
        this.recipients.splice(index, 1);
      }
    },
    converSignTypes(_value) {
      var data = this.sign_types.filter(animal => {
        return animal.value === _value;
      });
      return data[0].name;
    }
  }
};
</script>

<style lang="scss">
@import "./AddRecipients.scss";
</style>

