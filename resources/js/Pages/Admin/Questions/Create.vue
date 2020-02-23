<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.users')">Users</inertia-link>
      <span class="text-indigo-light font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded shadow overflow-hidden max-w-lg">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.text" :errors="$page.errors.text" class="pr-6 pb-8 w-full lg:w-1/2" :label="$t('Text')" />
            <select-input
                v-model="form.subject_id"
                :errors="$page.errors.theme_id"
                class="pb-3 w-full" :label="$t('Subject')"
            >
                <md-option v-for="subject in subjects.data" :key="subject.id" :value="subject.id">
                    {{subject.name}}
                </md-option>
            </select-input>
            <select-input
                v-model="form.theme_id"
                :errors="$page.errors.theme_id"
                class="pb-3 w-full" :label="$t('Theme')"
            >
                <md-option v-for="theme in themes.data" :key="theme.id" :value="theme.id">
                    {{theme.name}}
                </md-option>
            </select-input>
            <select-input
                v-model="form.subtheme_id"
                :errors="$page.errors.subtheme_id"
                class="pb-3 w-full" :label="$t('Subtheme')"
            >
                <md-option v-for="subtheme in subthemes.data" :key="subtheme.id" :value="subtheme.id">
                    {{subtheme.name}}
                </md-option>
            </select-input>
            <select-input
                v-model="form.type"
                :errors="$page.errors.type"
                class="pb-3 w-full" :label="$t('Type')"
            >
                <md-option v-for="(type, type_key) in types" :key="type_key" :value="type_key">
                    {{type}}
                </md-option>
            </select-input>
          <file-input v-model="form.photo" :errors="$page.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="px-8 py-4 bg-grey-lightest border-t border-grey-lighter flex justify-end items-center">
          <loading-button :loading="sending" class="btn-indigo" type="submit">Create User</loading-button>
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
  metaInfo: { title: 'Create User' },
  layout: (h, page) => h(Layout, [page]),
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        owner: false,
        photo: null,
      },
    }
  },
  methods: {
    submit() {
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
    },
  },
}
</script>
