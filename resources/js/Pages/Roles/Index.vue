<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Roles</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-sm mr-4" @reset="reset">
        <label class="block text-grey-darkest">Trashed:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <inertia-link class="btn-indigo" :href="route('admin.roles.create')">
        <span>Create</span>
        <span class="hidden md:inline">Role</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Name</th>
          <th class="px-6 pt-6 pb-4"></th>
        </tr>
        <tr v-for="role in roles.data" :key="role.id" class="hover:bg-grey-lightest focus-within:bg-grey-lightest">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo" :href="route('admin.roles.show', role.id)">
              {{ role.name }}
<!--              <icon v-if="organization.deleted_at" name="trash" class="flex-no-shrink w-3 h-3 fill-grey ml-2" />-->
            </inertia-link>
          </td>
            <td class="border-t w-px">
                <div class="px-4 flex items-center">
                    <div v-for="permission in role.permissions" :key="permission.module" :title="permission.module"
                         class="bg-blue rounded-full mx-2 text-white text-sm px-3 py-2 flex items-center"
                    >
                        {{ permission.lvl }}
                    </div>
                </div>
            </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('admin.roles.show', role.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-grey" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="roles.data.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No organizations found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="roles.links" />
  </div>
</template>

<script>
import _ from 'lodash'
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'

export default {
  metaInfo: { title: 'Roles' },
  layout: (h, page) => h(Layout, [page]),
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  props: {
    roles: Object,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      handler: _.throttle(function() {
        let query = _.pickBy(this.form)
        this.$inertia.replace(this.route('admin.admins', Object.keys(query).length ? query : { remember: 'forget' }))
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = _.mapValues(this.form, () => null)
    },
  },
}
</script>
