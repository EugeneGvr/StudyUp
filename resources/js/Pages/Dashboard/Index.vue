<template>
    <div class="flex">
htyijyukuioouilui;l
        <div
            v-for="subject in subjects" :key="subject.id"
            class=""
        >
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo"
                          :href="route('test', subject.id)">
                {{subject.name}}
            </inertia-link>
        </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'


export default {
    metaInfo: {title: 'Home'},
    layout: (h, page) => h(Layout, [page]),
    components: {
        Icon,
    },
    props: {
        subjects: Array,
    },
    // data() {
    //     return {
    //
    //     }
    // },
    // methods: {
    //
    // },
}
</script>
