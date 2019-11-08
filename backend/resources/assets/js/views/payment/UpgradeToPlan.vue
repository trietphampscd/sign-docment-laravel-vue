<template>
  <div class="app flex-row">
    <div class="w-100 position-relative">
      <div class="plan-header">
        <h1>Upgrade To {{plan.title}}</h1>
      </div>
      <hr class="mb-4" />
      <div class="row plans">
        <div class="col-12 col-sm-6 col-md-8 col-lg-8">
          <div class="content-card">
            <div class="content-header">Payment information</div>
            <b-tabs>
              <b-tab title="Card" active>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="name">Credit/debit card number</label>
                      <b-input-group
                        class="pl-3"
                        v-bind:class="{'input-error': isError(card_number)}"
                      >
                        <b-form-input placeholder="**** **** **** 5432" v-model="card_number"></b-form-input>
                        <b-input-group-prepend class="mr-0">
                          <b-input-group-text class="h-auto">
                            <UserIcon icon="mastercard.svg" />
                          </b-input-group-text>
                        </b-input-group-prepend>
                      </b-input-group>
                      <div v-if="isError(last_name)" class="error-text">Please input card number</div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 pl-lg-0">
                    <div class="d-flex align-items-end">
                      <div class="form-group">
                        <label for="name">Expiration month</label>
                        <div class="d-flex align-items-center">
                          <UserSelect
                            v-bind:value="month"
                            v-bind:items="['Month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']"
                            @changeValue="month=$event"
                            class="mb-0"
                            style="min-width:105px"
                          />
                          <span class="mx-2">/</span>
                        </div>
                      </div>

                      <div class="form-group mr-3">
                        <label for="name">Expiration year</label>
                        <UserSelect
                          v-bind:value="year"
                          v-bind:items="years"
                          @changeValue="year=$event"
                          class="mb-0"
                          style="min-width:95px"
                        />
                      </div>
                      <div class="form-group">
                        <label for="name">CVV</label>
                        <input
                          type="text"
                          class="form-control"
                          id="cvv"
                          placeholder="Your CVV"
                          name="cvv"
                          v-bind:class="{'input-error': isError(cvv)}"
                          v-model="cvv"
                        />
                        <div v-if="isError(cvv)" class="error-text">Please input CVV</div>
                      </div>
                    </div>
                  </div>
                </div>
              </b-tab>
              <b-tab title="PAYPAL">
                <label class="mr-3">Pay with PayPal</label>
                <a href="https://www.paypal.com">
                  <img src="img/icons/paypal.png" />
                </a>
              </b-tab>
            </b-tabs>
          </div>
          <div class="content-card">
            <div class="content-header">Billing information</div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <input
                    type="text"
                    class="form-control"
                    id="first_name"
                    placeholder="First Name"
                    name="first_name"
                    v-bind:class="{'input-error': isError(first_name)}"
                    v-model="first_name"
                  />
                  <div v-if="isError(first_name)" class="error-text">Please input First Name</div>
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
                    v-bind:class="{'input-error': isError(last_name)}"
                    v-model="last_name"
                  />
                  <div v-if="isError(last_name)" class="error-text">Please input Last Name</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Country</label>
                  <UserSelect
                    v-bind:value="country"
                    v-bind:items="countries"
                    @changeValue="country=$event"
                  />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">State</label>
                  <input
                    type="text"
                    class="form-control"
                    id="state"
                    placeholder="State"
                    name="state"
                    v-bind:class="{'input-error': isError(state)}"
                    v-model="state"
                  />
                  <div v-if="isError(state)" class="error-text">Please input state</div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Street Address</label>
                  <input
                    type="text"
                    class="form-control"
                    id="street_addr"
                    placeholder="Street Address"
                    name="street_addr"
                    v-bind:class="{'input-error': isError(street_addr)}"
                    v-model="street_addr"
                  />
                  <div v-if="isError(street_addr)" class="error-text">Please input Street Address</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Zip code</label>
                  <input
                    type="text"
                    class="form-control"
                    id="zip_code"
                    placeholder="Zip code"
                    name="zip_code"
                    v-bind:class="{'input-error': isError(zip_code)}"
                    v-model="zip_code"
                  />
                  <div v-if="isError(zip_code)" class="error-text">Please input Zip code</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4">
          <div class="plan-card content-card">
            <div class="content-header">You're buying:</div>
            <div class="plan-card-head">
              <div class="plan-title">
                {{plan.title}}
                <img
                  v-bind:src="'img/icons/'+plan.title+'.png'"
                  width="32"
                  height="32"
                  class="ml-3"
                />
              </div>
              <div>
                <span class="comments">Per month</span>
                <span class="price">
                  ${{plan.price}}
                  <sup>.00</sup>
                </span>
              </div>
            </div>
            <UserSelect
              v-bind:value="subscription"
              v-bind:items="['Monthly Subscription', 'Annual Subscription']"
              @changeValue="subscription=$event"
              class="mb-0 mt-3"
              style="min-width:105px"
            />
            <hr />
            <div class="text-center">
              <div class="limit-for-sending">Limit for Sending for Signature</div>
              <div class="limit-times">{{plan.limit}}</div>
            </div>
            <hr />
            <b-button block variant="other" v-on:click="showModal()">Upgrade my plan!</b-button>
            <div class="mt-3">
              By clicking "Upgrade my plan!" you agree to the
              <b-button variant="link" class="p-0">Terms and Conditions</b-button>.
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-modal id="done-modal" ref="done-modal" hide-footer>
      <div class="done-modal">
        <img src="img/icons/send.svg" class="mb-4" />
        <div class="you-done">Your plan is upgraded</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >Thank You for upgrading to {{plan.title}}! You can always downgrade when you are not in needs any more.</div>
        <button type="submit" class="btn btn-primary" v-on:click="upgradeMyPlan()">Get start</button>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import axios from "axios";
import Vue from "vue";
import CxltToastr from "cxlt-vue2-toastr";
var toastrConfigs = {
  position: "top right",
  showDuration: 500,
  delay: 0,
  timeOut: 5000,
  hideDuration: 500,
  progressBar: true,
  color: "#00c292"
  // icon: "img/icons/Info@2x.png"
};
Vue.use(CxltToastr, toastrConfigs);

export default {
  name: "UpgradeToPlan",
  components: {
    UserIcon,
    UserSelect
  },
  data() {
    return {
      plan: null,
      subscription: "Monthly subscription",
      country: "Select Country",
      countries: [],
      region: "Select Region",
      month: "Month",
      year: "Year",
      years: [],

      error_flag: false,
      first_name: "",
      last_name: "",
      card_number: "",
      cvv: "",
      state: "",
      street_addr: "",
      zip_code: ""
    };
  },
  created() {
    this.plan = this.$route.query.plan;
    this.id = this.$route.query.id;
    if (!this.plan.title) {
      this.$router.push({
        path: "/payment/upgrade-plan"
      });
    }
    const dt = new Date();
    const start = dt.getYear();
    for (let i = start; i < start + 10; i++) {
      this.years.push((i + 1900).toString());
    }
    axios({ method: "GET", url: "https://restcountries.eu/rest/v1/all" }).then(
      result => {
        this.countries = [];
        this.countries.push("Select Country");
        result.data.forEach(country => {
          this.countries.push(country.name);
        });
      },
      error => {
        console.error(error);
      }
    );
  },
  methods: {
    showModal() {
      this.error_flag = true;
      if (this.isError(this.first_name)) return;
      if (this.isError(this.last_name)) return;
      if (this.isError(this.card_number)) return;
      if (this.isError(this.cvv)) return;
      if (this.isError(this.state)) return;
      if (this.isError(this.street_addr)) return;
      if (this.isError(this.zip_code)) return;
      this.$refs["done-modal"].show();
    },
    isError(value) {
      return (
        this.error_flag &&
        (value === undefined ||
          value === null ||
          (typeof value === "object" && Object.keys(value).length === 0) ||
          (typeof value === "string" && value.trim().length === 0))
      );
    },
    upgradeMyPlan() {
      // this.$toast.success({
      //   title: "Congratulation!",
      //   message: "Selected " + this.plan.title + " plan!"
      // });
      this.$router.push({
        path: "/landing",
        query: { id: this.id }
      });
    }
  }
};
</script>

<style lang="scss">
@import "./UpgradeToPlan.scss";
</style>

