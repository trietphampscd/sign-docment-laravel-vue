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
            <b-tabs v-model="tabIndex">
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
                      <div v-if="isError(card_number)" class="error-text">Please input valid card number</div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 pl-lg-0">
                    <div class="d-flex align-items-start">
                      <div class="form-group">
                        <label for="name">Expiration month</label>
                        <div class="d-flex align-items-center">
                          <UserSelect
                            v-bind:value="month"
                            v-bind:items="['Month', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']"
                            @changeValue="month=$event"
                            class="mb-0"
                            style="min-width:105px"
                            v-bind:class="{'input-error': month == 'Month' && error_flag}"
                          />
                          <span class="mx-2">/</span>
                        </div>
                        <div v-if="month == 'Month' && error_flag" class="error-text">Select a month</div>
                      </div>

                      <div class="form-group mr-3">
                        <label for="name">Expiration year</label>
                        <UserSelect
                          v-bind:value="year"
                          v-bind:items="years"
                          @changeValue="year=$event"
                          class="mb-0"
                          style="min-width:95px"
                          v-bind:class="{'input-error': year == 'Year' && error_flag}"
                        />
                        <div v-if="year == 'Year' && error_flag" class="error-text">Select a year</div>
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
                        <div v-if="isError(cvv)" class="error-text">Please input valid CVV</div>
                      </div>
                    </div>
                  </div>
                </div>
              </b-tab>
              <b-tab title="PAYPAL">
                <label class="mr-3">Pay with PayPal</label>
                <!--
                <a href="https://www.paypal.com">
                  <img src="img/icons/paypal.png" />
                </a>
                -->
                <PayPal v-if="showpaypal" :amount="this.amount" currency="USD" :client="credentials" env="sandbox" @payment-authorized="payment_authorized_cb" @payment-completed="payment_completed_cb" @payment-cancelled="payment_cancelled_cb"></PayPal>
              </b-tab>
            </b-tabs>
          </div>
          <div class="content-card">
            
            <div v-if="tabIndex == 0" class="content-header">Billing information</div>
            <div v-if="tabIndex == 1" class="content-header">Please click on "Upgrade my plan" button to continue payment with PayPal</div>
            <div v-if="tabIndex == 0" class="form-group">
              <label for="name">Country</label>
              <div v-bind:class="{'input-error': (country == '' || country == 'Select country') && error_flag}">
                <ejs-dropdownlist id='dropdownlist' 
                  :dataSource="countries"
                  :change = "changeCountryEx"
                  placeholder = "Select country"
                  >
                </ejs-dropdownlist>
              </div>
              <div v-if="(country == '' || country == 'Select country') && error_flag" class="error-text">Please select a country</div>
            </div>
            <div v-if="tabIndex == 0" class="form-group">
              <label for="name">Street address</label>
              <input
                type="text"
                class="form-control"
                id="billing_addr"
                placeholder="Street address"
                name="billing_addr"
                v-bind:class="{'input-error': isError(billing_addr)}"
                v-model="billing_addr"
              />
              <div v-if="isError(billing_addr)" class="error-text">Please enter a street address</div>
            </div>
            <div v-if="tabIndex == 0" class="form-group">
              <label for="name">Street address line 2</label>
              <input
                type="text"
                class="form-control"
                id="billing_addr1"
                placeholder="Enter your street address line 2 (Optional)"
                name="billing_addr1"
                v-model="billing_addr1"
              />
            </div>
            <div v-if="tabIndex == 0" class="row">
              <div class="col-sm-4">
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
                  <div v-if="isError(zip_code)" class="error-text">Please enter a valid zip Code</div>
                </div>
              </div>
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="name">City</label>
                  <input
                    type="text"
                    class="form-control"
                    id="city"
                    placeholder="City"
                    name="city"
                    v-bind:class="{'input-error': isError(city)}"
                    v-model="city"
                  />
                  <div v-if="isError(city)" class="error-text">Please enter a valid city</div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="name">State</label>
                  <div v-bind:class="{'input-error': (state == '' || state == 'Select state') && error_flag}">
                    <ejs-dropdownlist id='dropdownlist-state' 
                      :dataSource="states"
                      :change = "changeStateEx"
                      placeholder = "Select state"
                      >
                    </ejs-dropdownlist>
                  </div>
                  <div v-if="(state == '' || state == 'Select state') && error_flag" class="error-text">Please select a state</div>
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
                <span class="comments">{{pricecomment}}</span>
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
            <div class="d-flex-align-center justify-content-between mt-2" v-if="!edit_promo_code">
              <span>Promo Code: <strong>{{promo_code}}</strong></span>
              <b-button variant="link" v-on:click="edit_promo_code=true">{{promo_code.length==0?'Add Code': 'Edit Code'}}</b-button>
            </div>
            <div class="mt-2" v-if="edit_promo_code">
              <div class="form-group">
                <label for="name">Promo code</label>
                <input
                  type="text"
                  class="form-control"
                  id="promo_code"
                  placeholder="Promo Code"
                  name="promo_code"
                  v-model="promo_code"
                />
              </div>
              <div class="text-center">
                <b-button variant="primary" v-on:click="edit_promo_code=false; show_promo_price();" class="mr-3">Add Code</b-button>
                <b-button variant="link" v-on:click="edit_promo_code=false">Cancel</b-button>
              </div>
            </div>
            

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

    <b-modal id="process-modal" ref="process-modal" hide-footer>
      <div class="done-modal">
        <div class="you-done">Payment for ${{amount}}</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >Your request is being processed. Please standby.</div>
      </div>
    </b-modal>

    <b-modal id="failed-modal" ref="failed-modal" hide-footer>
      <div class="done-modal">
        <div class="you-done">Payment Failed!!</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >Please check your information and try again.</div>
      </div>
    </b-modal>

    <b-modal id="cancelled-modal" ref="cancelled-modal" hide-footer>
      <div class="done-modal">
        <div class="you-done">Payment cancelled!</div>
      </div>
    </b-modal>

    <b-modal id="promocode-modal" ref="promocode-modal" hide-footer>
      <div class="done-modal">
        <div class="you-done">Promo Code Added Successfully</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >You have 10% discount now.</div>
      </div>
    </b-modal>

    <b-modal id="fpromocode-modal" ref="fpromocode-modal" hide-footer>
      <div class="done-modal">
        <div class="you-done">Promo Code Invalid!</div>
        <div
          class="comments text-center"
          style="margin-bottom:30px"
        >Please check your promo code and try again.</div>
      </div>
    </b-modal>

    <input type="hidden" id="promocode_used" value="0"/>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import country_region_list from "country-region-data/data";
import Vue from "vue";
import { DropDownListPlugin  } from '@syncfusion/ej2-vue-dropdowns';
Vue.use(DropDownListPlugin);
import PayPal from 'vue-paypal-checkout'

export default {
  name: "UpgradeToPlan",
  components: {
    UserIcon,
    UserSelect,
    PayPal
  },
  data() {
    return {
      promo_code: "",
      edit_promo_code: false,
      plan: null,
      subscription: "Monthly Subscription",
      country: "Select country",
      countries: [],
      region: "Select region",
      month: "Month",
      year: "Year",
      years: [],

      error_flag: false,
      first_name: "",
      last_name: "",
      card_number: "",
      cvv: "",
      state: "Select state",
      states: [],
      billing_addr: "",
      billing_addr1: "",
      city: "",
      zip_code: "",
      street_addr: "",
      amount: "",
      error_status: 0,
      planmode: "",
      paypal: {
        sandbox: 'ARv33Gg8JreFiRa0QWebUQVdokycZo7cL6wLMYD204bjkSmaeMybGqZCfIFJ16Gq5rmV-q7iSjpIaux3',
        production: '<production client id>'
      },
      credentials: {
        sandbox: 'ARv33Gg8JreFiRa0QWebUQVdokycZo7cL6wLMYD204bjkSmaeMybGqZCfIFJ16Gq5rmV-q7iSjpIaux3',
        production: '<production client id>'
      },
      showpaypal: false,
      tabIndex: 0,
      pricecomment: "Per month",
      myItems: [{ "name": "CoffeeSign Plan - "+this.subscription, "description": "Subscription Plan", "price": this.amount, "quantity": "1", "currency": "USD"}]
    };
  },
  created() {
    this.plan = this.$route.query.plan;
    this.id = this.$route.query.id;
    this.planmode = this.$route.query.planmode;
    if (!this.plan.title) {
      this.$router.push({
        path: "/payment/upgrade-plan"
      });
    }
    if (this.planmode=="annual") {
      this.subscription = "Annual Subscription"
    } else {
      this.subscription = "Monthly Subscription"
    }
    if (this.id==1 && this.subscription=="Monthly Subscription") {
        this.amount = 10
        this.plan.price = this.amount
        this.pricecomment = "Per month"
    }
    if (this.id==1 && this.subscription=="Annual Subscription") {
      this.amount = 120
      this.plan.price = this.amount
      this.pricecomment = "Annual"
    }
    if (this.id==2 && this.subscription=="Monthly Subscription") {
      this.amount = 25
      this.plan.price = this.amount
      this.pricecomment = "Per month"
    }
    if (this.id==2 && this.subscription=="Annual Subscription") {
      this.amount = 300
      this.plan.price = this.amount
      this.pricecomment = "Annual"
    }
    if (this.id==3 && this.subscription=="Monthly Subscription") {
      this.amount = 40
      this.plan.price = this.amount
      this.pricecomment = "Per month"
    }
    if (this.id==3 && this.subscription=="Annual Subscription") {
      this.amount = 480
      this.plan.price = this.amount
      this.pricecomment = "Annual"
    }
    const dt = new Date();
    const start = dt.getYear();
    for (let i = start; i < start + 10; i++) {
      this.years.push((i + 1900).toString());
    }
    
    this.states = [];
    this.state = "Select state";
    this.countries = [];
    country_region_list.forEach(country => {
      this.countries.push(country.countryName);
    });
  },
  methods: {
    changeStateEx(arg) {
      this.state = arg.value;
    },
    changeCountryEx(arg) {
      this.country = arg.value;
      this.states = [];
      this.state = "Select state";
      if(this.country == "Select country") return;
      const region = country_region_list.find(item => (item.countryName == this.country));
      region.regions.forEach(item => {
        this.states.push(item.name);
      });
    },
    showModal() {
      /*
      this.error_flag = true;
      if (this.isError(this.first_name)) return;
      if (this.isError(this.last_name)) return;
      if (this.isError(this.card_number)) return;
      if (this.isError(this.cvv)) return;
      if (this.isError(this.state)) return;
      if (this.isError(this.street_addr)) return;
      if (this.isError(this.zip_code)) return;
      this.$refs["done-modal"].show();
      */
      if (this.id==1 && this.subscription=="Monthly Subscription") {
        this.amount = 10
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==1 && this.subscription=="Annual Subscription") {
        this.amount = 120
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
      if (this.id==2 && this.subscription=="Monthly Subscription") {
        this.amount = 25
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==2 && this.subscription=="Annual Subscription") {
        this.amount = 300
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
      if (this.id==3 && this.subscription=="Monthly Subscription") {
        this.amount = 40
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==3 && this.subscription=="Annual Subscription") {
        this.amount = 480
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
      
      if (this.tabIndex==0) {
        console.log("I am in card");
        this.error_flag = true;
        this.showpaypal = false;
        if (this.card_number.length != 0 && this.month != 'Month' && this.year != 'Year' && this.cvv.length != 0 && this.billing_addr != 0 && this.city != 0 && this.state != 'Select state' && this.country != 'Select country' && this.zip_code.length != 0) {
        const formdata = new FormData();
        formdata.append('cardnumber', this.card_number);
        formdata.append('month', this.month);
        formdata.append('year', this.year);
        formdata.append('cvv', this.cvv);
        formdata.append('street_address', this.billing_addr);
        formdata.append('street_address_optional', this.billing_addr1);
        formdata.append('city', this.city);
        formdata.append('state', this.state);
        formdata.append('country', this.country);
        formdata.append('zip_code', this.zip_code);
        formdata.append('amount', this.amount);
        formdata.append('name_on_card', "test name");
        formdata.append('userid', "user1");
        this.$refs["process-modal"].show();
        this.$http.post(this.$api_url+'/cardpayment', formdata, { headers: { 'Authorization': null } })
                  .then((response) => {
                  //alert(JSON.stringify(response.data));
                  if (response.data['status']=='1') {
                      console.log(response.data);
                      this.$refs["done-modal"].show();
                      this.$refs["process-modal"].hide();
                      } else {
                        this.$refs["failed-modal"].show();
                        this.$refs["process-modal"].hide();
                        console.log('there was an error: ', response.data);
                      }
                  })
                  .catch((error) => {
                      // Error
                      if (error.response) {
                          // The request was made and the server responded with a status code
                          //alert("There is error. Please try again.")
                      } else if (error.request) {
                          // The request was made but no response was received
                          console.log(error.request)
                          //alert("No connection to server. Please check your internet connectivity.");
                      } else {
                          // Something happened in setting up the request that triggered an Error
                          console.log('Error', error.message);
                          //alert("unknown error. please try again.")
                      }
                      console.log(error.config)
              });
          }    
      }

      if (this.tabIndex==1) {
        console.log("I am in paypal");
        this.showpaypal = true;
      }
    },
    isError(value) {
      return (
        this.error_flag &&
        (value === undefined ||
          value === null || 
          (typeof value === "object" && Object.keys(value).length === 0) ||
          (typeof value === "string" && value.trim().length === 0) || (this.cvv.trim().length>4))
      );
    },
    upgradeMyPlan() {
      this.$router.push({
        path: "/landing",
        query: { id: this.id }
      });
    },
    updateprice() {
      if (this.id==1 && this.subscription=="Monthly Subscription") {
        this.amount = 10
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==1 && this.subscription=="Annual Subscription") {
        this.amount = 120
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
      if (this.id==2 && this.subscription=="Monthly Subscription") {
        this.amount = 25
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==2 && this.subscription=="Annual Subscription") {
        this.amount = 300
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
      if (this.id==3 && this.subscription=="Monthly Subscription") {
        this.amount = 40
        this.plan.price = this.amount
        this.pricecomment = "Per month"
      }
      if (this.id==3 && this.subscription=="Annual Subscription") {
        this.amount = 480
        this.plan.price = this.amount
        this.pricecomment = "Annual"
      }
    },
    payment_completed_cb(res){
      this.$refs["done-modal"].show();
      console.log(res);  
      console.log('the id is ', res.id);
      console.log('the amount is ', res.transactions[0]['amount']['total']);
      const formdata = new FormData();
      formdata.append('amount', res.transactions[0]['amount']['total']);
      formdata.append('transaction_id', res.id);
      formdata.append('userid', "user1");
      this.$http.post(this.$api_url+'/paypalpayment', formdata, { headers: { 'Authorization': null } })
                .then((response) => {
                //alert(JSON.stringify(response.data));
                if (response.data['status']=='1') {
                    console.log(response.data);
                    } else {
                      console.log('there was an error: ', response.data);
                    }
                })
                .catch((error) => {
                    // Error
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        //alert("There is error. Please try again.")
                    } else if (error.request) {
                        // The request was made but no response was received
                        console.log(error.request)
                        //alert("No connection to server. Please check your internet connectivity.");
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                        //alert("unknown error. please try again.")
                    }
                    console.log(error.config)
            });
    },
    payment_authorized_cb(res){
    console.log(res); 
    },
    payment_cancelled_cb(res){
        this.$refs["cancelled-modal"].show();
        console.log(res); 
    },
    show_promo_price() {
      var promo_value = document.getElementById("promo_code").value;
      if (promo_value=='123' && document.getElementById("promocode_used").value=='0') {
        this.amount = this.amount-( 10 * this.amount / 100 );
        this.plan.price = this.amount;
        document.getElementById("promocode_used").value = "1";
        setTimeout(() => { this.$refs["promocode-modal"].show(); }, 800); 
      } else {
        setTimeout(() => { this.$refs["fpromocode-modal"].show(); }, 800);
      }
    },
  }
};
</script>

<style lang="scss">
@import "./UpgradeToPlan.scss";
</style>

