<template>
    <div class="overflow-hidden">
        <h1 class="mb-8 font-bold text-3xl">{{$t('Questions')}}</h1>
        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" class="w-full max-w-sm mr-4" @reset="reset">
                <label class="block text-grey-darkest">Trashed:</label>
                <select v-model="form.trashed" class="mt-1 w-full form-select">
                    <option :value="null"/>
                    <option value="with">With Trashed</option>
                    <option value="only">Only Trashed</option>
                </select>
            </search-filter>
            <div class="manage-buttons flex">
                <inertia-link class="btn-blue mr-2" :href="route('admin.questions.create')">
                    <span>{{$t('Add Question')}}</span>
                </inertia-link>
            </div>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class=" table-module w-full whitespace-no-wrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">{{$t('Text')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Subtheme')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Theme')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Subject')}}</th>
                    <th class="px-6 pt-6 pb-4"></th>
                </tr>
                <tr
                    v-for="question in questions.data" :key="question.id"
                    class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                >
                    <td class="border-t">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ question.text }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ question.subtheme }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ question.theme }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ question.subject }}
                        </div>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex">
                            <div class="pl-4 pr-2" tabindex="-1" @click="showEditModal(question)">
                                <md-icon>edit</md-icon>
                                <md-tooltip md-direction="top">{{$t('Edit')}}</md-tooltip>
                            </div>
                            <div class="pl-2 pr-2" tabindex="-1" @click="showDeleteModal(question)">
                                <md-icon>delete_outline</md-icon>
                                <md-tooltip md-direction="top">{{$t('Delete')}}</md-tooltip>
                            </div>
                            <div class="px-4" tabindex="-1">
                                <md-icon>keyboard_arrow_right</md-icon>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="questions.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No questions found.</td>
                </tr>
            </table>
        </div>
        <pagination :links="questions.links"/>
    </div>
</template>

<script>
    import _ from 'lodash'
    import Icon from '@/Shared/Icon'
    import Layout from '@/Shared/Layout'
    import TextInput from '@/Shared/TextInput'
    import SelectInput from '@/Shared/SelectInput'
    import Pagination from '@/Shared/Pagination'
    import SearchFilter from '@/Shared/SearchFilter'

    export default {
        metaInfo: {title: 'Questions'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            Icon,
            SelectInput,
            Pagination,
            SearchFilter,
            TextInput,
        },
        props: {
            questions: Object,
            sub_themes: Object,
            filters: Object,
            themes: Object,
            subjects: Object,
        },
        data() {
            return {
                //[start] modals variables
                addModal: false,
                editModal: false,
                deleteModal: false,
                //[end] modals variables

                form: {
                    search: this.filters.search,
                    trashed: this.filters.trashed,
                },
                newQuestion: {
                    name: '',
                    subtheme_id: '',
                },
                focusedQuestion: {
                    id: null,
                    name: null,
                },
            }
        },
        watch: {
            form: {
                handler: _.throttle(function () {
                    let query = _.pickBy(this.form)
                    this.$inertia.replace(this.route('admin.subthemes', Object.keys(query).length ? query : {remember: 'forget'}))
                }, 150),
                deep: true,
            },
        },
        methods: {
            reset() {
                this.form = _.mapValues(this.form, () => null)
            },
            showEditModal(question) {
                this.editModal = true;
                this.focusedQuestion = question;
            },
            showAddModal() {
                this.addModal = true;
            },
            showDeleteModal(question) {
                this.deleteModal = true;
                this.focusedQuestion = question;
            },
            add() {
                this.$inertia.post(
                    this.route('admin.questions.store'),
                    this.newSubtheme
                ).then(this.addyModal = false)
            },
            edit() {
                this.$inertia.put(
                    this.route('admin.questions.update', this.focusedQuestion.id),
                    this.focusedQuestion
                ).then(this.editModal = false)
            },
            destroy(id) {
                this.$inertia.delete(
                    this.route('admin.question.destroy', id)
                ).then(this.deleteModal = false)
            },
        },
    }
</script>
