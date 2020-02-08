<template>
    <div class="overflow-hidden">
        <h1 class="mb-8 font-bold text-3xl">{{$t('Localities')}}</h1>
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
                <div class="btn-blue mr-2"  @click="showEditModal(currentLocality)">
                    <span>{{$t('Edit Current Locality')}}</span>
                </div>
                <div class="btn-blue mr-2" @click="showAddLocalityModal()">
                    <span>{{$t('Add Locality')}}</span>
                </div>
            </div>
        </div>
        <div class="mb-6 font-bold text-md flex">
            <div
                v-for="(breadcrumbElement, breadcrumb_key) in breadcrumb"
                class="flex"
            >
                <div
                    v-if="!breadcrumbElement.current"
                    class="text-blue hover:text-orange"
                    @click="treeStep(breadcrumbElement)"
                >
                    {{breadcrumbElement.name}}
                </div>
                <div v-else>
                    {{breadcrumbElement.name}}
                </div>
                <div
                    v-if="breadcrumb_key !== (breadcrumb.length - 1)"
                    class="text-blue px-1">
                    /
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class=" table-module w-full whitespace-no-wrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">{{$t('Name')}}</th>
                    <th class="px-6 pt-6 pb-4"></th>
                </tr>
                <tr
                    v-for="locality in localities.data" :key="locality.id"
                    class="hover:bg-grey-lightest focus-within:bg-grey-lightest"
                >
                    <td class="border-t" @click="treeStep(locality)">
                        <div class="px-6 py-4 flex items-center focus:text-indigo">
                            {{ locality.name }}
                        </div>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex">
                        <div class="pl-4 pr-2" tabindex="-1" @click="showEditModal(locality)">
                            <md-icon>edit</md-icon>
                            <md-tooltip md-direction="top">{{$t('Edit')}}</md-tooltip>
                        </div>
                        <div class="pl-2 pr-2" tabindex="-1" @click="showDeleteModal(locality)">
                            <md-icon>delete_outline</md-icon>
                            <md-tooltip md-direction="top">{{$t('Delete')}}</md-tooltip>
                        </div>
                        <div v-if="locality.has_children" class="px-4" tabindex="-1" @click="treeStep(locality)">
                            <md-icon>keyboard_arrow_right</md-icon>
                        </div>
                        </div>
                    </td>
                </tr>
                <tr v-if="localities.data.length === 0">
                    <td class="border-t px-6 py-4" colspan="4">No localities found.</td>
                </tr>
            </table>
        </div>
        <pagination :links="localities.links"/>
        <div class="modals">
            <md-dialog :md-active.sync="addLocalityModal">
                <md-dialog-title>
                    <span>{{$t('Add new Locality')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input v-model="newLocality.name" :errors="$page.errors.name" class="pb-8 w-full" :label="$t('Name')"/>
                    <select-input
                        v-model="this.newLocality.type"
                        :errors="$page.errors.type"
                        class="pb-3 w-full" :label="$t('Type')"
                    >
                        <md-option v-for="(type, type_key) in types" :key="type_key" :value="type_key">
                            {{type}}
                        </md-option>
                    </select-input>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="addLocalityModal=false">{{$t('Close')}}</md-button>
                    <button class="btn-blue m-2" tabindex="-1" type="button" @click="addLocality">{{$t('Add')}}</button>
                </md-dialog-actions>
            </md-dialog>

            <md-dialog :md-active.sync="editModal">
                <md-dialog-title>
                    <span>{{$t('Edit Locality')}}</span>
                </md-dialog-title>
                <md-dialog-content>
                    <text-input
                        v-model="this.focusedLocality.name"
                        :errors="$page.errors.name"
                        class="pb-8 w-full"
                        :label="$t('Name')"
                    />
                    <select-input
                        v-model="this.focusedLocality.type"
                        :errors="$page.errors.type"
                        class="pb-3 w-full" :label="$t('Type')"
                    >
                        <md-option v-for="(type, type_key) in types" :key="type_key" :value="type_key">
                            {{type}}
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
                    <span>Are you sure you want to delete locality</span>
                    <span class="text-blue">{{focusedLocality.name}}</span>
                    <span>?</span>
                </md-dialog-title>
                <md-dialog-content>
                    <span>This action will drop locality and all users who have this locality will get default value</span>
                </md-dialog-content>
                <md-dialog-actions>
                    <md-button class="md-primary p-2 m-2" @click="deleteModal=false">Close</md-button>
                    <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy(focusedLocality.id)">
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
    metaInfo: {title: 'Localities'},
    layout: (h, page) => h(Layout, [page]),
    components: {
        Icon,
        SelectInput,
        Pagination,
        SearchFilter,
        TextInput,
    },
    props: {
        types: Object,
        breadcrumb: Array,
        localities: Object,
        current: Object,
        filters: Object,
    },
    data() {
        return {
            //[start] modals variables
            addLocalityModal: false,
            editModal: false,
            deleteModal: false,
            //[end] modals variables

            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
            },
            newLocality: {
                name: '',
                parent_id: this.current.id,
                type: null,
                center: false,
            },
            currentLocality: {
                id: this.current.id,
                name: this.current.name,
                type: this.current.type,
            },
            focusedLocality: {
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
        treeStep(locality) {
            if (locality.has_children == null || locality.has_children) {
                this.$inertia.replace(this.route('admin.localities', {parent_id: locality.id}))
            } else {
                this.editModal = true;
                this.focusedLocality = locality;
            }
        },
        reset() {
            this.form = _.mapValues(this.form, () => null)
        },
        showEditModal(locality) {
            this.editModal = true;
            this.focusedLocality = locality;
        },
        showAddLocalityModal() {
            this.addLocalityModal = true;
        },
        showDeleteModal(locality) {
            this.deleteModal = true;
            this.focusedLocality = locality;
        },
        addLocality() {
            this.$inertia.post(
                this.route('admin.localities.store'),
                this.newLocality
            ).then(this.addLocalityModal = false)
        },
        edit() {
            this.$inertia.put(
                this.route('admin.localities.update', this.focusedLocality.id),
                this.focusedLocality
            ).then(this.editModal = false)
        },
        destroy(id) {
            this.$inertia.delete(
                this.route('admin.localities.destroy', id)
            ).then(this.deleteModal = false)
        },
    },
}
</script>
