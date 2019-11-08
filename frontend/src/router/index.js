import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/store'

/* Authentication */
const Signup = () => import("@/views/authentication/Signup")
const SignupConfirm = () => import("@/views/authentication/SignupConfirm")
const Login = () => import("@/views/authentication/Login")
const ForgotPassword = () => import("@/views/authentication/ForgotPassword")
const ResetPassword = () => import("@/views/authentication/ResetPassword")

// const Home = () => import("@/views/Home")

// Views - Documents
const UploadDocument = () => import("@/views/documents/Upload")
const DocumentsContainer = () => import("../containers/DocumentsContainer");
const AddDocuments = () => import("../views/document/AddDocuments");
const AddRecipients = () => import("../views/document/AddRecipients");
const Prepare = () => import("../views/document/Prepare");
const Review = () => import("../views/document/Review");

// Sign
const SignContainer = () => import("../containers/SignContainer");
const SignCheck = () => import("../views/sign/SignCheck");
const TestSignCheck = () => import("../views/sign/TestSignCheck");
const Signing = () => import("../views/sign/Signing");
const Complition = () => import("../views/sign/Complition");

// Container and Views of Pricing and Payment
const PaymentContainer = () => import("../containers/PaymentContainer");
const NormalSign = () => import("../views/payment/NormalSigin");

/** Import Signature & Stamp */
const SignatureStamp = () => import("../views/signaturestamp/SignatureStamp")
const SignInitialStamps = () => import("../views/signaturestamp/SignInitialStamps")

const DocumentList = () => import("../views/payment/DocumentList");
const UpgradeYourPlan = () => import("../views/payment/UpgradeYourPlan");
const PricingPlan = () => import("../views/payment/PricingPlan");
const UpgradeToPlan = () => import("../views/payment/UpgradeToPlan");
const TemplateList = () => import("../views/payment/TemplateList");
const Account = () => import("../views/profile/Account");
const CustomBranding = () => import("../views/profile/CustomBranding");
const Signature = () => import("../views/profile/Signature");

/////////////////////////////////////////////////////////////////
const TestCrop = () => import("../views/payment/NormalSigin_cp");
/////////////////////////////////////////////////////////////////

Vue.use(Router)

const router = new Router({
  /** Original */
  // // mode: 'history',
  // history: true,
  // hashbang: false,
  
  /** Sofian */
  /** "hash" | "history" | "abstract" */ 
  mode: "hash",  //https://router.vuejs.org/api/#mode
  linkActiveClass: "open active",
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    /* Authentication Routes */
    {
      path: '/signup',
      name: 'Signup',
      component: Signup,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/signup/:email',
      name: 'SignupC',
      component: Signup,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/signup/errors/:error',
      name: 'SignupE',
      component: Signup,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/signup/confirm/:token',
      name: 'Confirm',
      component: SignupConfirm,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/login/:email',
      name: 'LoginC',
      component: Login,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/login/errors/:error',
      name: 'LoginE',
      component: Login,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/password/forgot',
      name: 'ForgotPassword',
      component: ForgotPassword,
      meta: {
        requiresVisitor: true
      }
    },
    {
      path: '/password/reset/:token',
      name: 'ResetPassword',
      component: ResetPassword,
      meta: {
        requiresVisitor: true
      }
    },

    /* Module Routes */
    // {
    //   path: '/',
    //   name: 'Home',
    //   component: Home,
    //   meta: {
    //     requiresAuth: true
    //   }
    // },
    {
      path: '/documents/add',
      name: 'UploadDocument',
      component: UploadDocument,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/docu-sign",
      redirect: "/docu-sign/add-document",
      name: "Documents",
      component: DocumentsContainer,
      children: [
        {
          path: "add-document",
          name: "AddDocument",
          component: AddDocuments,
          props: route => route
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
      ],
      meta: {
        requiresAuth: true
      }
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
          component: SignCheck,
          props: route => route
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
        {
          path: "prepare",
          name: "TestSignCheck",
          component: TestSignCheck
        },
      ],
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
      ],
      meta: {
        requiresAuth: true
      }
    },
    {
      path: "/signature",
      redirect: "/signature/signature-stamp",
      name: "Signature",
      component: PaymentContainer,
      children: [
        {
          path: "signature-stamp",
          name: "SignInitialStamps",
          component: SignInitialStamps
        }
      ],
      meta: {
        requiresAuth: true
      }
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
      ],
      meta: {
        requiresAuth: true
      }
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
      ],
      meta: {
        requiresAuth: true
      }
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!(store.getters.AUTHENTICATED)) {
      next({
        name: 'Login',
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.requiresVisitor)) {
    if (store.getters.AUTHENTICATED) {
      next({
        name: 'LandingPage',
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router;