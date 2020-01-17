<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-6 font-bold text-3xl">
                    <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.admins')">
                        Administrators
                    </inertia-link>
                    <span class="text-indigo-light font-medium">/</span> Add
                </h1>
                <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo" type="submit">
                        Update Administrator
                    </loading-button>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-12 m-2">
                        <file-input v-model="form.photo" :errors="$page.errors.photo" class="pb-4 w-full"
                                    label="Photo"/>
                    </div>
                </div>
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-12 m-2">
                        <text-input v-model="form.first_name" :errors="$page.errors.first_name" class="pb-4 w-full" label="Name"/>
                        <text-input v-model="form.last_name" :errors="$page.errors.last_name" class="pb-4 w-full"
                                    label="Surname"/>
                        <text-input v-model="form.email" :errors="$page.errors.email" class="pb-4 w-full"
                                    label="Email"/>
                        <text-input v-model="form.phone" :errors="$page.errors.phone" class="pb-4 w-full"
                                    label="Phone"/>
                        <select-input
                            v-model="form.role"
                            :errors="$page.errors.role"
                            class="pb-3 w-full" label="Role"
                        >
                            <md-option v-for="role in roles" :key="role.id" :value="role.id">
                                {{role.name}}
                            </md-option>
                        </select-input>
                        <md-switch v-model="form.password_auto_generation" class="md-primary">
                            Generate password automatically
                        </md-switch>
                        <text-input
                            v-if="!form.password_auto_generation"
                            type="password"
                            v-model="form.password"
                            :errors="$page.errors.password"
                            class="pb-4 w-full"
                        />
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return { title: this.form.name }
  },
  layout: (h, page) => h(Layout, [page]),
  components: {
    Icon,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  props: {
    organization: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
          name: this.admin.name,
          surname: this.admin.surname,
          email: this.admin.email,
          phone: this.admin.phone,
          role: this.admin.role,
          photo: this.admin.photo,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.put(this.route('admin.admins.update', this.admin.id), this.form)
        .then(() => this.sending = false)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this administrator?')) {
        this.$inertia.delete(this.route('admin.admins.destroy', this.admin.id))
      }
    },
    // restore() {
    //   if (confirm('Are you sure you want to restore this administrator?')) {
    //     this.$inertia.put(this.route('administrators.restore', this.admin.id))
    //   }
    // },
  },
}
</script>
