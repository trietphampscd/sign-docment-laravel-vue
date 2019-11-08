import JwtService from "../../mixins/jwt.service"
import { post, put, remove, get } from '../../utils/http';

import { 
  CHANGE_IMAGE_REQUEST,
  GET_USER_INFOR_REQUEST
} from '../actions.type'

import { 
  CHANGE_IMAGE_SUCCESS,
  CHANGE_IMAGE_ERROR,
  GET_USER_INFOR_SUCCESS,
  GET_USER_INFOR_ERROR
} from '../mutations.type'

const state = {
  userProfile: [],
  urlAvatar: null
}

const getters = {
    getUser(state){
      return state.userProfile;
    },
    getAvatarUpload(state){
      return state.urlAvatar;
    }
}

const actions = {
  [CHANGE_IMAGE_REQUEST]: ({commit}, data) => {
    return new Promise((resolve, reject) => {
      put('clients/update-avarta', data)
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
}

const mutations = {
    [CHANGE_IMAGE_SUCCESS](state, payload) {
        state.urlAvatar = payload.user.avatar;
    },
    [CHANGE_IMAGE_ERROR](state, payload) {
      state.urlAvatar = null;
    },
    [GET_USER_INFOR_SUCCESS](state, payload) {
      state.userProfile = payload.user;
    },
    [GET_USER_INFOR_ERROR](state, payload) {
       return state.userProfile;
    },
}
export default {
    state,
    getters,
    mutations,
    actions
}