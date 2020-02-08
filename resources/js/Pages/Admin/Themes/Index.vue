<template>
    <div class="overflow-hidden">
        <h1 class="mb-8 font-bold text-3xl">{{$t('Themes')}}</h1>
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
                    <span>{{$t('Add Theme')}}</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class=" table-module w-full whitespace-no-wrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">{{$t('Name')}}</th>
                    <th class="px-6 pt-6 pb-4">{{$t('Subject')}}</th>
                    <th class="px-6 pt-6 pb-4"></th>
                </tr>
                <tr
                    v-for="theme in themes.data" :key="theme.id"
                    class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                >
                    <td class="border-t" @click="getSubthemes(theme)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ theme.name }}
                        </div>
                    </td>
                    <td class="border-t" @click="getSubthemes(theme)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ theme.subject_id }}
                        </div>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex">
                            <div class="pl-4 pr-2" tabindex="-1" @click="showEditModal(theme)">
                                <md-icon>edit</md-icon>
                                <md-tooltip md-direction="top">{{$t('Edit')}}</md-tooltip>
                            </div>
                            <div class="pl-2 pr-2" tabindex="-1" @click="showDeleteModal(theme)">
                                <md-icon>delete_outline</md-icon>
                                <md-tooltip md-direction="top">{{$t('Delete')}}</md-tooltip>
                            </div>
                            <div class="px-4" tabindex="-1" @click="treeStep(theme)">
                                <md-icon>keyboard_arrow_right</md-icon>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="themes.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No themes found.</td>
                </tr>
            </table>
        </div>
        <pagination :links="themes.links"/>
        <div class="modals">
            <md-dialog :md-active.sync="addModal">
                <md-dialog-title>
                    <span>{{$t('Add new Theme')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input v-model="newTheme.name" :errors="$page.errors.name" class="pb-8 w-full" :label="$t('Name')"/>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="addModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-blue m-2" tabindex="-1" type="button" @click="add">{{$t('Add')}}</button>
                </md-dialog-actions>
            </md-dialog>

            <md-dialog :md-active.sync="editModal">
                <md-dialog-title>
                    <span>{{$t('Edit Theme')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input
                        v-model="this.focusedTheme.name"
                        :errors="$page.errors.name"
                        class="pb-8 w-full"
                        :label="$t('Name')"
                    />
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="editModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-blue m-2" tabindex="-1" type="button" @click="edit">{{$t('Edit')}}</button>
                </md-dialog-actions>
            </md-dialog>

            <md-dialog :md-active.sync="deleteModal">
                <md-dialog-title>
                    <span>Are you sure you want to delete locality</span>
                    <span class="text-blue">{{focusedTheme.name}}</span>
                    <span>?</span>
                </md-dialog-title>
                <md-dialog-content>
                    <span>This action will drop locality and all users who have this locality will get default value</span>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="deleteModal=false">Close</md-button>
                    <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy(focusedTheme.id)">
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
    import Layout from '@/Shared/Layout'
    import TextInput from '@/Shared/TextInput'
    import SelectInput from '@/Shared/SelectInput'
    import Pagination from '@/Shared/Pagination'
    import SearchFilter from '@/Shared/SearchFilter'

    export default {
        metaInfo: {title: 'Themes'},
        layout: (h, page) => h(Layout, [page]),
        components: {
            Icon,
            SelectInput,
            Pagination,
            SearchFilter,
            TextInput,
        },
        props: {
            themes: Object,
            filters: Object,
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
                newTheme: {
                    name: '',
                },
                focusedTheme: {
                    id: null,
                    name: null,
                },
            }
        },
        watch: {
            form: {
                handler: _.throttle(function () {
                    let query = _.pickBy(this.form)
                    this.$inertia.replace(this.route('admin.subjects', Object.keys(query).length ? query : {remember: 'forget'}))
                }, 150),
                deep: true,
            },
        },
        methods: {
            getSubthemes(theme) {
                this.$inertia.replace(this.route('admin.subthemes', {theme_id: theme.id}))
            },
            reset() {
                this.form = _.mapValues(this.form, () => null)
            },
            showEditModal(theme) {
                this.editModal = true;
                this.focusedTheme = theme;
            },
            showAddModal() {
                this.addModal = true;
            },
            showDeleteModal(theme) {
                this.deleteModal = true;
                this.focusedTheme = theme;
            },
            add() {
                this.$inertia.post(
                    this.route('admin.themes.store'),
                    this.newLocality
                ).then(this.addyModal = false)
            },
            edit() {
                this.$inertia.post(
                    this.route('admin.themes.update', this.focusedTheme.id),
                    this.focusedTheme
                ).then(this.editModal = false)
            },
            destroy(id) {
                this.$inertia.delete(
                    this.route('admin.themes.destroy', id)
                ).then(this.deleteModal = false)
            },
        },
    }
</script>
