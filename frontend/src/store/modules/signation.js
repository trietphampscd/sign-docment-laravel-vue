import JwtService from '../../mixins/jwt.service'
import { signation } from '../../mixins/signation'

import {
  SIGNATURE_CREATE,
  SIGNATURE_UPLOAD,
  SIGNATURE_GET,
  SIGNATURE_UPDATE,
  SIGNATURE_SOFTDELETE,
  SIGNATURE_DESTROY
} from '../actions.type'

import {
  SET_SIGNATURE,
  UPDATE_SIGNATURE,
  SOFTDELETE_SIGNATURE,
  DESTROY_SIGNATURE
} from '../mutations.type'

const state = {
  signatures: {}
}

const getters = {
  SIGNATURES: state => state.signatures
}

const actions = {
  [SIGNATURE_CREATE]: (context, signature) => {
    context.commit(UPDATE_SIGNATURE, signature)
  },

  [SIGNATURE_UPLOAD]: (context, signature) => {
    context.commit(UPDATE_SIGNATURE, signature)
  },

  [SIGNATURE_GET]: (context, signatures) => {
    context.commit(SET_SIGNATURE, signatures)
  },
  
  [SIGNATURE_UPDATE]: (context, signature) => {    
    context.commit(SET_SIGNATURE, signature)
  },

  [SIGNATURE_SOFTDELETE]: (context, id) => {
    context.commit(SOFTDELETE_SIGNATURE, id)
  }, 

  [SIGNATURE_DESTROY]: (context, id) => {
    context.commit(DESTROY_SIGNATURE, id)
  }
}

const mutations = {
  [SET_SIGNATURE](state, payload) {
    state.signatures = payload
  },

  [UPDATE_SIGNATURE](state, payload) {
    state.signatures.push(payload)
  },

  /** NOT WORKING YET (SOFIAN) */
  [SOFTDELETE_SIGNATURE](state, id) {
    let index = state.signatures.findIndex(signature => signature.id == id)
    state.signatures.splice(index, 1)
  },

  /** NOT WORKING YET (SOFIAN) */
  [DESTROY_SIGNATURE](state, id) {
    // state.stamps = id
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}