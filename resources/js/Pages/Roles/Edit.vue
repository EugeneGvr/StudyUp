<template>
    <div>
        <form @submit.prevent="submit">
            <div class="flex justify-between items-center">
                <h1 class="mb-6 font-bold text-3xl">
                    <inertia-link class="text-blue hover:text-indigo-dark" :href="route('admin.roles')">Roles</inertia-link>
                    <span class="text-indigo-light font-medium">/</span> {{ form.name }}
                </h1>
                <div class="p-3 border-t border-grey-lighter flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo" type="submit">Update Role</loading-button>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <text-input v-model="form.name" :errors="$page.errors.name" class="pb-8 w-full" label="Name"/>
                        <textarea-input v-model="form.description" :errors="$page.errors.description" class="pb-2 w-full" label="Description"/>
                    </div>
                    <div class="bg-white rounded shadow pr-8 pl-8 pt-4 pb-4 m-2">
                        <button class="btn-red" tabindex="-1" type="button" @click="popupActivo=true">Delete</button>
                    </div>
                </div>
                <div class="flex-col lg:w-1/2 sm:w-full">
                    <div class="bg-white rounded shadow p-8 m-2">
                        <div class="mb-5">Permissions</div>
                        <div class="mb-5 flex items-center">
                            <vs-switch class="mr-2" v-model="form.permissions" id="all-permissions" vs-value="all.all" ref="all.all" @click="selectAll(allPermissions)"/>
                            <label for="all-permissions">Select all</label>
                        </div>
                        <div class="ml-5">
                            <div v-for="(permission, p_key) in allPermissions" :key="p_key">
                                <div class="flex items-center">
                                    <vs-switch
                                        class="mr-2"
                                        v-model="form.permissions"
                                        :id="p_key"
                                        :vs-value="p_key"
                                        :ref="p_key"
                                        @click="selectModule(p_key, permission, allPermissions)"
                                    />
                                    <label :for="p_key">{{ p_key | capitalize }}</label>
                                </div>
                                <div class="ml-5 my-4">
                                    <div v-for="(action, a_key) in permission" :key="a_key" class="my-3 flex items-center">
                                        <vs-switch class="mr-2"
                                                   v-model="form.permissions"
                                                   :id="p_key + '-' + action"
                                                   :vs-value="p_key + '.' + action"
                                                   :ref="p_key + '.' + action"
                                                   @click="selectAction(p_key, action, permission, allPermissions)"
                                        />
                                        <label :for="p_key + '-' + action">{{ action | capitalize }} {{ p_key }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="center">
            <vs-popup class="holamundo"  title="Delete" :active.sync="popupActivo">
                <div class="ml-6 mr-6 mt-3 mb-3">
                <span>Are you sure you want to delete role</span><b> {{role.name}}</b><span>?</span>
                </div>
                <div class="float-right">
                <button class="btn-red mr-2 mt-2 mb-2" tabindex="-1" type="button" @click="destroy">Delete</button>
                </div>
            </vs-popup>
        </div>
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
      popupActivo:false,
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
