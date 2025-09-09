<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">Course Management</h1>
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
      <!-- Filters -->
      <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
          <form @submit.prevent="filterCourses" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Search</label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search courses, instructors..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="filters.status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
                <option value="draft">Draft</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Category</label>
              <select
                v-model="filters.category_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">All Categories</option>
                <!-- Add categories dynamically -->
              </select>
            </div>
            <div class="flex items-end">
              <button
                type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                Filter
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Courses Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Language</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="course in courses.data" :key="course.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-12 w-12">
                        <img 
                          v-if="course.thumbnail" 
                          :src="course.thumbnail" 
                          :alt="course.title"
                          class="h-12 w-12 rounded-lg object-cover"
                        />
                        <div v-else class="h-12 w-12 rounded-lg bg-gray-300 flex items-center justify-center">
                          <span class="text-xs font-medium text-gray-700">{{ course.title.charAt(0) }}</span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ course.title }}</div>
                        <div class="text-sm text-gray-500">{{ course.category?.name || 'Uncategorized' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ course.instructor?.name || 'Unknown' }}</div>
                    <div class="text-sm text-gray-500">{{ course.instructor?.email || '' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 text-green-800': course.status === 'approved',
                        'bg-yellow-100 text-yellow-800': course.status === 'pending',
                        'bg-red-100 text-red-800': course.status === 'rejected',
                        'bg-gray-100 text-gray-800': course.status === 'draft'
                      }"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                    >
                      {{ course.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span v-if="course.price > 0">${{ course.price }}</span>
                    <span v-else class="text-green-600 font-medium">Free</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ course.language || 'Arabic' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ course.students_count || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                      </svg>
                      {{ (course.average_rating || 0).toFixed(1) }}
                      <span class="text-gray-500 ml-1">({{ course.total_ratings || 0 }})</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(course.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button class="text-indigo-600 hover:text-indigo-900">View</button>
                    <button 
                      v-if="course.status === 'pending'" 
                      @click="approveCourse(course.id)"
                      class="text-green-600 hover:text-green-900"
                    >
                      Approve
                    </button>
                    <button 
                      v-if="course.status === 'pending'" 
                      @click="rejectCourse(course.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Reject
                    </button>
                    <button class="text-gray-600 hover:text-gray-900">Edit</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="courses.links" class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="courses.prev_page_url"
                :href="courses.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="courses.next_page_url"
                :href="courses.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ courses.from }}</span>
                  to
                  <span class="font-medium">{{ courses.to }}</span>
                  of
                  <span class="font-medium">{{ courses.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="(link, index) in courses.links"
                    :key="index"
                    :href="link.url"
                    :class="{
                      'bg-indigo-50 border-indigo-500 text-indigo-600': link.active,
                      'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                      'cursor-not-allowed opacity-50': !link.url
                    }"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    v-html="link.label"
                  />
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  courses: Object,
  filters: Object,
})

const filters = ref({
  search: props.filters?.search || '',
  status: props.filters?.status || '',
  category_id: props.filters?.category_id || '',
})

const filterCourses = () => {
  router.get(route('admin.courses'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  })
}

const approveCourse = (courseId) => {
  router.post(route('courses.approve', courseId), {}, {
    onSuccess: () => {
      // Refresh the page or show success message
    }
  })
}

const rejectCourse = (courseId) => {
  if (confirm('Are you sure you want to reject this course?')) {
    router.post(route('courses.reject', courseId), {}, {
      onSuccess: () => {
        // Refresh the page or show success message
      }
    })
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}
</script>