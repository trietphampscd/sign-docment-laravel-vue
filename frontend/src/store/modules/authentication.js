import JwtService from '../../mixins/jwt.service'
import { authentication as API } from '../../mixins/authentication'

import { 
  AUTH_REQUEST,
  AUTH_SUCCESS, 
  AUTH_DESTROY, 
  AUTH_ERROR, 
  AUTH_MODAL_BTN, 
  AUTH_SET_FB,
  AUTH_LOADING
} from '../actions.type'
import { 
  SET_AUTH, 
  SET_USER, 
  PURGE_AUTH, 
  SET_ERROR, 
  SET_MODAL_BTN, 
  SET_FB,
  SET_LOADING
} from '../mutations.type'

const state = {
  user: {},
  token: JwtService.getToken(),
  fb: null,
  errors: [],
  button: null,
  loading: false
}

const getters = {
  AUTHENTICATED: state => !!state.token,
  USER: state => state.user,
  TOKEN: state => state.token,
  FB: state => state.FB,
  errors: state => state.errors,
  button: state => state.button,
  loading: state => state.loading
}

const actions = {
  [AUTH_REQUEST]: ({ commit, dispatch }, token) => {
    dispatch(AUTH_LOADING, true)

    JwtService.saveToken(token.access_token)
    commit(SET_AUTH, token.access_token)

    return new Promise((resolve, reject) => {
        API.methods.user()
        .then(response => {
          dispatch(AUTH_SUCCESS, response.data.user)
          
          dispatch(AUTH_LOADING, false)
          resolve(response)
        })
        .catch(error => {
          console.log(error.response)
          JwtService.destroyToken()
          dispatch(AUTH_ERROR, error)
          reject(error)
        })
    })
  },
  [AUTH_SUCCESS](context, user) {
    context.commit(SET_USER, user)
  },
  [AUTH_DESTROY](context) {
    JwtService.destroyToken()
    context.commit(PURGE_AUTH)
  },
  [AUTH_ERROR](context, errors) {
    context.commit(SET_ERROR, errors)
  },
  [AUTH_MODAL_BTN](context, button) {
    context.commit(SET_MODAL_BTN, button)
  },
  [AUTH_SET_FB](context, fb) {
    context.commit(SET_FB, fb)
  },
  [AUTH_LOADING](context, loading) {
    context.commit(SET_LOADING, loading)
  }
}

const mutations = {
  [SET_AUTH](state, token) {
    state.token = token
    state.errors = []
  },
  [SET_USER](state, user) {
    state.user = user
  },
  [SET_ERROR](state, errors) {
    state.errors = errors
    state.loading = false
  },
  [SET_MODAL_BTN](state, button) {
    state.button = button
    state.loading = false
  },
  [PURGE_AUTH](state) {
    state.user = {}
    state.token = null
    state.fb = null
    state.errors = []
    state.button = null
    state.loading = false
  },
  [SET_FB](state, fb) {
    state.FB = fb
    state.loading = false
  },
  [SET_LOADING](state, loading) {
    state.loading = loading
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}