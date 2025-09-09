<script setup>
import { ref } from 'vue'

const notifications = ref([
  {
    title: 'New course added',
    message: 'A new course has been published',
    time: '2 minutes ago',
    icon: 'mdi-book-open-variant'
  },
  {
    title: 'New user registered',
    message: 'A new student has joined the platform',
    time: '5 minutes ago',
    icon: 'mdi-account-plus'
  },
  {
    title: 'Course completed',
    message: 'A student has completed a course',
    time: '10 minutes ago',
    icon: 'mdi-check-circle'
  }
])
</script>

<template>
  <v-menu>
    <template v-slot:activator="{ props }">
      <v-btn
        v-bind="props"
        icon
        variant="flat"
        size="small"
        class="mr-2"
      >
        <v-badge
          :content="notifications.length"
          color="error"
          offset-x="8"
          offset-y="8"
        >
          <v-icon>mdi-bell</v-icon>
        </v-badge>
      </v-btn>
    </template>
    <v-card min-width="300">
      <v-list>
        <v-list-subheader>Notifications</v-list-subheader>
        <v-list-item
          v-for="notification in notifications"
          :key="notification.title"
          :prepend-icon="notification.icon"
        >
          <v-list-item-title>{{ notification.title }}</v-list-item-title>
          <v-list-item-subtitle>{{ notification.message }}</v-list-item-subtitle>
          <template v-slot:append>
            <span class="text-caption text-muted">{{ notification.time }}</span>
          </template>
        </v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>
