<template>
  <div>
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
                  <textarea-input v-model="form.description" :errors="$page.errors.description" class="pb-2 w-full" label="Description"/>
                  </div>
              </div>
              <div class="flex-col lg:w-1/2 sm:w-full">
                  <div class="bg-white rounded shadow p-8 m-2">
                      <div class="mb-5">Permissions</div>
                      <div class="mb-5 flex items-center">
                          <vs-switch class="mr-2" v-model="form.permissions" id="all-permissions" vs-value="all.all" @click="selectAll"/>
                          <label for="all-permissions">Select all</label>
                      </div>
                      <div class="ml-5">
                      <div v-for="(permission, p_key) in allPermissions" :key="p_key">
                          <div class="flex items-center">
                              <vs-switch class="mr-2" v-model="form.permissions" :id="p_key" :vs-value="p_key" :ref="p_key" @click="selectModule(p_key, permission)" />
                              <label :for="p_key">{{ p_key | capitalize }}</label>
                          </div>
                          <div class="ml-5 my-4">
                          <div v-for="(action, a_key) in permission" :key="a_key" class="my-3 flex items-center">
                              <vs-switch class="mr-2" v-model="form.permissions" :id="p_key + '-' + action" :vs-value="p_key + '.' + action" />
                              <label :for="p_key + '-' + action">{{ action | capitalize }} {{ p_key }}</label>
                          </div>
                          </div>
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
    allPermissions: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        name: null,
        description: null,
        permissions: [],
      },
    }
  },
  methods: {
      submit() {
          this.sending = true,
          this.$inertia.post(this.route('admin.roles.store'), this.form)
              .then(() => this.sending = false)
      },
      selectModule(module, permission) {
          console.log(this.$refs[module][0].isChecked);
          for (const action of permission) {
              if(this.$refs[module][0].isChecked) {
                  const index = this.form.permissions.indexOf(module + '.' + action);

                  if (index > -1) {
                      this.form.permissions.splice(index, 1);
                  }
              } else {
                  this.form.permissions.push(module + '.' + action);
              }
          }
      },
      selectAll() {

      }
  },
}
</script>
