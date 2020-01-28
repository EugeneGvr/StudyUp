<template>
    <div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.dashboard')">
                <icon name="dashboard" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Dashboard')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.subjects')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Subjects')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.themes')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Themes')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.subthemes')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Subthemes')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.questions')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Questions')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.users')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Users')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.admins')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Administrators')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.roles')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Roles')}}
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.localities')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    {{$t('Localities')}}
                </div>
            </inertia-link>
        </div>
    </div>
</template>

<script>
    import Icon from '@/Shared/Icon'

    export default {
        components: {
            Icon,
        },
        props: {
            url: String,
        },
        methods: {
            isUrl(...urls) {
                if (urls[0] === '') {
                    return this.url === ''
                }

                return urls.filter(url => this.url.startsWith(url)).length
            },
        },
    }
</script>
