import Vue from 'vue'
import VueI18n from 'vue-i18n'

import { localeUa } from './Locales/ua'
import { localeRu } from './Locales/ru'
import { localeEn } from './Locales/en'

Vue.use(VueI18n);

export const i18n = new VueI18n({
    locale: 'ua',
    fallbackLocale: 'ru',
    messages: {
        ua: localeUa(),
        ru: localeRu(),
        en: localeEn(),
    }
})
