import JwtService from '../../mixins/jwt.service'
import { post, put, remove, get } from '../../utils/http';

import {
  CHANGE_IMAGE_REQUEST,
  GET_USER_INFOR_REQUEST,
  USER_UPDATE_PASSAUTH_REQUEST,
  USER_UPDATE_REQUEST
} from '../actions.type'

import { 
  SET_ERROR,
  CHANGE_IMAGE_SUCCESS,
  CHANGE_IMAGE_ERROR,
  GET_USER_INFOR_SUCCESS,
  GET_USER_INFOR_ERROR,
  UPDATE_PASSAUTH_SUCCESS,
  UPDATE_PROFILE_SUCCESS
} from '../mutations.type'

const state = {
  userAccount: {},
  urlAvatar: null
}

const getters = {
  getUser : state => {
    return state.userAccount;
  },
  getAvatarUpload : state => {
    return state.urlAvatar;
  }
}

const actions = {
  [CHANGE_IMAGE_REQUEST]: ({commit}, data) => {
    return new Promise((resolve, reject) => {
      put('clients/update-avatar', data)
        .then(resp => {
          commit(CHANGE_IMAGE_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          commit(CHANGE_IMAGE_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_USER_INFOR_REQUEST]: ({commit}) => {
    return new Promise((resolve, reject) => {
      get('auth/user')
        .then(resp => {
          commit(GET_USER_INFOR_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          commit(GET_USER_INFOR_ERROR, err)
          reject(err)
        })
    })
  },
  [USER_UPDATE_PASSAUTH_REQUEST]: (context, credentials) => {
    return new Promise((resolve, reject) => {
      put('clients/update-password', credentials)
        .then(response => {
          context.dispatch(GET_USER_INFOR_REQUEST)
          context.commit(UPDATE_PASSAUTH_SUCCESS, response.data)
          resolve(response)
        })
        .catch(error => {
          context.commit(SET_ERROR, error)
          reject(error)
        })
    })
  },
  [USER_UPDATE_REQUEST]: (context, credentials) => {
    return new Promise((resolve, reject) => {
      put('clients/update-client', credentials)
        .then(response => {
          context.dispatch(GET_USER_INFOR_REQUEST)
          context.commit(UPDATE_PROFILE_SUCCESS, response.data)
          resolve(response)
        })
        .catch(error => {
          context.commit(SET_ERROR, error)
          reject(error)
        })
    })
  }
}

const mutations = {
    [UPDATE_PROFILE_SUCCESS](state, payload) {
      state.userAccount = payload.user
    },  
    [CHANGE_IMAGE_SUCCESS](state, payload) {
        state.urlAvatar = payload.user.avatar;
    },
    [CHANGE_IMAGE_ERROR](state, payload) {
      state.urlAvatar = null;
    },
    [GET_USER_INFOR_SUCCESS](state, payload) {
      state.userAccount = payload.user;
    },
    [GET_USER_INFOR_ERROR](state, payload) {
       return state.userAccount;
    },
    [UPDATE_PASSAUTH_SUCCESS](state, payload) {
      // Dont know what to do
    }
    
}
export default {
    state,
    getters,
    mutations,
    actions
}