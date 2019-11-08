import { LANG_KEY , initLang } from "../../i18n";

export const CHANGE_LANGUAGE_ACTION = "CHANGE_LANGUAGE_ACTION";

const ON_CHANGE_LANGUAGE = "ON_CHANGE_LANGUAGE";

const state = {
    lang: initLang
}

const actions = {
    [CHANGE_LANGUAGE_ACTION]({ commit }, payload) {
        commit(ON_CHANGE_LANGUAGE, payload);
    }
}

const mutations = {
    [ON_CHANGE_LANGUAGE](state, payload) {
        window.localStorage.setItem(LANG_KEY, payload.lang)
        payload.i18n.locale = payload.lang
        payload.i18n.fallbackLocale = payload.lang
        state.lang = payload.lang
    }
}

export default {
    state,
    mutations,
    actions
}