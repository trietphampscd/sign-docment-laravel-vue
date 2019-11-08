import Vue from 'vue'
import Vuex from 'vuex'

import language from './modules/language'
import authentication from './modules/authentication'
import firstDocuSign from './modules/firstDocuSign'
import document from './modules/document'
import review from './modules/review'
import userProfile from './modules/userProfile'
import folder from './modules/folder'

Vue.use (Vuex)

export default new Vuex.Store({
    modules: {
        language,
        authentication,
        firstDocuSign,
        document,
        review,
        userProfile,
        folder
    }
})