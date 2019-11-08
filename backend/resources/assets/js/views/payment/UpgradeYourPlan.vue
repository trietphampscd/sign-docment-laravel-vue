<template>
  <div class="app flex-row">
    <div class="w-100 position-relative">
      <div class="plan-header">
        <h1>Upgrade {{plans[current_plan].title}} Plan</h1>
        <div class="switch-button" v-on:click="clickSwitch()">
          <div class="switch-item" v-bind:class="{'selected': switch_annual}">Annual</div>
          <div class="switch-item" v-bind:class="{'selected': !switch_annual}">Monthly</div>
        </div>
      </div>
      <hr class="mb-4" />
      <div class="row plans px-2">
        <div
          class="col-12 col-sm-6 col-md-6 col-lg-3 px-1"
          v-for="(plan, index) in plans"
          :key="index"
        >
          <div class="plan-card content-card">
            <div class="plan-card-head">
              <div class="plan-title">{{plan.title}}</div>
              <!-- <UserIcon :icon="plan.title + '.png'" :height="26" :width="26" /> -->
              <img v-bind:src="'img/icons/'+plan.title+'.png'" width="32" height="32" />
            </div>
            <div class="plan-card-head mt-4">
              <div class="comments">
                <div v-if="index==0">Per month</div>
                <div v-if="index==1">Per user</div>
                <div v-if="index>1">per user, per month</div>
                <div v-if="(switch_annual && (plan.price>0))">${{plan.price*12}} annually</div>
              </div>
              <span class="price">
                ${{switch_annual?plan.price:plan.monthly}}
                <sup>.00</sup>
              </span>
            </div>
            <hr />
            <div class="text-center">
              <div class="limit-for-sending">Limit for Sending for Signature</div>
              <div class="limit-times">{{plan.limit}}</div>
            </div>
            <hr />
            <b-button
              block
              :variant="current_plan==index?'secondary':'other'"
              :disabled="current_plan==index"
              v-on:click="chooseThisPlan(plan, index)"
            >{{current_plan == index?"Current plan":"Choose this plan"}}</b-button>
          </div>
        </div>
      </div>
      <div class="plan-cancel">
        <i class="fa fa-close"></i>
        <b-button variant="link">Cancel plan</b-button>
      </div>
    </div>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";

export default {
  name: "UpgradeYourPlan",
  components: {
    UserIcon
  },
  data() {
    return {
      current_plan: 0,
      switch_annual: true,
      plans: [
        {
          title: "Free",
          price: 0,
          monthly: 0,
          limit: "5 Times total"
        },
        {
          title: "Personnel",
          price: 10,
          monthly: 5,
          limit: "5 Times total"
        },
        {
          title: "Standard",
          price: 25,
          monthly: 10,
          limit: "30 Times total"
        },
        {
          title: "Pro",
          price: 40,
          monthly: 20,
          limit: "No Limit ∞"
        }
      ]
    };
  },
  created() {
    const id = this.$route.query.id;
    if (id) {
      this.current_plan = id;
    } else {
      this.current_plan = 0;
    }
  },
  methods: {
    clickSwitch() {
      this.switch_annual = !this.switch_annual;
    },
    chooseThisPlan(toPlan, id) {
      this.$router.push({
        path: "/payment/upgrade-to-plan",
        query: { plan: toPlan, id: id }
      });
    }
  }
};
</script>

<style lang="scss">
@import "./UpgradeYourPlan.scss";
</style>

