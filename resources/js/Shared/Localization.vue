<template>
  <div>
    <div class="bg-white rounded shadow-inner flex flex-wrap bg-grey-lightest">

        <div v-for="locale in locales" class="cursor-pointer">
            <div
                v-if="isCurrentLocale(locale)"
                class="bg-blue text-white shadow rounded flex p-3"
            >
                {{locale}}
            </div>
            <div v-else class="flex p-3" @click="setLocale(locale)">
                {{locale}}
            </div>
        </div>
    </div>
  </div>
</template>

<script>

    export default {
        props: {
            locales: Array,
            default: String,
        },
        methods: {
            setLocale(locale) {
                this.$i18n.locale = locale;
                localStorage.setItem('locale', locale);
            },
            isCurrentLocale(locale) {
                return this.$i18n.locale == locale
            },
        },
        beforeMount() {
            const userLocale = localStorage.getItem('locale');
            console.log(userLocale);
            if (userLocale == null) {
                console.log(this.default);
            }
            const locale = userLocale ? userLocale : this.default;
            console.log(locale);
            this.setLocale(locale);
        },
    }
</script>
