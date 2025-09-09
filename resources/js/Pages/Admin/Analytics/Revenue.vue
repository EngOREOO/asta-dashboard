<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">Revenue Analytics</h1>
          </div>
          <div class="flex items-center space-x-4">
            <Link
              :href="route('admin.dashboard')"
              class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
            >
              Back to Admin Dashboard
            </Link>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Revenue Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ revenueData.total?.toLocaleString() || 0 }}</dd>
                </dl>
              </div>
            </div>
            <div class="mt-5">
              <div class="flex items-center">
                <div 
                  :class="revenueData.growth >= 0 ? 'text-green-500' : 'text-red-500'"
                  class="flex items-center text-sm font-medium"
                >
                  <svg 
                    v-if="revenueData.growth >= 0"
                    class="w-4 h-4 mr-1" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                  </svg>
                  <svg 
                    v-else
                    class="w-4 h-4 mr-1" 
                    fill="currentColor" 
                    viewBox="0 0 20 20"
                  >
                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                  {{ Math.abs(revenueData.growth || 0) }}%
                </div>
                <span class="text-gray-500 text-sm ml-1">from last month</span>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ currentMonthRevenue?.toLocaleString() || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Avg Order Value</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ averageOrderValue || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Transactions</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ totalTransactions || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Revenue Chart -->
      <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
          <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Monthly Revenue Trend</h3>
          <div class="chart-container" style="height: 400px;">
            <!-- Placeholder for chart - you can integrate Chart.js, ApexCharts, etc. -->
            <div class="w-full h-full bg-gradient-to-r from-blue-50 to-indigo-100 rounded-lg flex items-center justify-center">
              <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Revenue Chart</h4>
                <p class="text-gray-500">Monthly revenue visualization would appear here</p>
                <div class="mt-4 grid grid-cols-6 gap-2">
                  <div v-for="(value, index) in revenueData.monthly" :key="index" class="bg-blue-500 rounded" :style="{ height: Math.max(value / 1000, 10) + 'px' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Revenue by Course Category -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Revenue by Category</h3>
            <div class="space-y-4">
              <div v-for="category in topCategories" :key="category.id" class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-3 h-3 rounded-full mr-3" :style="{ backgroundColor: category.color || '#6366f1' }"></div>
                  <span class="text-sm text-gray-900">{{ category.name }}</span>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">${{ category.revenue?.toLocaleString() || 0 }}</div>
                  <div class="text-xs text-gray-500">{{ category.percentage || 0 }}%</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Earning Courses</h3>
            <div class="space-y-4">
              <div v-for="course in topCourses" :key="course.id" class="flex items-center justify-between">
                <div class="flex items-center">
                  <img v-if="course.thumbnail" :src="course.thumbnail" :alt="course.title" class="w-10 h-10 rounded-lg object-cover mr-3" />
                  <div v-else class="w-10 h-10 rounded-lg bg-gray-300 flex items-center justify-center mr-3">
                    <span class="text-xs font-medium text-gray-700">{{ course.title?.charAt(0) || 'C' }}</span>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ course.title }}</div>
                    <div class="text-xs text-gray-500">{{ course.instructor }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <div class="text-sm font-medium text-gray-900">${{ course.revenue?.toLocaleString() || 0 }}</div>
                  <div class="text-xs text-gray-500">{{ course.sales || 0 }} sales</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Transactions -->
      <div class="mt-8">
        <div class="bg-white shadow rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Transactions</h3>
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="transaction in recentTransactions" :key="transaction.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">{{ transaction.student_name || 'Unknown' }}</div>
                      <div class="text-sm text-gray-500">{{ transaction.student_email || '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ transaction.course_title || 'Unknown Course' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      ${{ transaction.amount || 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(transaction.created_at) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="{
                          'bg-green-100 text-green-800': transaction.status === 'completed',
                          'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                          'bg-red-100 text-red-800': transaction.status === 'failed'
                        }"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      >
                        {{ transaction.status || 'completed' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  revenueData: {
    type: Object,
    default: () => ({
      total: 125000,
      monthly: [15000, 18000, 22000, 19000, 25000, 28000, 32000, 35000, 38000, 42000, 45000, 48000],
      growth: 15.4,
    })
  },
  currentMonthRevenue: {
    type: Number,
    default: 48000
  },
  averageOrderValue: {
    type: Number,
    default: 89
  },
  totalTransactions: {
    type: Number,
    default: 1420
  },
  topCategories: {
    type: Array,
    default: () => [
      { id: 1, name: 'Programming', revenue: 45000, percentage: 36, color: '#3B82F6' },
      { id: 2, name: 'Design', revenue: 32000, percentage: 26, color: '#10B981' },
      { id: 3, name: 'Business', revenue: 28000, percentage: 22, color: '#F59E0B' },
      { id: 4, name: 'Marketing', revenue: 20000, percentage: 16, color: '#EF4444' }
    ]
  },
  topCourses: {
    type: Array,
    default: () => [
      { id: 1, title: 'Advanced JavaScript', instructor: 'John Smith', revenue: 15000, sales: 150 },
      { id: 2, title: 'React Fundamentals', instructor: 'Jane Doe', revenue: 12000, sales: 120 },
      { id: 3, title: 'Vue.js Mastery', instructor: 'Mike Johnson', revenue: 10000, sales: 100 }
    ]
  },
  recentTransactions: {
    type: Array,
    default: () => [
      { id: 1, student_name: 'Ahmed Ali', student_email: 'ahmed@example.com', course_title: 'JavaScript Course', amount: 99, status: 'completed', created_at: '2024-01-15T10:30:00Z' },
      { id: 2, student_name: 'Sarah Mohamed', student_email: 'sarah@example.com', course_title: 'React Course', amount: 129, status: 'completed', created_at: '2024-01-14T15:20:00Z' },
      { id: 3, student_name: 'Omar Hassan', student_email: 'omar@example.com', course_title: 'Vue.js Course', amount: 89, status: 'pending', created_at: '2024-01-14T09:15:00Z' }
    ]
  }
})

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.chart-container {
  position: relative;
}
</style>
