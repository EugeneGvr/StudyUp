import Vue from 'vue'
import VueI18n from 'vue-i18n'

Vue.use(VueI18n);

export const i18n = new VueI18n({
    locale: 'ua',
    fallbackLocale: 'ru',
    messages: {
        ua: {
            hhh: 'HHH'
        },
        ru: {
            hhh: 'HHH'
        }
    }
})
