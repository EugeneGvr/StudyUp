<template>
  <div>
    <portal-target name="dropdown" slim />
    <div class="flex flex-col">
      <div class="h-screen flex flex-col" @click="hideDropdownMenus">
        <div class="bg-white border-r border-b md:flex">
          <div class="md:flex-no-shrink md:w-64 px-6 py-4 flex items-center justify-between md:justify-center">
            <inertia-link class="mt-1" href="/">
              <logo width="40" height="40" />
            </inertia-link>
          </div>
          <div class="w-full p-4 md:py-0 md:px-12 text-sm md:text-base flex justify-between items-center">
              <localization :locales="$page.locales" :default="$page.defaultLocale"></localization>
            <dropdown class="mt-1" placement="bottom-end">
              <div class="flex items-center cursor-pointer select-none group">
                <div class="text-grey-darkest group-hover:text-indigo-dark focus:text-indigo-dark mr-1 whitespace-no-wrap">
                  <span>{{ $page.auth.user.first_name }}</span>
                  <span class="hidden md:inline">{{ $page.auth.user.last_name }}</span>
                </div>
                <icon class="w-5 h-5 group-hover:fill-indigo-dark fill-grey-darkest focus:fill-indigo-dark" name="cheveron-down" />
              </div>
              <div slot="dropdown" class="mt-2 py-2 shadow-lg bg-white rounded text-sm">
                <inertia-link class="block px-6 py-2 hover:bg-indigo hover:text-white" :href="route('admin.users.edit', $page.auth.user.id)">My Profile</inertia-link>
                <inertia-link class="block px-6 py-2 hover:bg-indigo hover:text-white" :href="route('admin.users')">Manage Users</inertia-link>
                <inertia-link class="block px-6 py-2 hover:bg-indigo hover:text-white" :href="route('admin.logout')" method="post">Logout</inertia-link>
              </div>
            </dropdown>
          </div>
        </div>
        <div class="flex flex-grow overflow-hidden">
          <div class="w-full overflow-hidden px-4 py-8 md:p-12 overflow-y-auto" scroll-region>
            <flash-messages />
            <slot />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Dropdown from '@/Shared/Dropdown'
import FlashMessages from '@/Shared/FlashMessages'
import Icon from '@/Shared/Icon'
import Logo from '@/Shared/LogoUI'
import MainMenu from '@/Shared/MainMenu'
import Localization from '@/Shared/Localization'

export default {
  components: {
    Dropdown,
    FlashMessages,
    Icon,
    Logo,
    MainMenu,
    Localization,
  },
  data() {
    return {
      showUserMenu: false,
    }
  },
  methods: {
    url() {
      return location.pathname.substr(1)
    },
    hideDropdownMenus() {
      this.showUserMenu = false
    },
  },
}
</script>
