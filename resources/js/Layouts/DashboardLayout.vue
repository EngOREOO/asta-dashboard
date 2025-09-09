<template>
  <div class="min-h-screen bg-gray-50 text-gray-800">
    <div class="flex h-screen">
      <!-- Sidebar -->
      <aside
        :class="[
          'bg-white border-r border-gray-200 w-72 shrink-0 flex flex-col transition-transform duration-200 ease-in-out',
          sidebarOpen ? 'translate-x-0' : '-translate-x-72 lg:translate-x-0'
        ]"
      >
        <div class="h-16 px-4 flex items-center justify-between border-b">
          <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-md bg-indigo-600"></div>
            <span class="font-semibold">Admin</span>
          </div>
          <button class="lg:hidden p-2" @click="sidebarOpen=false" aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-3 space-y-6">
          <div>
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">General</p>
            <ul class="mt-2 space-y-1">
              <li>
                <Link :href="route('dashboard')" class="group flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"
                      :class="{ 'bg-indigo-50 text-indigo-700': $page.url.startsWith('/dashboard') }">
                  <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                  <span>Dashboard</span>
                </Link>
              </li>
              <li>
                <Link :href="route('courses.index')" class="group flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"
                      :class="{ 'bg-indigo-50 text-indigo-700': $page.url.startsWith('/courses') }">
                  <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5 12.083 12.083 0 015.84 10.578L12 14z"/></svg>
                  <span>Courses</span>
                </Link>
              </li>
              <li v-if="hasRole(['admin','instructor'])">
                <Link :href="route('assessments.index')" class="group flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"
                      :class="{ 'bg-indigo-50 text-indigo-700': $page.url.startsWith('/assessments') }">
                  <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V3a1 1 0 112 0v8m-2 10a1 1 0 102 0v-4a1 1 0 10-2 0v4z"/></svg>
                  <span>Assessments</span>
                </Link>
              </li>
              <li v-if="hasRole(['admin'])">
                <Link :href="route('users.index')" class="group flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100"
                      :class="{ 'bg-indigo-50 text-indigo-700': $page.url.startsWith('/users') }">
                  <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4a2 2 0 00-2-2H4a2 2 0 00-2 2v16h5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a5 5 0 100-10 5 5 0 000 10z"/></svg>
                  <span>Users</span>
                </Link>
              </li>
            </ul>
          </div>

          <div>
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Applications</p>
            <ul class="mt-2 space-y-1">
              <li><a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#"><span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>Project</a></li>
              <li><a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#">File Manager</a></li>
              <li><a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#">Kanban</a></li>
              <li><a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100" href="#">Ecommerce</a></li>
            </ul>
          </div>
        </nav>

        <div class="p-4 border-t">
          <div class="bg-indigo-50 rounded-xl p-4 text-sm">
            <p class="font-semibold">Experience with more Features</p>
            <button class="mt-3 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-2 text-white text-xs">Check now
            </button>
          </div>
        </div>
      </aside>

      <!-- Content area -->
      <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6">
          <div class="flex items-center gap-3 min-w-0">
            <button class="lg:hidden p-2 rounded-md hover:bg-gray-100" @click="sidebarOpen=true" aria-label="Open sidebar">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="relative flex-1 max-w-xl">
              <input type="text" placeholder="Search here..." class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <button class="p-2 rounded-full hover:bg-gray-100" aria-label="Notifications">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </button>
            <div class="ml-1">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-gray-100">
                    <div class="h-8 w-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 text-sm font-semibold">{{ initials }}</div>
                    <div class="hidden md:block text-left">
                      <div class="text-xs text-gray-500">{{ user?.email }}</div>
                      <div class="text-sm font-medium">{{ $page.props.auth.user.name }}</div>
                    </div>
                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.958a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0l-4.24-4.52a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                  </button>
                </template>
                <template #content>
                  <form @submit.prevent="logout">
                    <DropdownLink as="button">Logout</DropdownLink>
                  </form>
                </template>
              </Dropdown>
            </div>
          </div>
        </header>

        <!-- Main -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
          <!-- Breadcrumb + Title -->
          <div class="mb-4">
            <nav class="text-sm text-gray-400" aria-label="Breadcrumb">
              <ol class="inline-flex items-center gap-2">
                <li><a class="hover:text-gray-600" href="#">Dashboard</a></li>
                <li>/</li>
                <li class="text-gray-500">Default</li>
              </ol>
            </nav>
            <h1 class="mt-2 text-xl font-semibold">Default Dashboard</h1>
          </div>

          <!-- Optional header slot; if not provided show hero/welcome + overview cards -->
          <slot name="page-header">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
              <div class="col-span-1 xl:col-span-2">
                <div class="rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-400 text-white p-6 flex items-center gap-6">
                  <div class="hidden sm:block">
                    <div class="h-24 w-24 rounded-2xl bg-white/20"></div>
                  </div>
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold">Welcome, {{ $page.props.auth.user.name }}</h3>
                    <p class="mt-1 text-indigo-50 text-sm">You have completed 40% of your goals this week! Start a new goal & improve your result.</p>
                    <button class="mt-4 inline-flex items-center rounded-lg bg-white/90 px-3 py-2 text-indigo-700 text-sm font-medium hover:bg-white">Continue →</button>
                  </div>
                </div>
              </div>
              <div class="col-span-1 grid grid-cols-1 gap-4">
                <div class="rounded-2xl bg-white border border-gray-200 p-4">
                  <div class="flex items-center justify-between">
                    <h4 class="font-medium">Yearly Overview</h4>
                    <span class="text-xs text-gray-400">50/100</span>
                  </div>
                  <div class="mt-4 h-24 bg-gradient-to-t from-gray-100 to-white rounded-lg flex items-end">
                    <div class="w-full h-2 rounded bg-indigo-100"></div>
                  </div>
                </div>
                <div class="rounded-2xl bg-white border border-gray-200 p-4">
                  <h4 class="font-medium">Activity</h4>
                  <ul class="mt-3 space-y-2 text-sm">
                    <li class="flex items-center gap-3"><span class="h-8 w-8 rounded-full bg-gray-100"></span><span>Review request from Jim</span><span class="ml-auto text-xs text-gray-400">12m</span></li>
                    <li class="flex items-center gap-3"><span class="h-8 w-8 rounded-full bg-gray-100"></span><span>New contact added</span><span class="ml-auto text-xs text-gray-400">32m</span></li>
                    <li class="flex items-center gap-3"><span class="h-8 w-8 rounded-full bg-gray-100"></span><span>Sent review</span><span class="ml-auto text-xs text-gray-400">1h</span></li>
                  </ul>
                </div>
              </div>
            </div>
          </slot>

          <!-- Main content slot -->
          <div class="mt-6">
            <slot></slot>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const props = defineProps({
  user: Object
})

const sidebarOpen = ref(false)

const hasRole = (roles) => {
  if (!props.user || !props.user.roles) return false
  return roles.some(role => props.user.roles.some(userRole => userRole.name === role))
}

const initials = computed(() => {
  const name = (typeof $page !== 'undefined' && $page.props?.auth?.user?.name) || ''
  return name.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase()
})

const user = computed(() => props.user || $page?.props?.auth?.user || null)

const logout = () => {
  router.post(route('logout'))
}
</script>
