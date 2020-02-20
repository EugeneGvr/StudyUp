<template>
    <div class=" bg-grey-darkest">
        <logo-a-i class="m-6 absolute block"/>
        <div class="p-6  min-h-screen flex justify-center items-center">
            <div class="w-full max-w-sm">
                <form class="bg-white rounded-lg shadow-lg overflow-hidden" @submit.prevent="submit">
                    <div class="px-10 py-12">
                        <h1 class="text-center font-light text-md">Log in</h1>
                        <div class="mx-auto mt-6 w-24 border-b-2"/>
                        <text-input v-model="form.email" :errors="$page.errors.email" class="mt-10" label="Email"
                                    type="email" autofocus autocapitalize="off"/>
                        <text-input v-model="form.password" class="mt-6" label="Password" type="password"/>
                        <label class="mt-6 select-none flex items-center" for="remember">
                            <input id="remember" v-model="form.remember" class="mr-1" type="checkbox">
                            <span class="text-sm">Remember Me</span>
                        </label>
                    </div>
                    <div
                        class="px-10 py-4 bg-grey-lightest border-t border-grey-lighter flex justify-between items-center">
                        <a class="hover:underline" tabindex="-1" href="#reset-password">Forget password?</a>
                        <loading-button :loading="sending" class="btn-indigo" type="submit">Login</loading-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingButton from '@/Shared/LoadingButton'
    import LogoAI from '@/Shared/LogoAI'
    import TextInput from '@/Shared/TextInput'

    export default {
        metaInfo: {title: 'Login'},
        components: {
            LoadingButton,
            LogoAI,
            TextInput,
        },
        props: {
            errors: Object,
        },
        data() {
            return {
                sending: false,
                form: {
                    email: null,
                    password: null,
                    remember: null,
                },
            }
        },
        methods: {
            submit() {
                this.sending = true
                this.$inertia.post(this.route('login.attempt'), {
                    email: this.form.email,
                    password: this.form.password,
                    remember: this.form.remember,
                }).then(() => this.sending = false)
            },
        },
    }
</script>
