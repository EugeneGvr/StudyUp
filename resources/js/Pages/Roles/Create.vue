<template>
  <div>
      <vs-button vs-type="filled">Primary</vs-button>
<!--    <h1 class="mb-6 font-bold text-3xl">-->
<!--      <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.roles')">Roles</inertia-link>-->
<!--      <span class="text-indigo-light font-medium">/</span> Create-->
<!--    </h1>-->
      <form @submit.prevent="submit">
          <div class="flex justify-between items-center">
              <h1 class="mb-6 font-bold text-3xl">
                  <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.roles')">Roles</inertia-link>
                  <span class="text-indigo-light font-medium">/</span> Create
              </h1>
              <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                  <loading-button :loading="sending" class="btn-indigo" type="submit">Add Role</loading-button>
              </div>
          </div>
          <div class="flex flex-wrap">
              <div class="flex-col lg:w-1/2 sm:w-full">
                  <div class="bg-white rounded shadow p-8 m-2">
                  <text-input v-model="form.name" :errors="$page.errors.name" class="pb-8 w-full" label="Name"/>
                  <textarea-input v-model="form.email" :errors="$page.errors.email" class="pb-2 w-full" label="Description"/>
                  </div>
              </div>
              <div class="flex-col lg:w-1/2 sm:w-full">
                  <div class="bg-white rounded shadow p-8 m-2">
                      <div v-for="(permission, p_key) in permissions" :key="p_key">
                          {{ p_key | capitalize }}
                          <div v-for="(permission_action, pa_key) in permission" :key="pa_key">
<!--                              <input type="checkbox" id="checkbox">-->
                              <vs-checkbox v-model="checkBox1">{{ permission_action | capitalize }} {{ p_key }}</vs-checkbox>
<!--                              <label for="checkbox">{{ permission_action | capitalize }} {{ p_key }}</label>-->
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </form>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'


export default {
  metaInfo: { title: 'Create Role' },
  layout: (h, page) => h(Layout, [page]),
  components: {
    LoadingButton,
    TextInput,
    TextareaInput,
  },
  filters:  {
      capitalize: function(value) {
          if(!value) return ''
          value = value.toString()
          return value.charAt(0).toUpperCase() + value.slice(1)
      }
  },
  props: {
    permissions: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        name: null,
        description: null,
        permissions: null,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.post(this.route('admin.roles.store'), this.form)
        .then(() => this.sending = false)
    },
  },
}
</script>
