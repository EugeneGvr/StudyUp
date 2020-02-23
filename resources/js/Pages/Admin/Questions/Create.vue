<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.questions')">
                {{$t('Questions')}}
            </inertia-link>
            <span class="text-indigo-light font-medium">/</span> {{$t('Add')}}
        </h1>
        <div class="bg-white rounded shadow overflow-hidden max-w-lg">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="text" :errors="$page.errors.text" class="pr-6 pb-8 w-full lg:w-1/2"
                                :label="$t('Text')"/>
                    <select-input
                        v-on:change="getThemes()"
                        v-model="subject_id"
                        :errors="$page.errors.theme_id"
                        class="pb-3 w-full" :label="$t('Subject')"
                    >
                        <md-option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                            {{subject.name}}
                        </md-option>
                    </select-input>
                    <select-input
                        v-if="subject_id !== null"
                        v-model="theme_id"
                        :errors="$page.errors.theme_id"
                        class="pb-3 w-full" :label="$t('Theme')"
                    >
                        <md-option v-for="theme in themes.data" :key="theme.id" :value="theme.id">
                            {{theme.name}}
                        </md-option>
                    </select-input>
                    <select-input
                        v-if="theme_id !== null"
                        v-model="subtheme_id"
                        :errors="$page.errors.subtheme_id"
                        class="pb-3 w-full" :label="$t('Subtheme')"
                    >
                        <md-option v-for="subtheme in subthemes.data" :key="subtheme.id" :value="subtheme.id">
                            {{subtheme.name}}
                        </md-option>
                    </select-input>
                    <select-input
                        v-model="answer_type"
                        :errors="$page.errors.answer_type"
                        class="pb-3 w-full" :label="$t('Type')"
                    >
                        <md-option v-for="(type, type_key) in answer_types" :key="type_key" :value="type_key">
                            {{type}}
                        </md-option>
                    </select-input>
                    <file-input v-model="photo" :errors="$page.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2"
                                type="file" accept="image/*" label="Photo"/>
                </div>
                <div class="px-8 py-4 bg-grey-lightest border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo" type="submit">Create Question</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import Layout from '@/Shared/Layout'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectInput from '@/Shared/SelectInput'
    import TextInput from '@/Shared/TextInput'
    import FileInput from '@/Shared/FileInput'

    export default {
        metaInfo: {title: 'Create Question'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            LoadingButton,
            SelectInput,
            TextInput,
            FileInput,
        },
        props: {
            subjects: Array,
            answer_types: Object,
        },
        data() {
            return {
                themes: Object,
                subthemes: Object,
                sending: false,
                //form variables
                text: null,
                subject_id: null,
                theme_id: null,
                subtheme_id: null,
                answer_type: null,
                photo: null,
                //form variables
            }
        },
        watch: {
            subject_id() {
                console.log("======================================================");
                const response = this.$inertia.post(this.route('api.v1.themes', this.subject_id));
                console.log(response);
            },
        },
        methods: {
            /*submit() {
                this.sending = true

                var data = new FormData()
                data.append('first_name', this.form.first_name || '')
                data.append('last_name', this.form.last_name || '')
                data.append('email', this.form.email || '')
                data.append('password', this.form.password || '')
                data.append('owner', this.form.owner ? '1' : '0')
                data.append('photo', this.form.photo || '')

                this.$inertia.post(this.route('admin.users.store'), data)
                    .then(() => this.sending = false)
            },*/

            getThemes() {
                console.log('================================================================');
                //const response = this.$inertia.post(this.route('api.v1.themes'), this.form.subject_id);
            },

            getSubthemes() {
                const response = this.$inertia.post(this.route('api.v1.subthemes'), this.theme_id);
            },
        },
    }
</script>
