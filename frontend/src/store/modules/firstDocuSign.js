import JwtService from "../../mixins/jwt.service"
import { post, put, remove, get } from '../../utils/http';

import { CHECK_CLIENT, RECIPIENTS_ADD, RECIPIENTS_UPDATE, GET_EMAIL_CLIENT, GET_DOCS, RECIPIENTS_REMOVE, GET_FRIENDS_KAKAO_REQUEST } from '../actions.type'
import { IS_CLIENTED,  ERR_CLIENTED, RECIPIENTS_SUCCESS, RECIPIENTS_ERROR,GET_EMAIL_CLIENT_SUCCESS, GET_EMAIL_CLIENT_ERROR, GET_DOCS_SUCCESS, GET_DOCS_ERROR, RECIPIENTS_REMOVE_SUCCESS, RECIPIENTS_REMOVE_ERROR} from '../mutations.type'

const state = {
    isClient: false,
    isRecipients: false,
    emailClients: [],
    recipients: [],
    documents: [],
}

const getters = {
    _isClient(state)
    {
        return state.isClient
    },
    getEmailClients: state => state.emailClients,
    getRecipients: state => state.recipients,
    getDocuments: state => state.documents,
    
}

const actions = {
  [CHECK_CLIENT]: ({ commit }) => {
    return new Promise((resolve, reject) => {
      get('clients/get-client')
        .then(resp => {
          commit(IS_CLIENTED, resp.data);
          resolve(resp.data)
        })
        .catch(err => {
          commit(ERR_CLIENTED, err)
          reject(err)
        })
    })
  },
  [RECIPIENTS_ADD]: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      post('recipients/add', data)
        .then(resp => {
          console.log(resp)
          commit(RECIPIENTS_SUCCESS, resp.data);
          resolve(resp)
        })
        .catch(err => {
          console.log(err)
          commit(RECIPIENTS_ERROR, err)
          reject(err)
        })
    })
  },
  [RECIPIENTS_UPDATE]: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      put('recipients/add', data)
        .then(resp => {
          console.log(resp)
          commit(RECIPIENTS_SUCCESS, resp.data);
          resolve(resp)
        })
        .catch(err => {
          console.log(err)
          commit(RECIPIENTS_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_EMAIL_CLIENT]: ({ commit }) => {
    return new Promise((resolve, reject) => {
      get('clients/get-all-email-client')
        .then(resp => {
          commit(GET_EMAIL_CLIENT_SUCCESS, resp.data);
          resolve(resp)
        })
        .catch(err => {
          commit(GET_EMAIL_CLIENT_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_DOCS]: ({ commit }, document_id) => {
    return new Promise((resolve, reject) => {
      get('document/get-by/document-id?document_id='+document_id)
        .then(resp => {
          commit(GET_DOCS_SUCCESS, resp.data);
          resolve(resp)
        })
        .catch(err => {
          commit(GET_DOCS_ERROR, err)
          reject(err)
        })
    })
  },
  [RECIPIENTS_REMOVE]: ({ commit, dispatch }, data) => {
    return new Promise((resolve, reject) => {
      remove("recipients/delete/"+data, data)
        .then(resp => {
          // resp && commit(RECIPIENTS_REMOVE_SUCCESS, resp.data);
          resolve(resp);
        })
        .catch(err => {
          // commit(RECIPIENTS_REMOVE_ERROR, err)
          reject(err)
        })
    })
  },
  [GET_FRIENDS_KAKAO_REQUEST]: ({ commit }, data) => {
    return new Promise((resolve, reject) => {
      post('recipients/get-kakao-friends', data)
        .then(resp => {
          // commit(GET_DOCS_SUCCESS, resp.data);
          resolve(resp)
        })
        .catch(err => {
          // commit(GET_DOCS_ERROR, err)
          reject(err)
        })
    })
  },
}

const mutations = {
    [IS_CLIENTED](state, payload) {
        state.isClient = payload.status
    },
    [ERR_CLIENTED](state, payload) {
        state.isClient = false
    },
    [RECIPIENTS_SUCCESS](state, payload) {
        state.isRecipients = payload.status
    },
    [RECIPIENTS_ERROR](state, payload) {
        state.isRecipients = false
    },
    [GET_EMAIL_CLIENT_SUCCESS](state, payload) {
        state.emailClients = payload.list_email
    },
    [GET_EMAIL_CLIENT_ERROR](state, payload) {
        state.isRecipients = []
    },
    [GET_DOCS_SUCCESS](state, payload) {
        var datas = payload.list[0].recipients
        var _type_name = "";

        datas.map( (value, index) => {
          if( value.action === "sign" ){
            _type_name = "Needs to Sign"
          }else if(value.action === "signer"){
            _type_name =  "In personal signer"
          }else {
            _type_name =  "Receives a Copy"
          }

          Object.assign(datas[index], {
            com_type: value.email ? true : false,
            set_password: value.password ? true : false,
            confirm_password: value.password,
            sign_type_name: _type_name
          });
          delete datas[index].pivot;
          delete datas[index].updated_at;
          delete datas[index].created_at;
        })
        state.recipients = datas;
        state.documents = payload.list;
    },
    [GET_DOCS_ERROR](state, payload) {
        state.recipients = []
        state.documents = []
    },
    [RECIPIENTS_REMOVE_SUCCESS](state, payload) {

    },
    [RECIPIENTS_REMOVE_ERROR](state, payload) {

    },
}
export default {
    state,
    getters,
    mutations,
    actions
}