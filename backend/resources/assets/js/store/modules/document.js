/* eslint-disable promise/param-names */
import { post, put, remove, get, getOutSide } from '../../utils/http';
import { addParamsToBlob } from "../../helpers";

const ADD_DOCUMENT_URL = 'document';
const GET_DOCUMENT_URL = 'document';
const REMOVE_DOCUMENT_DETAIL_URL = 'document/del_detail';
const GET_DOCUMENT_BY_DOC_ID_URL = 'document/get-by/document-id';
const GET_RECIPIENTS_URL = 'recipients/by-document-id';
const ADD_ANNTATION_URL = 'annotation/add-new';
const GET_ANNTATION_URL = 'annotation/get-by/document-id';
const DELETE_ANNTATION_URL = 'annotation';
const GET_LIST_DOCUMENTS_URL = 'document/get-by/client-id';
const ADD_ROTATE_DOCUMENTS_URL = 'document/add-rotate-page';
const ADD_DELETE_DOCUMENTS_URL = 'document/add-delete-page';

export const GET_BLOD_FROM_URL = 'GET_BLOD_FROM_URL';

export const GET_DOCUMENT_FILE = 'GET_DOCUMENT_FILE';

export const DOCUMENT_FILES = 'DOCUMENT_FILES';
export const DOCUMENT_FILES_SUCCESS = 'DOCUMENT_FILES_SUCCESS';
export const DOCUMENT_FILES_REQUEST_SUCCESS = 'DOCUMENT_FILES_REQUEST_SUCCESS';
export const DOCUMENT_FILES_ERROR = 'DOCUMENT_FILES_ERROR';

export const ADD_DOCUMENT_REQUEST = 'ADD_DOCUMENT_REQUEST';
export const ADD_DOCUMENT_ERROR = 'ADD_DOCUMENT_ERROR';
export const ADD_DOCUMENT_SUCCESS = 'ADD_DOCUMENT_SUCCESS';

export const GET_DOCUMENT_REQUEST = 'GET_DOCUMENT_REQUEST';
export const GET_DOCUMENT_ERROR = 'GET_DOCUMENT_ERROR';
export const GET_DOCUMENT_SUCCESS = 'GET_DOCUMENT_SUCCESS';

export const REMOVE_DOCUMENT_REQUEST = 'REMOVE_DOCUMENT_REQUEST';
export const REMOVE_DOCUMENT_ERROR = 'REMOVE_DOCUMENT_ERROR';
export const REMOVE_DOCUMENT_SUCCESS = 'REMOVE_DOCUMENT_SUCCESS';

export const REMOVE_DOCUMENT_DETAIL_REQUEST = 'REMOVE_DOCUMENT_DETAIL_REQUEST';
export const REMOVE_DOCUMENT_DETAIL_ERROR = 'REMOVE_DOCUMENT_DETAIL_ERROR';
export const REMOVE_DOCUMENT_DETAIL_SUCCESS = 'REMOVE_DOCUMENT_DETAIL_SUCCESS';

export const ADD_ROTATE_DOCUMENTS = 'ADD_ROTATE_DOCUMENTS';
export const ADD_DELETE_DOCUMENTS = 'ADD_DELETE_DOCUMENTS';
export const ADD_ANNTATION = 'ADD_ANNTATION';
export const EDIT_ANNTATION = 'EDIT_ANNTATION';
export const GET_ANNTATION = 'GET_ANNTATION';
export const DELETE_ANNTATION = 'DELETE_ANNTATION';

export const GET_RECIPIENTS = 'GET_RECIPIENTS';

export const GET_LIST_DOCUMENTS_REQUEST = 'GET_LIST_DOCUMENTS_REQUEST';
export const GET_LIST_DOCUMENTS_ERROR = 'GET_LIST_DOCUMENTS_ERROR';
export const GET_LIST_DOCUMENTS_SUCCESS = 'GET_LIST_DOCUMENTS_SUCCESS';


const state = {
  [ADD_DOCUMENT_REQUEST]: {
    data: null,
    loading: false,
  },
  [GET_DOCUMENT_REQUEST]: {
    data: [],
    loading: false,
  },
  [DOCUMENT_FILES]: [],
  isRemove: false,
  listDocuments: [],
}

const getters = {
  addDocument: state => {
    return {
      loading: state[ADD_DOCUMENT_REQUEST].loading,
      data: state[ADD_DOCUMENT_REQUEST].data
    }
  },
  [GET_DOCUMENT_REQUEST]: state => state[GET_DOCUMENT_REQUEST],
  [DOCUMENT_FILES]: state => state[DOCUMENT_FILES],
  getListDocs: state => state.listDocuments
}

const actions = {
  [ADD_ROTATE_DOCUMENTS]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      post(ADD_ROTATE_DOCUMENTS_URL, data)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [ADD_DELETE_DOCUMENTS]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      post(ADD_DELETE_DOCUMENTS_URL, data)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [DELETE_ANNTATION]: ({ commit, dispatch }, id) => {
    return new Promise((resolve, reject) => {
      remove(`${DELETE_ANNTATION_URL}/${id}`)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [GET_ANNTATION]: ({ commit, dispatch }, document_id) => {
    return new Promise((resolve, reject) => {
      get(`${GET_ANNTATION_URL}?document_id=${document_id}`)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [GET_RECIPIENTS]: ({ commit, dispatch }, document_id) => {
    return new Promise((resolve, reject) => {
      get(`${GET_RECIPIENTS_URL}?document_id=${document_id}`)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [ADD_ANNTATION]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      post(ADD_ANNTATION_URL, data)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [EDIT_ANNTATION]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      put(ADD_ANNTATION_URL, data)
        .then(resp => {
          resolve(resp.data)
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [ADD_DOCUMENT_REQUEST]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      commit(ADD_DOCUMENT_REQUEST);
      post(ADD_DOCUMENT_URL, data)
        .then(resp => {
          commit(ADD_DOCUMENT_SUCCESS, resp);
          resolve(resp.data)
        })
        .catch(err => {
          commit(ADD_DOCUMENT_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_DOCUMENT_REQUEST]: ({ commit, dispatch }, params) => {
    return new Promise((resolve, reject) => {
      commit(GET_DOCUMENT_REQUEST);
      let queryString = Object.keys(params).map(key => key + '=' + params[key]).join('&');;
      get(`${GET_DOCUMENT_BY_DOC_ID_URL}?${queryString}`)
        .then(resp => {
          resp && resp.data && resp.data.list && commit(GET_DOCUMENT_SUCCESS, resp.data.list);
          resolve(resp.data);
        })
        .catch(err => {
          commit(GET_DOCUMENT_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_DOCUMENT_FILE]: ({ commit, dispatch }, file) => {
    return new Promise((resolve, reject) => {
      getOutSide(file.downloadUrl, { responseType: 'blob', })
        .then(resp => {
          console.log('resp',resp,file)
          resp && commit(DOCUMENT_FILES_REQUEST_SUCCESS, addParamsToBlob(resp.data, file))
          resolve(resp.data);
        })
        .catch(err => {
          commit(DOCUMENT_FILES_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_BLOD_FROM_URL]: ({ commit, dispatch }, url) => {
    return new Promise((resolve, reject) => {
      getOutSide(url, { responseType: 'blob', })
        .then(resp => {
          resolve(resp.data);
        })
        .catch(err => {
          reject(err)
        })
    })
  },
  [REMOVE_DOCUMENT_REQUEST]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      remove(GET_DOCUMENT_URL + "/" + data, data)
        .then(resp => {
          resp && resp.data && commit(REMOVE_DOCUMENT_SUCCESS, resp.data);
          resolve(resp.data);
        })
        .catch(err => {
          commit(REMOVE_DOCUMENT_ERROR, err)
          reject(err)
        })
    })
  },
  [REMOVE_DOCUMENT_DETAIL_REQUEST]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      post(REMOVE_DOCUMENT_DETAIL_URL, data)
        .then(resp => {
          // resp && resp.data && commit(REMOVE_DOCUMENT_SUCCESS, resp.data);
          resolve(resp.data);
        })
        .catch(err => {
          // commit(REMOVE_DOCUMENT_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_LIST_DOCUMENTS_REQUEST]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      get(`${GET_LIST_DOCUMENTS_URL}?page=${data.page}&per_page=${data.per_page}&date=${data.date}&status=${data.status}&search=${data.search}`)
        .then(resp => {
          resp && resp.data && commit(GET_LIST_DOCUMENTS_SUCCESS, resp.data);
          resolve(resp.data);
        })
        .catch(err => {
          commit(GET_LIST_DOCUMENTS_ERROR, err)
          reject(err)
        })
    })
  },
}

const mutations = {
  // add document
  [ADD_DOCUMENT_REQUEST]: (state) => {
    state[ADD_DOCUMENT_REQUEST].loading = true;
  },
  [ADD_DOCUMENT_SUCCESS]: (state, resp) => {
    state[ADD_DOCUMENT_REQUEST].loading = false;
  },
  [ADD_DOCUMENT_ERROR]: (state) => {
    state[ADD_DOCUMENT_REQUEST].loading = false;
  },

  // get document
  [GET_DOCUMENT_REQUEST]: (state) => {
    state[GET_DOCUMENT_REQUEST].loading = true;
  },
  [GET_DOCUMENT_SUCCESS]: (state, data) => {
    state[GET_DOCUMENT_REQUEST].loading = false;
    state[GET_DOCUMENT_REQUEST].data = data;
  },
  [GET_DOCUMENT_ERROR]: (state) => {
    state[GET_DOCUMENT_REQUEST].loading = false;
  },

  // list document files
  [DOCUMENT_FILES_SUCCESS]: (state, file) => {
    state[DOCUMENT_FILES].push(file);
  },
  [DOCUMENT_FILES_REQUEST_SUCCESS]: (state, file) => {
    let _array = state[DOCUMENT_FILES];

    Array.from(_array).forEach((v, k) => {
      if (v.downloadUrl === file.downloadUrl) {
        _array[k] = file;
      }
    });
  },
  [DOCUMENT_FILES_ERROR]: (state, err) => {
    console.log(err)
  },

  // remove document
  [REMOVE_DOCUMENT_SUCCESS]: (state, data) => {
    state.isRemove = data.status;
  },
  [REMOVE_DOCUMENT_ERROR]: (state) => {
    state.isRemove = false;
  },

  // remove document
  [GET_LIST_DOCUMENTS_SUCCESS]: (state, data) => {
    state.listDocuments = data.list;
  },
  [GET_LIST_DOCUMENTS_ERROR]: (state) => {
    state.listDocuments = [];
  },
}

export default {
  state,
  getters,
  actions,
  mutations,
}
