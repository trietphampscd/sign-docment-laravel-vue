import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n)

export let LANGUAGES = [];

export const LANG_KEY = "language";

const loadLocaleMessages = () => {
  const locales = require.context('./locales', true, /[A-Za-z0-9-_,\s]+\.json$/i)
  const messages = {}
  locales.keys().forEach(key => {
    const matched = key.match(/([A-Za-z0-9-_]+)\./i)
    if (matched && matched.length > 1) {
      const locale = matched[1];
      LANGUAGES.push(locale);
      messages[locale] = locales(key)
    }
  })
  return messages
}

export const initLang = (() => {
  let lang = window.localStorage.getItem(LANG_KEY) // || window.navigator.language
  return lang || 'gb'
})()

export default new VueI18n({
  locale: initLang,
  fallbackLocale: initLang,
  formatFallbackMessages: true,
  sync: true,
  messages: loadLocaleMessages()
})