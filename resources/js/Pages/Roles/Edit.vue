<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-6 font-bold text-3xl">
                    <inertia-link class="text-blue hover:text-orange" :href="route('admin.roles')">{{$t('Roles')}}</inertia-link>
                    <span class="text-blue font-medium">/</span> {{ form.name }}
                </h1>
                <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-blue" type="submit">{{$t('Update Role')}}</loading-button>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <text-input v-model="form.name" :errors="$page.errors.name" class="pb-8 w-full" :label="$t('Name')"/>
                        <textarea-input v-model="form.description" :errors="$page.errors.description" class="pb-2 w-full" :label="$t('Description')"/>
                    </div>
                    <div class="bg-white rounded shadow pr-8 pl-8 pt-4 pb-4 m-2">
                        <button class="btn-red" tabindex="-1" type="button" @click="deleteModal=true">{{$t('Delete')}}</button>
                    </div>
                </div>
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <div class="mb-5">{{$t('Permissions')}}</div>
                        <div class="mb-5 flex items-center">
                            <md-switch class="md-primary mr-2" v-model="form.permissions" id="all-permissions" value="all.all" @click="selectAll(allPermissions)">
                            {{$t('Select all')}}
                            </md-switch>
                        </div>
                        <div class="ml-5">
                            <div v-for="(permission, p_key) in allPermissions" :key="p_key">
                                <div class="flex items-center">
                                    <md-switch
                                        class="md-primary mr-2"
                                        v-model="form.permissions"
                                        :id="p_key"
                                        :value="p_key"
                                        :ref="p_key"
                                        @click="selectModule(p_key, permission, allPermissions)"
                                    >
                                        {{ $t(p_key) | capitalize }}
                                    </md-switch>
                                </div>
                                <div class="ml-5 my-4">
                                    <div v-for="(action, a_key) in permission" :key="a_key" class="my-3 flex items-center">
                                        <md-switch class="md-primary mr-2"
                                                   v-model="form.permissions"
                                                   :id="p_key + '-' + action"
                                                   :value="p_key + '.' + action"
                                                   @click="selectAction(p_key, action, permission, allPermissions)"
                                        >
                                            {{ $t(action + ' ' + p_key) | capitalize }}
                                        </md-switch>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <md-dialog :md-active.sync="deleteModal">
            <md-dialog-title>
                <span>Are you sure you want to delete role</span>
                <span class="text-blue">{{role.name}}</span>
                <span>?</span>
            </md-dialog-title>
            <md-dialog-content>
                <span>This action will drop role and all admins who have this role will get default list of permissions</span>
            </md-dialog-content>
            <md-dialog-actions>
                <md-button class="md-primary p-2 m-2" @click="deleteModal=false">Close</md-button>
                <button class="btn-red m-2" tabindex="-1" type="button" @click="destroy">Delete</button>
            </md-dialog-actions>
        </md-dialog>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'

export default {
  metaInfo() {
    return { title: this.form.name }
  },
  layout: (h, page) => h(Layout, [page]),
  components: {
    LoadingButton,
    TextInput,
    TextareaInput,
  },
    filters: {
        capitalize: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        }
    },
  props: {
    role: Object,
    allPermissions: Object,
  },
  remember: 'form',
  data() {
    return {
      deleteModal:false,
      sending: false,
      form: {
        name: this.role.name,
        description: this.role.description,
        permissions: this.role.permissions,
      },
    }
  },
  methods: {
      submit() {
          this.sending = true
          this.$inertia.put(this.route('admin.roles.update', this.role.id), this.form)
              .then(() => this.sending = false)
      },
      destroy() {
          this.$inertia.delete(this.route('admin.roles.destroy', this.role.id)).then(this.popupActivo = false)
      },
      selectAction(module, action, permission, permissions) {
          const moduleLength = this.form.permissions.filter(permission => permission.split('.')[0] == module).length + 1;
          if (this.$refs[module + '.' + action][0].isChecked === false && permission.length === moduleLength) {
              this.form.permissions.push(module)
          } else {
              const index = this.form.permissions.indexOf(module);
              if (index > -1) {
                  this.form.permissions.splice(index, 1);
              }
          }

          let counter = 0;
          for (const module in permissions) {
              counter++;
              for (const action of permissions[module]) {
                  counter++;
              }
          }
          if (this.$refs['all.all'].isChecked == false && (counter === this.form.permissions.length + 1)) {
              this.form.permissions.push('all.all');
          } else {
              const indexModule = this.form.permissions.indexOf('all.all');
              if (indexModule > -1) {
                  this.form.permissions.splice(indexModule, 1);
              }
          }
      },
      selectModule(module, permission, permissions) {
          for (const action of permission) {
              if (this.$refs[module][0].isChecked) {
                  const index = this.form.permissions.indexOf(module + '.' + action);

                  if (index > -1) {
                      this.form.permissions.splice(index, 1);
                  }
              } else {
                  if (this.form.permissions.indexOf(module + '.' + action) === -1) {
                      this.form.permissions.push(module + '.' + action);
                  }
              }
          }
      },
      selectAll(permissions) {
          for (const module in permissions) {

              for (const action of permissions[module]) {
                  if (this.$refs['all.all'].isChecked) {
                      const indexAction = this.form.permissions.indexOf(module + '.' + action);

                      if (indexAction > -1) {
                          this.form.permissions.splice(indexAction, 1);
                      }
                      const indexModule = this.form.permissions.indexOf(module);

                      if (indexModule > -1) {
                          this.form.permissions.splice(indexModule, 1);
                      }
                  } else {
                      if (this.form.permissions.indexOf(module) === -1) {
                          this.form.permissions.push(module);
                      }

                      if (this.form.permissions.indexOf(module + '.' + action) === -1) {
                          this.form.permissions.push(module + '.' + action);
                      }
                  }
              }
          }
      },
  },
}
</script>
