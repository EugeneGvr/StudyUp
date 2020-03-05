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
                        Add Administrator
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
    import Layout from '@/Shared/AdminLayout'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectInput from '@/Shared/SelectInput'
    import TextInput from '@/Shared/TextInput'
    import FileInput from '@/Shared/FileInput'

    export default {
        metaInfo: {title: 'Add Administrator'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            LoadingButton,
            SelectInput,
            TextInput,
            FileInput,
        },
        props: {
            roles: Array,
        },
        remember: 'form',
        data() {
            return {
                sending: false,
                form: {
                    first_name: null,
                    last_name: null,
                    email: null,
                    phone: null,
                    photo: null,
                    role: null,
                    password: null,
                    password_auto_generation: true,
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
                data.append('password', this.form.password || '');
                data.append('phone', this.form.phone || '');
                data.append('role', this.form.role || '');
                data.append('password_auto_generation', this.form.password_auto_generation ? 1 : 0);
                data.append('photo', this.form.photo || '');

                this.$inertia.post(
                    this.route('admin.admins.store'),
                    data
                ).then(() => this.sending = false)
            },
            successUpload() {
                this.$vs.notify({
                    color: 'success',
                    title: 'Upload Success',
                    text: 'Lorem ipsum dolor sit amet, consectetur'
                })
            }
        },
    }
</script>
