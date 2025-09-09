<script setup>
import DashLayout from '@/Layouts/DashLayout.vue'
import { onMounted } from 'vue'

onMounted(() => {
  // Landing page: Statistics/Analytics from template
})
</script>

<template>
  <DashLayout :user="$page.props.auth.user">
    <!-- Basic Statistics header from template context -->
    <div class="row g-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Dashboard - Analytics</h5>
          </div>
          <div class="card-body">
            <p class="mb-0">Welcome, {{ $page.props.auth.user.name }}.</p>
          </div>
        </div>
      </div>
    </div>
  </DashLayout>
</template>

<style scoped>
/* RTL Support */
[dir="rtl"] {
    direction: rtl;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
