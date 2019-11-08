<template>
  <div class="app flex-row">
    <div class="w-100">
      <h1>{{ $t("docsign.review.review") }}</h1>
      <hr class="mb-4" />
      <div class="row-content">
        <div class="all-recipients">
          <div class="title">{{ $t("docsign.review.title") }}</div>
          <b-form-group>
            <label for="company" class="subtitle mt-4">
              {{ $t("docsign.review.subtitle") }}
              <span class="text-danger">*</span>
            </label>
            <b-form-input 
              type="text" 
              :placeholder="coffeeSign"
              name="subject"
              :value="subject"
              v-model="review_form.subject"
              @input="inputSubject"
            ></b-form-input>
          </b-form-group>
          <b-form-group>
            <label for="vat" class="subtitle">{{ $t("docsign.review.email") }}</label>
            <b-form-textarea 
              type="text" 
              :placeholder="message" 
              class="email-msg"
              :value="input_message"
              v-model="review_form.message"
              @input="inputMessage"
            ></b-form-textarea>
          </b-form-group>
          <div class="subtitle">{{ $t("docsign.review.expiration") }}</div>
          <div class="d-flex align-items-center mt-3">
            <span>{{ $t("docsign.review.request.expire") }}</span>
            <b-input class="ml-3" value="120" style="width: 75px" v-model="review_form.expiration"></b-input>
          </div>
          <div class="d-flex align-items-center mt-3">
            <span>
              {{ $t("docsign.review.request.scheduled") }}
              <strong>{{$t('May 1, 2019')}}</strong> {{ $t("docsign.review.request.at") }}
              <strong>{{$t('6:59:59 AM')}}</strong>
            </span>
          </div>
        </div>
        <div class="col-right-pannel">
          <div class="content-card">
            <div class="title">{{ $t("docsign.review.documents") }}</div>
            <div v-for="(doc, key) in getDocuments" :key="key">
              <div class="mt-4" v-if="key==0">{{doc.document_name}}</div>
              <div class="mt-3" v-else>{{doc.document_name}}</div>
            </div>
          </div>
          <div class="content-card">
            <div class="title">{{ $t("docsign.review.recipients") }}</div>
            <div class="recipients">
              <div class="recipient-item" v-for="(recipient, index) in getRecipients" :key="index+1">
                <div class="recipient-number">{{index + 1}}</div>
                <div class="info">
                  <div class="w-100 d-flex justify-content-between flex-wrap">
                    <span class="subtitle">{{recipient.name}}</span>
                    <span class="ml-auto" v-if="recipient.action === 'sign'">
                      <i class="fa fa-pencil mr-2 comments"></i>
                      <span>{{$t('Needs to Sign')}}</span>
                    </span>
                    <span class="ml-auto" v-else-if="recipient.action === 'copy'">
                      <UserIcon icon="CC.svg" class="mr-2"></UserIcon>
                      <span>{{$t('Receives a copy')}}</span>
                    </span>
                    <span class="ml-auto" v-else>
                      <UserIcon icon="In personal signer.svg" class="mr-2"></UserIcon>
                      <span>{{$t('In personal signer')}}</span>
                    </span>
                  </div>
                  <div class="comments" v-if="recipient.email">{{recipient.email}}</div>
                  <div class="comments" v-else>Sharing via Kakao talk</div>
                </div>
              </div>
            </div>
            <div
              class="comments mt-4"
            >{{ $t("docsign.review.recipients") }}</div>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-end flex-wrap pt-4">
        <button class="btn btn-outline-primary min-width-124px m-1" v-on:click="moveBackPage()">{{ $t("docsign.back") }}</button>
        <button class="btn btn-primary min-width-124px m-1" v-on:click="moveNextPage()" style="position: relative">
        <i v-if="nextLoading" class="fa fa-spinner fa-spin fa-3x" style="position: absolute; top: 0; left: 33%;" />
        {{ $t("docsign.send") }}</button>
      </div>
    </div>
    <b-modal id="send-modal" ref="send-modal" hide-footer centered @hidden="onHidden">
      <div class="send-modal">
        <img src="img/icons/send.svg" class="mb-4" />
        <div class="you-done">{{ $t("docsign.review.modal.title") }}</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >{{ $t("docsign.review.modal.comments") }}</div>
        <button
          type="submit"
          class="btn btn-primary"
          v-on:click="$router.push('/payment/document-list');"
        >{{ $t("docsign.review.modal.button") }}</button>
      </div>
    </b-modal>

    <b-modal id="kakao-modal" ref="kakao-modal" hide-footer centered @hidden="onHiddenKakao">
      <div class="send-modal">
        <img src="img/icons/send.svg" class="mb-4" />
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >This account access time is already expired. Please, click button LOGIN VIA KAKAO to continue</div>
        <button
          type="submit"
          class="btn btn-primary"
          v-on:click="loginWithKakao"
        > LOGIN VIA KAKAO </button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import { signTypes } from "../../helpers/defaultValue";
import config from "../../config/config";
import { mapGetters, mapState } from 'vuex'
import store from '../../store/store'
import { GET_DOCS, REVIEW_SEND_REQUEST } from '../../store/actions.type'

export default {
  name: "Review",
  components: {
    UserIcon
  },
  data() {
    return {
      review_form:
      {
        subject: '',
        message: '',
        expiration: 120,
        recipients: [],
        name: ''
      },
      titleEmail:'',
      nextLoading: false,
      subjectData: '',
      messageData: '',
      isCheckAccessToken: false,
    };
  },
  created()
  {
    store.dispatch(GET_DOCS, this.$route.query.document_id)
    .then( res => {
      this.review_form.name = this.converDocuments().join(', ')
      this.review_form.recipients = this.getRecipients;
      this.review_form.subject = 'Sign Request for '+this.review_form.name+' from Coffeesign.io'
    })
    .catch( err => {

    });
  },
  mounted()
  {
    const scriptId = "kakao_login";
    const script = document.createElement("script");
    script.src = `https://developers.kakao.com/sdk/js/kakao.min.js`;
    script.onload = () => this.initiate(this);
    // script.onerror = error => this.handleError(error)
    script.id = scriptId;
    document.getElementsByTagName("head")[0].appendChild(script);
  },
  computed: {
    ...mapGetters({
        getRecipients: 'getRecipients',
        getDocuments: 'getDocuments'
      }),
    subject() {
      this.review_form.subject = this.$t('docsign.review.input_email_sub_1')+' '+this.review_form.name+' '+this.$t('docsign.review.input_email_sub_2');
      return this.review_form.subject;
    },
    input_message() {
      this.review_form.message = `${this.$t('docsign.review.input_message_1')}

${this.$t('docsign.review.input_message_2')}`
      
      return this.review_form.message;
    },
    coffeeSign() {
      return this.$t('docsign.review.placeholder.coffeeSign');
    },
    message() {
      return this.$t('docsign.review.placeholder.message');
    }
  },
  methods: {
    initiate: comp => {
      Kakao.init(config.KAKAO.javascriptKey);
    },
    loginWithKakao() {
      let _self = this;
      Kakao.Auth.login({
        scope: "talk_message,friends",
        success: function(resLogin) {
          _self.moveNextPage()
        },
        fail: function(res) {
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
      _self.$refs["kakao-modal"].hide();
    },
    checkAccessToken(){
      let _self = this;
      _self.isCheckAccessToken = false
      Kakao.API.request({
        url: "/v1/api/talk/friends",
        success: function(res) {
          _self.isCheckAccessToken = true
        },
        fail: function(res) {
          console.log('res____', res)
          Kakao.Auth.logout() 
          // if(res && res.msg) {
          //   _self.$awn.alert(res.msg, {
          //     position: "bottom-left",
          //     labels: {
          //       alert: "Danger Message"
          //     }
          //   });
          // }
          _self.$refs["kakao-modal"].show();
        }
      });
    },
    inputSubject(e) {
      this.subjectData = e;
    },
    inputMessage(e) {
      this.messageData = e;
    },
    moveNextPage() {
      let _self = this;
      let accessToken = '';
      let review_form = {
        ...this.review_form, 
        subject:this.subjectData.length > 0 ? this.subjectData : this.review_form.subject, 
        document_id: this.$route.query.document_id,
        message: this.messageData.length > 0 ? this.messageData : this.review_form.message, 
        access_token: accessToken.length > 0 ? accessToken : null,
      }
      this.nextLoading = true
      store.dispatch( REVIEW_SEND_REQUEST , review_form)
      .then( res => {
        this.$refs["send-modal"].show();
        this.nextLoading = false
      })
      .catch( err => {
        console.log('err', err)
      }) 
    },
    async moveNextPage1() {
      let _self = this;
      let accessToken = Kakao.Auth.getAccessToken()
      if(accessToken) {
        await _self.checkAccessToken()
        let review_form = {
          ...this.review_form, 
          subject:this.subjectData.length > 0 ? this.subjectData : this.review_form.subject, 
          document_id: this.$route.query.document_id,
          message: this.messageData.length > 0 ? this.messageData : this.review_form.message, 
          access_token: accessToken.length > 0 ? accessToken : null, 
        }
        this.nextLoading = true
        store.dispatch( REVIEW_SEND_REQUEST , review_form)
        .then( res => {
          this.$refs["send-modal"].show();
          this.nextLoading = false
        })
        .catch( err => {
          console.log('err', err)
        })
      } else {
        _self.$refs["kakao-modal"].show();
      } 
    },
    onHidden(){
      this.$router.push("/payment/document-list");
    },
    onHiddenKakao(){
      let _self = this
      _self.$refs["kakao-modal"].hide();
    },
    moveBackPage() {
      this.$router.push("/docu-sign/prepare?document_id="+this.$route.query.document_id);
    },
    converDocuments()
    {
      var data = this.getDocuments.map((animal) => {
        return animal.document_name
      })
      return data;
    }
  }
};
</script>

<style lang="scss">
@import "./Review.scss";
</style>

