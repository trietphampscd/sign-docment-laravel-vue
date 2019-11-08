import JwtService from "../../mixins/jwt.service"
import { post, put, remove, get } from '../../utils/http';

import { 
  FOLDER_GET_REQUEST,
  FOLDER_ADD_REQUEST,
  FOLDER_RENAME_REQUEST,
  FOLDER_DELETE_REQUEST
} from '../actions.type'

import { 
  FOLDER_GET_REQUEST_SUCCESS,
  FOLDER_GET_REQUEST_ERROR,
  FOLDER_ADD_REQUEST_SUCCESS,
  FOLDER_ADD_REQUEST_ERROR,
  FOLDER_RENAME_REQUEST_SUCCESS,
  FOLDER_RENAME_REQUEST_ERROR
} from '../mutations.type'

const state = {
  folders: [],
  folder_add: {
    name: 'abc',
    id: 1,
    children: []
  }
}

const getters = {
    get_folders(state) {
      return state.folders;
    },
    folder_add(state) {
      return state.folder_add;
    }
}

const actions = {
  [FOLDER_GET_REQUEST]: ({commit}) => {
    return new Promise((resolve, reject) => {
      get('doc-folders/all')
        .then(resp => {
          commit(FOLDER_GET_REQUEST_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          commit(FOLDER_GET_REQUEST_ERROR, err)
          reject(err)
        })
    })
  },
  [FOLDER_ADD_REQUEST]: ({commit}, data) => {
    return new Promise((resolve, reject) => {
      post('doc-folders/add', data)
        .then(resp => {
          // commit(FOLDER_ADD_REQUEST_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          // commit(FOLDER_ADD_REQUEST_ERROR, err)
          reject(err)
        })
    })
  },
  [FOLDER_RENAME_REQUEST]: ({commit}, data) => {
    return new Promise((resolve, reject) => {
      put(`doc-folders/${data.id}/update`, data)
        .then(resp => {
          // commit(FOLDER_ADD_REQUEST_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          // commit(FOLDER_ADD_REQUEST_ERROR, err)
          reject(err)
        })
    })
  },
  [FOLDER_DELETE_REQUEST]: ({commit}, id) => {
    return new Promise((resolve, reject) => {
      remove(`doc-folders/${id}/delete`)
        .then(resp => {
          // commit(FOLDER_ADD_REQUEST_SUCCESS, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          // commit(FOLDER_ADD_REQUEST_ERROR, err)
          reject(err)
        })
    })
  },
}

const mutations = {
    [FOLDER_GET_REQUEST_SUCCESS](state, payload) {
      state.folders = payload.list.map( val => {
        return {
          name: val.doc_folder_name,
          id: val.id,
          parent_id: val.parent_id
        }
      })
      // state.folders = payload.list;
    },
    [FOLDER_GET_REQUEST_ERROR](state, payload) {
      state.folders = [];
    },
    [FOLDER_ADD_REQUEST_SUCCESS](state, payload) {
      // return { ...state.folder_add, name: payload.list.doc_folder_name, id: payload.list.id, children: [] }
      // state.folder_add.name = payload.data.doc_folder_name
      // state.folder_add.id = payload.data.id
      // state.folder_add.children = []
    },
    [FOLDER_ADD_REQUEST_ERROR](state, payload) {
      state.folder_add = [];
    },
}
export default {
    state,
    getters,
    mutations,
    actions
}