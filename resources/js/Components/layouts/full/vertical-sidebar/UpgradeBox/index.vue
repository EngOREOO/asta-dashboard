<template>
  <v-card class="upgrade-card" elevation="0">
    <v-card-text class="text-center pa-4">
      <v-icon size="48" color="primary" class="mb-3">mdi-star</v-icon>
      <h6 class="text-h6 mb-2">Upgrade to Pro</h6>
      <p class="text-body-2 text-muted mb-3">Get access to all premium features and advanced analytics</p>
      <v-btn color="primary" variant="flat" size="small" block>
        Upgrade Now
      </v-btn>
    </v-card-text>
  </v-card>
</template>

<style scoped>
.upgrade-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.upgrade-card .text-muted {
  color: rgba(255, 255, 255, 0.7) !important;
}
</style>
