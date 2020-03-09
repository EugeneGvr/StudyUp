<template>
    <div class="overflow-hidden">
        <h1 class="mb-8 font-bold text-3xl">{{$t('Subthemes')}}</h1>
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
                <div class="btn-blue mr-2" @click="showAddModal()">
                    <span>{{$t('Add Subtheme')}}</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class=" table-module w-full whitespace-no-wrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">{{$t('Name')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Theme')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Subject')}}</th>
                    <th class="px-6 pt-6 pb-4"></th>
                </tr>
                <tr
                    v-for="subtheme in sub_themes.data" :key="subtheme.id"
                    class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                >
                    <td class="border-t" @click="getQuestions(subtheme)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ subtheme.name }}
                        </div>
                    </td>
                    <td class="border-t" @click="getQuestions(subtheme)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ subtheme.theme_name }}
                        </div>
                    </td>
                    <td class="border-t" @click="getQuestions(subtheme)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ subtheme.subject_name }}
                        </div>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex">
                            <div class="pl-4 pr-2" tabindex="-1" @click="showEditModal(subtheme)">
                                <md-icon>edit</md-icon>
                                <md-tooltip md-direction="top">{{$t('Edit')}}</md-tooltip>
                            </div>
                            <div class="pl-2 pr-2" tabindex="-1" @click="showDeleteModal(subtheme)">
                                <md-icon>delete_outline</md-icon>
                                <md-tooltip md-direction="top">{{$t('Delete')}}</md-tooltip>
                            </div>
                            <div class="px-4" tabindex="-1" @click="getQuestions(subtheme)">
                                <md-icon>keyboard_arrow_right</md-icon>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="sub_themes.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No subthemes found.</td>
                </tr>
            </table>
        </div>
        <pagination :links="sub_themes.links"/>
        <div class="modals">
            <md-dialog :md-active.sync="addModal">
                <md-dialog-title>
                    <span>{{$t('Add new Subtheme')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input v-model="newSubtheme.name" :errors="$page.errors.name" class="pb-8 w-full" :label="$t('Name')"/>
                    <select-input
                        v-model="subject_id"
                        :errors="$page.errors.subject_id"
                        class="pb-3 w-full" :label="$t('Subject')"
                    >
                        <md-option v-for="subject in subjects.data" :key="subject.id" :value="subject.id">
                            {{subject.name}}
                        </md-option>
                    </select-input>
                    <select-input
                        v-if="themes !== null"
                        v-model="newSubtheme.theme_id"
                        :errors="$page.errors.theme_id"
                        class="pb-3 w-full" :label="$t('Theme')"
                    >
                        <md-option v-for="theme in themes" :key="theme.id" :value="theme.id">
                            {{theme.name}}
                        </md-option>
                    </select-input>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="addModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-blue m-2" tabindex="-1" type="button" @click="add">{{$t('Add')}}</button>
                </md-dialog-actions>
            </md-dialog>

            <md-dialog :md-active.sync="editModal">
                <md-dialog-title>
                    <span>{{$t('Edit Subtheme')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input
                        v-model="focusedSubtheme.name"
                        :errors="$page.errors.name"
                        class="pb-8 w-full"
                        :label="$t('Name')"
                    />
                    <select-input
                        v-model="subject_id"
                        :errors="$page.errors.subject_id"
                        class="pb-3 w-full" :label="$t('Subject')"
                    >
                        <md-option v-for="subject in subjects.data" :key="subject.id" :value="subject.id">
                            {{subject.name}}
                        </md-option>
                    </select-input>
                    <select-input
                        v-if="themes !== null"
                        v-model="focusedSubtheme.theme_id"
                        :errors="$page.errors.theme_id"
                        class="pb-3 w-full" :label="$t('Theme')"
                    >
                        <md-option v-for="theme in themes" :key="theme.id" :value="theme.id">
                            {{theme.name}}
                        </md-option>
                    </select-input>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="editModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-blue m-2" tabindex="-1" type="button" @click="edit">{{$t('Edit')}}</button>
                </md-dialog-actions>
            </md-dialog>

            <md-dialog :md-active.sync="deleteModal">
                <md-dialog-title>
                    <span>Are you sure you want to delete dubtheme</span>
                    <span class="text-blue">{{focusedSubtheme.name}}</span>
                    <span>?</span>
                </md-dialog-title>
                <md-dialog-content>
                    <span>This action will drop subtheme</span>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="deleteModal=false">Close</md-button>
                    <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy(focusedSubtheme.id)">
                        Delete
                    </button>
                </md-dialog-actions>
            </md-dialog>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'
    import Icon from '@/Shared/Icon'
    import Layout from '@/Shared/AdminLayout'
    import TextInput from '@/Shared/TextInput'
    import SelectInput from '@/Shared/SelectInput'
    import Pagination from '@/Shared/Pagination'
    import SearchFilter from '@/Shared/SearchFilter'
    import axios from "axios";

    export default {
        metaInfo: {title: 'Subthemes'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            Icon,
            SelectInput,
            Pagination,
            SearchFilter,
            TextInput,
        },
        props: {
            sub_themes: Object,
            filters: Object,
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
                newSubtheme: {
                    name: '',
                    theme_id: '',
                },
                subject_id: null,
                themes: null,
                focusedSubtheme: {
                    id: null,
                    name: null,
                },
            }
        },
        watch: {
            subject_id() {
                this.themes = null;
                this.theme_id = null;
                if(this.subject_id !== null) {
                    axios.get(this.route('api.v1.themes', this.subject_id))
                        .then(response => {
                            this.themes = response.data;
                        })
                        .catch(e => {
                            this.errors.push(e)
                        })
                }
            },
            form: {
                handler: _.throttle(function () {
                    let query = _.pickBy(this.form)
                    this.$inertia.replace(this.route('admin.themes', Object.keys(query).length ? query : {remember: 'forget'}))
                }, 150),
                deep: true,
            },
        },
        methods: {
            getQuestions(subtheme) {
                this.$inertia.replace(this.route('admin.questions', {subtheme_id: subtheme.id}))
            },
            reset() {
                this.form = _.mapValues(this.form, () => null)
            },
            showEditModal(subtheme) {
                this.editModal = true;
                this.subject_id = subtheme.subject_id;
                this.focusedSubtheme = {...subtheme};
            },
            showAddModal() {
                this.newSubtheme.name = '';
                this.newSubtheme.theme_id = null;
                this.subject_id = null;
                this.addModal = true;
            },
            showDeleteModal(subtheme) {
                this.deleteModal = true;
                this.focusedSubtheme = subtheme;
            },
            add() {
                this.$inertia.post(
                    this.route('admin.subthemes.store'),
                    this.newSubtheme
                ).then(this.addyModal = false)
            },
            edit() {
                this.$inertia.put(
                    this.route('admin.subthemes.update', this.focusedSubtheme.id),
                    this.focusedSubtheme
                ).then(this.editModal = false)
            },
            destroy(id) {
                this.$inertia.delete(
                    this.route('admin.subthemes.destroy', id)
                ).then(this.deleteModal = false)
            },
        },
    }
</script>
