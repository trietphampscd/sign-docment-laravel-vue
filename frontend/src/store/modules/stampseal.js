import JwtService from '../../mixins/jwt.service'
import { stampseal } from '../../mixins/stampseal'

import {
  STAMP_CREATE,
  STAMP_UPLOAD,
  STAMP_GET,
  STAMP_UPDATE,
  STAMP_SOFTDELETE,
  STAMP_DESTROY
} from '../actions.type'

import {
  SET_STAMP,
  UPDATE_STAMP,
  SOFTDELETE_STAMP,
  DESTROY_STAMP
} from '../mutations.type'

const state = {
  stamps: []
}

const getters = {
  STAMPS: state => state.stamps
}

const actions = {
  [STAMP_CREATE]: (context, stamp) => {
    context.commit(UPDATE_STAMP, stamp)
  },
  
  [STAMP_UPLOAD]: (context, stamp) => {
    context.commit(UPDATE_STAMP, stamp)
  },

  [STAMP_GET]: (context, stamps) => {
    context.commit(SET_STAMP, stamps)
  },

  [STAMP_UPDATE]: (context, stamp) => {    
    context.commit(SET_STAMP, stamp)
  },

  [STAMP_SOFTDELETE]: (context, id) => {
    context.commit(SOFTDELETE_STAMP, id)
  }, 

  [STAMP_DESTROY]: (context, id) => {
    context.commit(DESTROY_STAMP, id)
  }
}

const mutations = {
  [SET_STAMP](state, payload) {
    state.stamps = payload
  },
  [UPDATE_STAMP](state, payload) {
    state.stamps.push(payload)
  },

  [SOFTDELETE_STAMP](state, id) {
    let index = state.stamps.findIndex(stamp => stamp.id == id)
    state.stamps.splice(index, 1)
  },

  /** NOT WORKING YET (SOFIAN) */
  [DESTROY_STAMP](state, id) {
    // state.stamps = id
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}