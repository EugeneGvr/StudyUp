<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-6 font-bold text-3xl">
                    <inertia-link class="text-blue hover:text-indigo-dark" :href="route('admin.roles')">Roles</inertia-link>
                    <span class="text-indigo-light font-medium">/</span> {{ form.name }}
                </h1>
                <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo" type="submit">Update Role</loading-button>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <text-input v-model="form.name" :errors="$page.errors.name" class="pb-8 w-full" label="Name"/>
                        <textarea-input v-model="form.description" :errors="$page.errors.description" class="pb-2 w-full" label="Description"/>
                    </div>
                    <div class="bg-white rounded shadow pr-8 pl-8 pt-4 pb-4 m-2">
                        <button class="btn-red" tabindex="-1" type="button" @click="popupActivo=true">Delete</button>
                    </div>
                </div>
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <div
                            class="mb-3 ml-3 cursor-pointer hover:text-blue-dark select-none"
                        >
                            select all
                        </div>
                        <div v-for="(permission, p_key) in allPermissions" :key="p_key">
                            <div class="mb-5">{{ p_key | capitalize }}</div>
                            <div v-for="(action, a_key) in permission" :key="a_key" class="ml-3 mb-3">
                                <input
                                    id="checkbox"
                                    type="checkbox"
                                    v-model="form.permissions"
                                    :value="p_key + '.' + action"
                                    :checked="role.permissions.includes(p_key + '.' + action)"
                                >
                                <label for="checkbox">{{ action | capitalize }} {{ p_key }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="centerx">
            <vs-popup class="holamundo"  title="Delete" :active.sync="popupActivo">
                <div class="ml-6 mr-6 mt-3 mb-3">
                <span>Are you sure you want to delete role</span><b> {{role.name}}</b><span>?</span>
                </div>
                <div class="float-right">
                <button class="btn-red mr-2 mt-2 mb-2" tabindex="-1" type="button" @click="destroy">Delete</button>
                </div>
            </vs-popup>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'

export default {
  metaInfo() {
    return { title: this.form.name }
  },
  layout: (h, page) => h(Layout, [page]),
  components: {
    LoadingButton,
    TextInput,
    TextareaInput,
  },
    filters: {
        capitalize: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        }
    },
  props: {
    role: Object,
    allPermissions: Object,
  },
  remember: 'form',
  data() {
    return {
      popupActivo:false,
      sending: false,
      form: {
        name: this.role.name,
        description: this.role.description,
        permissions: this.role.permissions,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.put(this.route('admin.roles.update', this.role.id), this.form)
        .then(() => this.sending = false)
    },
      destroy() {
          this.$inertia.delete(this.route('admin.roles.destroy', this.role.id)).then(this.popupActivo = false)
      },
  },
}
</script>
