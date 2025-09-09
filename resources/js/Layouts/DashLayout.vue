<template>
  <div class="layout-wrapper layout-content-navbar dashboard-container">
    <div class="layout-container">
      <!-- Sidebar -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <Link :href="route('dashboard')" class="app-brand-link">
            <span class="app-brand-logo demo">
              <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="#7367F0" />
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#7367F0" />
              </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Admin</span>
          </Link>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto" @click.prevent="toggleCollapsed">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboards -->
          <li class="menu-item" :class="{ active: isActive('/dashboard') }">
            <Link :href="route('dashboard')" class="menu-link">
              <i class="menu-icon tf-icons ti ti-smart-home"></i>
              <div>Statistics</div>
            </Link>
          </li>

          <!-- Courses -->
          <li class="menu-item" :class="{ active: startsWith('/courses') }">
            <Link :href="route('courses.index')" class="menu-link">
              <i class="menu-icon tf-icons ti ti-book"></i>
              <div>Courses</div>
            </Link>
          </li>

          <!-- Add New Course -->
          <li class="menu-item" :class="{ active: isActive('/courses/create') }">
            <Link :href="route('courses.create')" class="menu-link">
              <i class="menu-icon tf-icons ti ti-square-plus"></i>
              <div>Add New Course</div>
            </Link>
          </li>

          <!-- Users (admin) -->
          <li v-if="hasRole(['admin'])" class="menu-item" :class="{ active: startsWith('/users') }">
            <Link :href="route('users.index')" class="menu-link">
              <i class="menu-icon tf-icons ti ti-users"></i>
              <div>Users</div>
            </Link>
          </li>
        </ul>
      </aside>

      <!-- Layout page -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
              <div class="nav-item navbar-search-wrapper mb-0">
                <div class="navbar-search d-flex align-items-center">
                  <i class="ti ti-search ti-md me-2"></i>
                  <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                </div>
              </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-primary">{{ initials }}</span>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#" @click.prevent="logout">
                      <i class="ti ti-logout me-2"></i>Logout
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <slot />
          </div>
          <!-- / Content -->
        </div>
      </div>
      <!-- / Layout page -->
    </div>
    <!-- Overlay for small screens -->
    <div class="layout-overlay layout-menu-toggle" @click="closeOverlay"></div>
  </div>
</template>

<script setup>
import '@/dash/assets.js'
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  user: Object
})

const hasRole = (roles) => {
  const usr = props.user || (typeof $page !== 'undefined' && $page.props?.auth?.user) || null
  if (!usr || !usr.roles) return false
  return roles.some(r => usr.roles.some(ur => ur.name === r))
}

const initials = computed(() => {
  const name = (props.user?.name) || ($page?.props?.auth?.user?.name) || ''
  return name.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase()
})

const isActive = (path) => $page?.url === path
const startsWith = (prefix) => ($page?.url || '').startsWith(prefix)

const toggleCollapsed = () => {
  // menu.js handles collapse on body classes; toggler click is enough
}

const closeOverlay = () => {
  // overlay close handled by template scripts, kept for semantics
}

const logout = () => {
  console.log('Logout clicked, attempting to logout...')
  router.post(route('logout'), {}, {
    onSuccess: () => {
      console.log('Logout successful')
    },
    onError: (errors) => {
      console.error('Logout error:', errors)
    }
  })
}
</script>

<style scoped>
/* Keep layout sizing consistent */
.layout-wrapper { min-height: 100vh; }
</style>
