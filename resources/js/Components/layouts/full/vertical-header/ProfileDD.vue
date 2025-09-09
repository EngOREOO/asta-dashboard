<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
</script>

<template>
  <v-menu>
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        variant="flat"
        class="text-none"
      >
        <v-avatar size="32" class="mr-2">
          <v-img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=256&q=80"></v-img>
        </v-avatar>
        {{ user.name }}
        <v-icon class="ml-2">mdi-chevron-down</v-icon>
      </v-btn>
    </template>
    <v-card min-width="200">
      <v-list>
        <v-list-item>
          <v-list-item-title class="text-h6">{{ user.name }}</v-list-item-title>
          <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item prepend-icon="mdi-account" to="/admin/profile">
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>
        <v-list-item prepend-icon="mdi-cog" to="/admin/settings">
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item prepend-icon="mdi-logout" href="/logout" method="post" as="button">
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>
