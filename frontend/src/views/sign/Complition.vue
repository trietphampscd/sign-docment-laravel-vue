<template>
  <div class="app flex-row">
    <div class="w-100">
      <div class="content-card completion">
        <img src="img/icons/complition.svg" />
        <div class="title">
          All signatures are complete.
        </div>
        <div class="content">
          An audit trail certificate with completed documents and signature progress history has been emailed. Please check the attached file or all of the emails in the signature.
        </div>
        <b-button variant="primary" class="min-width-136px m-2">Login</b-button>
        <b-button variant="outline-primary" class=" m-2 min-width-136px">No Thanks</b-button>
      </div>
    </div>
  </div>
</template>

<script>
import UserIcon from "../../components/UserIcon";
import UserSelect from "../../components/UserSelect";
import pdf from "vue-pdf";
import draggable from "vuedraggable";
export default {
  name: "Complition",
  components: {
  },
  data() {
    return { };
  },
  mounted() {
  },
  methods: {
  }
};
</script>

<style lang="scss">
@import "./Complition.scss";
</style>

