<template>
  <div class="app flex-row">
    <div class="w-100">
      <h1>Pricing Plan</h1>
      <hr class="mb-4" />
      <div class="personal-plan content-card">
        <div>
          <div class="payment-header">Personal Monthly Plan</div>
          <div class="d-flex align-items-center">
            <UserIcon icon="mastercard.svg" />
            <span class="mastercard ml-2">MasterCard ****5432</span>
            <b-button variant="link" class="ml-2" v-on:click="editPaymentMethod()">Edit Card</b-button>
          </div>
          <div class="comments">Next Payment: $0.87 on June 1, 2019</div>
        </div>
        <div>
          <div>
            <span class="comments mr-2">Available:</span>
            <strong>2/3</strong>
            <span class="comments ml-2">Signitures</span>
          </div>
          <b-progress :value="30" :max="100" class="mb-3"></b-progress>
          <b-button variant="other" style="width: 186px" v-on:click="upgradePlan()">Upgrade Plan</b-button>
        </div>
      </div>
      <h1 class="mt-5">Payment History</h1>
      <hr class="mb-4" />
      <div class="payment-history">
        <div class="history-header comments">
          <div class="other-fields">Date</div>
          <div class="other-fields">TYPE</div>
          <div class="other-fields">TRANSACTION</div>
          <div class="other-fields">AMOUNT</div>
          <div class="receipt">RECEIPT</div>
        </div>
        <div class="content-card history-item">
          <div class="other-fields">May 1, 2019</div>
          <div class="other-fields">Invoice</div>
          <div class="other-fields">INV19857462</div>
          <div class="other-fields">$0.87</div>
          <div class="receipt">
            <b-button variant="link">View PDF</b-button>
          </div>
        </div>
        <div class="content-card history-item">
          <div class="other-fields">May 1, 2019</div>
          <div class="other-fields">Invoice</div>
          <div class="other-fields">INV19857462</div>
          <div class="other-fields">$0.87</div>
          <div class="receipt">
            <b-button variant="link">View PDF</b-button>
          </div>
        </div>
        <div class="content-card history-item">
          <div class="other-fields">May 1, 2019</div>
          <div class="other-fields">Invoice</div>
          <div class="other-fields">INV19857462</div>
          <div class="other-fields">$0.87</div>
          <div class="receipt">
            <b-button variant="link">View PDF</b-button>
          </div>
        </div>
      </div>
      <div class="plan-cancel">
        <b-button variant="link">Show more invoices</b-button>
      </div>
    </div>
    <b-modal id="modal-1" ref="edit-payment-method-modal" hide-footer size="lg">
      <div class="payment-modal">
        <h1>Change payment method</h1>
        <div class="w-100 mt-3">
          <div class="form-group">
            <label for="name">Credit/debit card number</label>
            <b-input-group class="pl-3" v-bind:class="{'input-error': isError(card_number)}">
              <b-form-input placeholder="**** **** **** 5432"
                v-model="card_number"
                 autocomplete="current-password" ></b-form-input>
              <b-input-group-prepend class="mr-0">
                <b-input-group-text class="h-auto">
                  <UserIcon icon="mastercard.svg" />
                </b-input-group-text>
              </b-input-group-prepend>
            </b-input-group>
            <div
              v-if="isError(card_number)"
              class="error-text"
            >Please enter a valid credit/debit card number</div>
          </div>
          <div class="row">
            <div class="col-4 pr-0">
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
                <div v-if="month == 'Month' && error_flag" class="error-text">Please select a month</div>
              </div>
            </div>
            <div class="col-4 px-0">
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
                <div v-if="year == 'Year' && error_flag" class="error-text">Please select a year</div>
              </div>              
            </div>
            <div class="col-4">
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
                <div v-if="isError(cvv)" class="error-text">Please enter a valid CVV number</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label for="name">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="first_name"
                  placeholder="First Name"
                  name="first_name"
                  v-bind:class="{'input-error': isError(first_name)}"
                  v-model="first_name"
                />
                <div
                  v-if="isError(first_name)"
                  class="error-text"
                >Please enter the first name</div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label for="name">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="last_name"
                  placeholder="Last Name"
                  name="last_name"
                  v-bind:class="{'input-error': isError(last_name)}"
                  v-model="last_name"
                />
                <div v-if="isError(last_name)" class="error-text">Please enter the last name</div>
              </div>
            </div>
          </div>
          <div class="form-group">
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
          <div class="form-group">
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
          <div class="form-group">
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
          <div class="row">
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
          <div class="d-flex justify-content-end align-items-center mb-2 mb-md-5">
            <i class="fa fa-close"></i>
            <button class="btn btn-link mr-3" v-on:click="hideModal()">Revert</button>
            <button class="btn btn-primary min-width-124px" v-on:click="saveData()">Save</button>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import country_region_list from "country-region-data/data";
import Vue from "vue";
import { DropDownListPlugin  } from '@syncfusion/ej2-vue-dropdowns';
Vue.use(DropDownListPlugin);
export default {
  name: "PricingPlan",
  components: {
    UserSelect,
    UserIcon
  },
  data() {
    return {
      payment_method: "Credit Card",
      country: "Select country",
      country1: '',
      countries: [],
      countries1: [],

      swtich_annual: true,
      plans: [{}],
      
      years: [],      
      month: "Month",
      year: "Year",
      error_flag: false,
      card_number: "",
      first_name: "",
      last_name: "",
      exp_month: "",
      exp_year: "",
      state: "Select state",
      states: [],
      cvv: "",
      city: "",
      billing_addr: "",
      billing_addr1: "",
      zip_code: ""
    };
  },
  created() {
    const dt = new Date();
    const start = dt.getYear();
    for (let i = start; i < start + 10; i++) {
      this.years.push((i + 1900).toString());
    }
  },
  mounted() {
    this.countries = [];    
    this.states = [];
    this.state = "Select state";
    this.countries1 = [];
    country_region_list.forEach(country => {
      this.countries.push(country.countryName);
      this.countries1.push({name: country.countryName});
    });
    this.$root.$emit('toggleSidebar');
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
    isError(value) {
      return (
        this.error_flag &&
        (value === undefined ||
          value === null ||
          (typeof value === "object" && Object.keys(value).length === 0) ||
          (typeof value === "string" && value.trim().length === 0))
      );
    },
    hideModal() {

      this.$refs["edit-payment-method-modal"].hide();
    },
    saveData() {
      this.error_flag = true;
      if (this.isError(this.first_name)) return;
      if (this.isError(this.last_name)) return;
      if (this.isError(this.exp_year)) return;
      if (this.isError(this.exp_month)) return;
      if (this.isError(this.billing_address)) return;
      if (this.isError(this.zip_code)) return;
      this.$refs["edit-payment-method-modal"].hide();
    },
    clickSwitch() {
      this.swtich_annual = !this.swtich_annual;
    },
    editPaymentMethod() {
      this.$refs["edit-payment-method-modal"].show();
    },
    upgradePlan() {
      this.$router.push({ path: "/payment/upgrade-plan" });
    }
  }
};
</script>

<style lang="scss">
@import "./PricingPlan.scss";
</style>

