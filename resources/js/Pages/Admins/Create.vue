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
                    <loading-button :loading="sending" class="btn-indigo" type="submit">Add Administrator</loading-button>
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
                        <text-input v-model="form.name" :errors="$page.errors.name" class="pb-4 w-full" label="Name"/>
                        <text-input v-model="form.surname" :errors="$page.errors.surname" class="pb-4 w-full" label="Surname"/>
                        <text-input v-model="form.email" :errors="$page.errors.email" class="pb-4 w-full"
                                    label="Email"/>
                        <text-input v-model="form.phone" :errors="$page.errors.phone" class="pb-4 w-full"
                                    label="Phone"/>
                        <select-input
                            v-model="form.role"
                            :errors="$page.errors.role"
                            class="pb-3 w-full" label="Role"
                        >
                            <md-option v-for="role in roles.data" :key="role.id" :value="role.id">
                                {{role.name}}
                            </md-option>
                        </select-input>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'

export default {
  metaInfo: { title: 'Add Administrator' },
  layout: (h, page) => h(Layout, [page]),
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
  },
    props: {
        roles: Object,
    },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        name: null,
        surname: null,
        email: null,
        phone: null,
        role: null,
        photo: null,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.post(this.route('admin.admins.store'), this.form)
        .then(() => this.sending = false)
    },
      successUpload(){
          this.$vs.notify({color:'success',title:'Upload Success',text:'Lorem ipsum dolor sit amet, consectetur'})
      }
  },
}
</script>
