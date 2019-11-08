import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/Home'
import Signup from '../views/authentication/Signup'
import SignupConfirm from '../views/authentication/SignupConfirm'
import Login from '../views/authentication/Login'
import ForgotPassword from '../views/authentication/ForgotPassword'
import ResetPassword from '../views/authentication/ResetPassword'
import UploadDocument from '../views/documents/Upload'


// Views - Documents
const DocumentsContainer = () => import("../containers/DocumentsContainer");
const AddDocuments = () => import("../views/document/AddDocuments");
const AddRecipients = () => import("../views/document/AddRecipients");
const Prepare = () => import("../views/document/Prepare");
const Review = () => import("../views/document/Review");

// Container and Views of Pricing and Payment
const PaymentContainer = () => import("../containers/PaymentContainer");
const NormalSign = () => import("../views/payment/NormalSigin");
/////////////////////////////
const TestCrop = () => import("../views/payment/NormalSigin_cp");
/////////////////////////////
const DocumentList = () => import("../views/payment/DocumentList");
const UpgradeYourPlan = () => import("../views/payment/UpgradeYourPlan");
const PricingPlan = () => import("../views/payment/PricingPlan");
const UpgradeToPlan = () => import("../views/payment/UpgradeToPlan");
const TemplateList = () => import("../views/payment/TemplateList");
const Account = () => import("../views/profile/Account");
const CustomBranding = () => import("../views/profile/CustomBranding");
const Signature = () => import("../views/profile/Signature");

Vue.use(Router)

export default new Router({
    // mode: 'history',
    history: true,
    hashbang: false,
    routes: [
        // {
        //     path: '/crop-test',
        //     name: 'TestCrop',
        //     component: TestCrop
        // },
        {
            path: '/signup',
            name: 'Signup',
            component: Signup
        },
        {
            path: '/signup/:email',
            name: 'SignupC',
            component: Signup
        },
        {
            path: '/signup/confirm/:token',
            name: 'Confirm',
            component: SignupConfirm
        },
        {
            path: '/login',
            name: 'Login',
            component: Login
        },
        {
            path: '/login/:email',
            name: 'LoginC',
            component: Login
        },
        {
            path: '/password/forgot',
            name: 'ForgotPassword',
            component: ForgotPassword
        },
        {
            path: '/password/reset/:token',
            name: 'ResetPassword',
            component: ResetPassword
        },
        {
            path: '/documents/add',
            name: 'UploadDocument',
            component: UploadDocument
        },
        {
            path: '/',
            name: 'Home',
            component: Home
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
})
