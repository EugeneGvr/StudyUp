<template>
    <div class="overflow-hidden">
htyijyukuioouilui;l
    </div>
</template>

<script>
import _ from 'lodash'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

export default {
    metaInfo: {title: 'Localities'},
    layout: (h, page) => h(Layout, [page]),
    components: {
        Icon,
        Pagination,
        SearchFilter,
    },
    props: {
        breadcrumb: Array,
        localities: Object,
        filters: Object,
    },
    data() {
        return {
            addModal: false,
            editModal: false,
            deleteModal: false,
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
            newSubject: {
                name: '',
            },
            focusedSubject: {
                id: null,
                name: null,
            },
        }
    },
    watch: {
        form: {
            handler: _.throttle(function () {
                let query = _.pickBy(this.form)
                this.$inertia.replace(this.route('admin.localities', Object.keys(query).length ? query : {remember: 'forget'}))
            }, 150),
            deep: true,
        },
    },
    methods: {
        getThemes(id) {
            this.$inertia.replace(this.route('admin.themes', {subject_id: id}))
        },
        reset() {
            this.form = _.mapValues(this.form, () => null)
        },
        showEditModal(subject) {
            this.editModal = true;
            this.focusedSubject = subject;
        },
        showAddModal() {
            this.addModal = true;
        },
        showDeleteModal(subject) {
            this.deleteModal = true;
            this.focusedSubject = subject;
        },
        add() {
            console.log('ok');
            this.$inertia.post(
                this.route('admin.subjects.store'),
                this.newSubject
            ).then(this.addModal = false)
        },
        edit() {
            this.$inertia.put(
                this.route('admin.subjects.update', this.focusedSubject.id),
                this.focusedSubject
            ).then(this.editModal = false)
        },
        destroy(id) {
            this.$inertia.delete(
                this.route('admin.subjects.destroy', id)
            ).then(this.deleteModal = false)
        },
    },
}
</script>
