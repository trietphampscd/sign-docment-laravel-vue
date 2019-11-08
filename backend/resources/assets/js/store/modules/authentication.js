import JwtService from "../../mixins/jwt.service"

import { AUTH_REQUEST, AUTH_SUCCESS, AUTH_ERROR, AUTH_MODAL_BTN, AUTH_SET_FB } from '../actions.type'
import { SET_AUTH, PURGE_AUTH, SET_ERROR, SET_MODAL_BTN, SET_FB } from '../mutations.type'

const state = {
    errors: [],
    button: null,
    user: {},
    authorization: null,
    token: JwtService.getToken(),
    FB: null
}

const getters = {
    isAuthenticated: state => !!state.token,
    currentUser: state => state.user,
    authorization: state => state.authorization,
    authToken: state => state.token,
    FB: state => state.FB,
    errors: state => state.errors,
    button: state => state.button
}

const actions = {
    [AUTH_SUCCESS](context, token) {
        JwtService.saveToken(token.access_token)
        context.commit(SET_AUTH, token)
    },
    [AUTH_ERROR](context, errors) {
        JwtService.destroyToken()
        context.commit(SET_ERROR, errors)
    },
    [AUTH_MODAL_BTN](context, button) {
        context.commit(SET_MODAL_BTN, button)
    },
    [AUTH_SET_FB](context, fb) {
        context.commit(SET_FB, fb)
    }
}

const mutations = {
    [SET_AUTH](state, token) {
        state.isAuthenticated = true
        state.authorization = token
        state.errors = []
    },
    [SET_ERROR](state, errors) {
        state.isAuthenticated = true
        // state.authorization = null
        state.errors = errors
    },
    [SET_MODAL_BTN](state, button) {
        state.button = button
    },
    [PURGE_AUTH](state) {
        state.isAuthenticated = false
        state.user = {} //
        state.errors = []
    },
    [SET_FB](state, fb) {
        state.FB = fb
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}