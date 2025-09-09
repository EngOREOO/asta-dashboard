<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

const userMenuList = [
  {
    type: 'navItem',
    title: 'Profile',
    icon: 'tabler-user',
    to: { name: 'admin.profile' },
  },
  {
    type: 'navItem',
    title: 'Settings',
    icon: 'tabler-settings',
    to: { name: 'admin.settings.general' },
  },
  {
    type: 'divider',
  },
  {
    type: 'navItem',
    title: 'Logout',
    icon: 'tabler-logout',
    to: { name: 'logout' },
  },
]
</script>

<template>
  <VMenu
    location="bottom end"
    offset="8px"
    transition="scale-transition"
  >
    <template #activator="{ props }">
      <VBtn
        class="profile-btn custom-button__content"
        variant="text"
        v-bind="props"
      >
        <VAvatar
          size="32"
          class="me-2"
        >
          <VImg
            :src="user.avatar || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=32&q=80'"
          />
        </VAvatar>
        <span class="d-none d-md-block">{{ user.name }}</span>
        <VIcon
          icon="tabler-chevron-down"
          class="ms-1"
        />
      </VBtn>
    </template>

    <VCard min-width="200">
      <VList>
        <VListItem
          v-for="item in userMenuList"
          :key="item.title"
          :to="item.to"
          :value="item.title"
        >
          <template #prepend>
            <VIcon
              v-if="item.icon"
              :icon="item.icon"
              size="20"
            />
          </template>
          <VListItemTitle>{{ item.title }}</VListItemTitle>
        </VListItem>
      </VList>
    </VCard>
  </VMenu>
</template>

<style lang="scss" scoped>
.profile-btn {
  text-transform: none;
}
</style>
