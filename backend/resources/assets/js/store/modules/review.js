import JwtService from "../../mixins/jwt.service"
import { post, put, remove, get } from '../../utils/http';

import { 
  REVIEW_SEND_REQUEST,
} from '../actions.type'

import { 
  REVIEW_SEND_REQUEST_SUCCESS,
  REVIEW_SEND_REQUEST_ERROR
} from '../mutations.type'

const state = {
}

const getters = {
    
}

const actions = {
  [REVIEW_SEND_REQUEST]: ({commit}, data) => {
    return new Promise((resolve, reject) => {
      post('recipients/sent-email-recipient', data)
        .then(resp => {
          commit(REVIEW_SEND_REQUEST_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          commit(REVIEW_SEND_REQUEST_ERROR, err)
          reject(err)
        })
    })
  },
}

const mutations = {
    [REVIEW_SEND_REQUEST_SUCCESS](state, payload) {

    },
    [REVIEW_SEND_REQUEST_ERROR](state, payload) {

    },
}
export default {
    state,
    getters,
    mutations,
    actions
}