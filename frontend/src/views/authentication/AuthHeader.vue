<template>
  <div>
    <b-link href="javascript:;" class="logo">
      <img src="/img/logo_dark.png"/>
    </b-link>
        
    <b-dropdown class="language-select">
      <template v-slot:button-content>
        {{ ($i18n.locale === 'gb') ? "English" : ($i18n.locale === 'jp' ? "日本語" : "한국어") }}
      </template>
      <b-dropdown-item
        v-for="(language, index) in languages"
        :key="index"
        v-on:click="changeLanguage(language)">
        <img :src="`/img/flags/${language}.png`" class="flag"/>
        {{ (language === 'gb') ? "English" : (language === 'jp' ? "日本語" : "한국어") }} 
      </b-dropdown-item>
    </b-dropdown>
    <img :src="`/img/flags/${$i18n.locale}.png`" class="flag"/>

    <p style="font-size: 15px; color: #000; font-style: italic;">{{ note }}</p>
  </div>
</template>

<script>
import { CHANGE_LANGUAGE_ACTION } from '../../store/actions.type'
import { LANGUAGES } from '../../i18n'

export default {
  name: 'AuthHeader',
  components: {

  },
  data() {
    return {
      languages: LANGUAGES,
      note: process.env.VUE_APP_NOTE
    }
  },
  computed: {
    selectedLang() {
      return this.$store.state.language.lang
    }
  },
  mounted() {

  },
  methods: {
    changeLanguage(lang) {
      this.$store.dispatch(CHANGE_LANGUAGE_ACTION, {
        lang: lang,
        i18n: this.$root.$i18n
      })
    },
  }
}
</script>

<style scoped>

</style>