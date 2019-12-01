<template>
    <div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.dashboard')">
                <icon name="dashboard" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Головна</div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.subjects')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Предмети</div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.themes')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Теми</div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.subthemes')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Підтеми</div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.questions')">
                <icon name="book" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Питання</div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.users')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    Користувачі
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.admins')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">
                    Адміністратори
                </div>
            </inertia-link>
        </div>
        <div class="mb-4">
            <inertia-link class="flex items-center group py-3" :href="route('admin.roles')">
                <icon name="users" class="w-4 h-4 mr-2"
                      :class="isUrl('') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'"/>
                <div :class="isUrl('') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Ролі</div>
            </inertia-link>
        </div>
        <!--    <div class="mb-4">-->
        <!--      <inertia-link class="flex items-center group py-3" :href="route('admin.organizations')">-->
        <!--        <icon name="office" class="w-4 h-4 mr-2" :class="isUrl('admin.organizations') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'" />-->
        <!--        <div :class="isUrl('admin.organizations') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Organizations</div>-->
        <!--      </inertia-link>-->
        <!--    </div>-->
        <!--    <div class="mb-4">-->
        <!--      <inertia-link class="flex items-center group py-3" :href="route('admin.contacts')">-->
        <!--        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('admin.contacts') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'" />-->
        <!--        <div :class="isUrl('admin.contacts') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Contacts</div>-->
        <!--      </inertia-link>-->
        <!--    </div>-->
        <!--    <div class="mb-4">-->
        <!--      <inertia-link class="flex items-center group py-3" :href="route('admin.reports')">-->
        <!--        <icon name="printer" class="w-4 h-4 mr-2" :class="isUrl('admin.reports') ? 'fill-white' : 'fill-indigo-light group-hover:fill-white'" />-->
        <!--        <div :class="isUrl('admin.reports') ? 'text-white' : 'text-indigo-lighter group-hover:text-white'">Reports</div>-->
        <!--      </inertia-link>-->
        <!--    </div>-->
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
