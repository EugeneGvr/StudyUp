<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-6 font-bold text-3xl">
                    <inertia-link class="text-indigo-light hover:text-indigo-dark" :href="route('admin.admins')">
                        Administrators
                    </inertia-link>
                    <span class="text-indigo-light font-medium">/</span> {{ form.first_name + ' ' + form.last_name }}
                </h1>
                <div class="manage-buttons flex">
                    <div
                        class="btn-red mr-2 border-t border-grey-lighter flex justify-end items-center"
                        @click="deleteModal=true"
                    >
                        <span>{{$t('Delete')}}</span>
                    </div>
                    <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                        <loading-button :loading="sending" class="btn-indigo" type="submit">
                            {{$t('Update')}}
                        </loading-button>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-12 m-2">
                        <file-input
                            v-model="form.photo"
                            :errors="$page.errors.photo"
                            :path="form.photo_path"
                            class="pb-4 w-full"
                            label="Photo"
                        />
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
                    </div>
                </div>
            </div>
        </form>
        <div class="modals">
            <md-dialog :md-active.sync="deleteModal">
                <md-dialog-title>
                    <span>{{$t('Are you sure you want to delete an administrator')}}</span>
                    <span class="text-blue">{{admin.first_name + ' ' + admin.last_name }}</span>
                    <span>?</span>
                </md-dialog-title>
                <md-dialog-content>
                    <span>{{$t('Be careful, if you will delete an administrator, you wont be able to restore it')}}</span>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="deleteModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy">
                        {{$t('Delete')}}
                    </button>
                </md-dialog-actions>
            </md-dialog>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'

export default {
  metaInfo() {
    return { title: this.form.first_name }
  },
  layout: (h, page) => h(Layout, [page]),
  components: {
    Icon,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
  },
    props: {
        admin: Object,
        roles: Array
        ,
    },
  remember: 'form',
  data() {
    return {
        //[start] modals variables
        deleteModal: false,
        //[end] modals variables

      sending: false,
      form: {
          first_name: this.admin.first_name,
          last_name: this.admin.last_name,
          email: this.admin.email,
          phone: this.admin.phone,
          photo_path: this.admin.photo_path,
          photo: null,
          role: this.admin.role,
      },
    }
  },
  methods: {
    submit() {
        this.sending = true;

        let data = new FormData();
        data.append('first_name', this.form.first_name || '');
        data.append('last_name', this.form.last_name || '');
        data.append('email', this.form.email || '');
        data.append('phone', this.form.phone || '');
        data.append('role', this.form.role || '');
        data.append('photo', this.form.photo || '');

        this.$inertia.put(
            this.route('admin.admins.update', this.admin.id),
            data
        ).then(() => this.sending = false)
    },
    destroy() {
        this.$inertia.delete(this.route('admin.admins.destroy', this.admin.id))
    },
  },
}
</script>
