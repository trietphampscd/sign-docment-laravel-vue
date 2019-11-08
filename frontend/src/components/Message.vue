<template>
  <div class="message">
    <div class="message-toggle-icon" v-on:click="chat_room =!chat_room">
      <span v-if="!chat_room">
        <UserIcon icon="subtraction-icon.svg" />
      </span>
      <span v-if="chat_room">
        <UserIcon icon="close-icon.svg" />
      </span>
    </div>
    <div class="substraction__wrap" v-bind:class="chat_room?'active':''">
      <div class="substraction__title">
        <div class="substraction__logo">
          <img src="img/logo_white.svg" alt="Coffee Sign" width="120" />
        </div>
        <h3 class="substraction__h3">Hi there! 👋</h3>
        <p
          class="substraction__text"
        >Please fill out the form below to start chatting with the next available agent.</p>
      </div>
      <!-- /.substraction__title -->
      <form action="#" class="substraction__form">
        <b-form-group>
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <i class="fa fa-user"></i>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-form-input type="text" placeholder="Recipient Name*"></b-form-input>
          </b-input-group>
        </b-form-group>
        <b-form-group>
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <i class="fa fa-envelope"></i>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-form-input type="email" placeholder="Email Address*" autocomplete="email"></b-form-input>
          </b-input-group>
        </b-form-group>
        <b-form-group>
          <b-input-group>
            <b-input-group-prepend>
              <b-input-group-text>
                <i class="fa fa-commenting"></i>
              </b-input-group-text>
            </b-input-group-prepend>
            <b-form-textarea type="text" placeholder="Enter Message" row="5" class="email-msg"></b-form-textarea>
          </b-input-group>
        </b-form-group>
        <b-button type="submit" block variant="primary">Start Chat</b-button>
      </form>
      <!-- /.substraction__form -->

      <b-button variant="link" block>
        We're by
        <b>tawk.to</b>
      </b-button>
    </div>
  </div>
</template>

<script>
import UserIcon from "./UserIcon";
export default {
  name: "Message",
  components: {
    UserIcon
  },
  data() {
    return {
      chat_room: false
    };
  },
  methods: {}
};
</script>
<style lang="scss">
@import "./Message.scss";
</style>
