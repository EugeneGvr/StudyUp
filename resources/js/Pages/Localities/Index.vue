<template>
  <div class="overflow-hidden">
    <h1 class="mb-8 font-bold text-3xl">{{$t('Localities')}}</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-sm mr-4" @reset="reset">
        <label class="block text-grey-darkest">Trashed:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <inertia-link class="btn-blue" :href="route('admin.localities.create')">
        <span>{{$t('Add Locality')}}</span>
      </inertia-link>
    </div>
          <div class="mb-6 font-bold text-md flex">
              <div
                  v-for="(breadcrumbElement, breadcrumb_key) in breadcrumb"
                  class="flex"
              >
                  <div
                      v-if="!breadcrumbElement.current"
                      class="text-blue hover:text-orange"
                      @click="treeStep(breadcrumbElement.parent_id)"
                  >
                      {{breadcrumbElement.title}}
                  </div>
                  <div v-else>
                      {{breadcrumbElement.title}}
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
                  @click="treeStep(locality.id, locality.has_children)"
              >
                  <td class="border-t">
                      <div class="px-6 py-4 flex items-center focus:text-indigo">
                          {{ locality.name }}
                      </div>
                  </td>
                  <td class="border-t w-px">
                      <div v-if="locality.has_children" class="px-4 flex items-center" tabindex="-1">
                          <icon name="cheveron-right" class="block w-6 h-6 fill-grey"/>
                      </div>
                  </td>
              </tr>
              <tr v-if="localities.data.length === 0">
                  <td class="border-t px-6 py-4" colspan="4">No localities found.</td>
              </tr>
          </table>
      </div>
    <pagination :links="localities.links" />
      <md-dialog :md-active.sync="editModal">
          <md-dialog-title>
              <span>Hey</span>
              <span class="text-blue">Hey</span>
              <span>?</span>
          </md-dialog-title>
          <md-dialog-content>
              <span>This action will drop role and all admins who have this role will get default list of permissions</span>
          </md-dialog-content>
          <md-dialog-actions>
              <md-button class="md-primary p-2 m-2" @click="editModal=false">Close</md-button>
              <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy">Delete</button>
          </md-dialog-actions>
      </md-dialog>
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
            editModal: false,
            form: {
                search: this.filters.search,
                trashed: this.filters.trashed,
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
        treeStep(id, goDeep = true) {
            if (goDeep) {
                this.$inertia.replace(this.route('admin.localities', {parent_id: id}))
            } else {
                this.editModal = true;
            }
        },
        reset() {
            this.form = _.mapValues(this.form, () => null)
        },
    },
}
</script>
