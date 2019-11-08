import Vue from "vue";
import Router from "vue-router";

// Views - Documents
const DocumentsContainer = () => import("@/containers/DocumentsContainer");
const AddDocuments = () => import("@/views/document/AddDocuments");
const AddRecipients = () => import("@/views/document/AddRecipients");
const Prepare = () => import("@/views/document/Prepare");
const Review = () => import("@/views/document/Review");

// Container and Views of Pricing and Payment
const PaymentContainer = () => import("@/containers/PaymentContainer");
const NormalSign = () => import("@/views/payment/NormalSigin");
const DocumentList = () => import("@/views/payment/DocumentList");
const TemplateList = () => import("@/views/payment/TemplateList");
const UpgradeYourPlan = () => import("@/views/payment/UpgradeYourPlan");
const PricingPlan = () => import("@/views/payment/PricingPlan");
const UpgradeToPlan = () => import("@/views/payment/UpgradeToPlan");
const Account = () => import("@/views/profile/Account");
const CustomBranding = () => import("@/views/profile/CustomBranding");
const Signature = () => import("@/views/profile/Signature");

// Signing
const SignContainer = () => import("@/containers/SignContainer");
const SignCheck = () => import("@/views/sign/SignCheck");
const Signing = () => import("@/views/sign/Signing");
const Complition = () => import("@/views/sign/Complition");
Vue.use(Router);

export default new Router({
  mode: "hash", // https://router.vuejs.org/api/#mode
  linkActiveClass: "open active",
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: "/docu-sign",
      redirect: "/docu-sign/add-document",
      name: "Documents",
      component: DocumentsContainer,
      children: [
        {
          path: "add-document",
          name: "AddDocument",
          component: AddDocuments
        },
        {
          path: "add-recipients",
          name: "AddRecipients",
          component: AddRecipients
        },
        {
          path: "prepare",
          name: "Prepare",
          component: Prepare
        },
        {
          path: "review",
          name: "Review",
          component: Review
        }
      ]
    },
    {
      path: "/profile",
      redirect: "/profile/account",
      name: "Profile",
      component: PaymentContainer,
      children: [
        {
          path: "account",
          name: "Account",
          component: Account
        },
        {
          path: "custom-branding",
          name: "CustomBranding",
          component: CustomBranding
        }
      ]
    },
    {
      path: "/signature",
      redirect: "/signature/signature-stamp",
      name: "Signature",
      component: PaymentContainer,
      children: [
        {
          path: "signature-stamp",
          name: "SignatureStamp",
          component: Signature
        }
      ]
    },
    {
      path: "/sign",
      redirect: "/sign/check",
      name: "Sign",
      component: SignContainer,
      children: [
        {
          path: "check",
          name: "SignCheck",
          component: SignCheck
        },
        {
          path: "signing",
          name: "Signing",
          component: Signing
        },
        {
          path: "complition",
          name: "Complition",
          component: Complition
        },
      ]
    },
    {
      path: "/",
      redirect: "/landing",
      name: "LandingPage",
      component: PaymentContainer,
      children: [
        {
          path: "/landing",
          name: "NormalSign",
          component: NormalSign
        }
      ]
    },
    {
      path: "/payment",
      redirect: "/payment/document-list",
      name: "Payment",
      component: PaymentContainer,
      children: [
        {
          path: "document-list",
          name: "DocumentList",
          component: DocumentList
        },
        {
          path: "template-list",
          name: "TemplateList",
          component: TemplateList
        },
        {
          path: "upgrade-plan",
          name: "UpgradeYourPlan",
          component: UpgradeYourPlan
        },
        {
          path: "pricing-plan",
          name: "PricingPlan",
          component: PricingPlan
        },
        {
          path: "upgrade-to-plan",
          name: "UpgradeToPlan",
          component: UpgradeToPlan
        }
      ]
    }
  ]
});
