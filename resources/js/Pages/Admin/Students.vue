<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">إدارة الطلاب</h1>
          </div>
          <div class="flex items-center space-x-4">
            <Link
              :href="route('admin.dashboard')"
              class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
            >
              العودة للوحة التحكم
            </Link>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ students.total || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Active Students</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ activeStudents || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Enrolled in Courses</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ totalEnrollments || 0 }}</dd>
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Avg Progress</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ averageProgress || 0 }}%</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
          <form @submit.prevent="filterStudents" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Search</label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search by name or email..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="filters.status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">All Students</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
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

      <!-- Students Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Courses</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="student in students.data" :key="student.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">{{ student.name?.charAt(0) || 'S' }}</span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ student.name }}</div>
                        <div class="text-sm text-gray-500">{{ student.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ student.enrolled_courses_count || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ student.completed_courses_count || 0 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                        <div 
                          class="bg-blue-600 h-2 rounded-full" 
                          :style="{ width: (student.average_progress || 0) + '%' }"
                        ></div>
                      </div>
                      <span class="text-sm text-gray-900">{{ (student.average_progress || 0).toFixed(1) }}%</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(student.last_activity_at) || 'Never' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(student.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button 
                      @click="viewStudent(student)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      View
                    </button>
                    <button class="text-green-600 hover:text-green-900">Progress</button>
                    <button class="text-red-600 hover:text-red-900">Suspend</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="students.links" class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="students.prev_page_url"
                :href="students.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="students.next_page_url"
                :href="students.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ students.from }}</span>
                  to
                  <span class="font-medium">{{ students.to }}</span>
                  of
                  <span class="font-medium">{{ students.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="(link, index) in students.links"
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

    <!-- Student Details Modal -->
    <div 
      v-if="selectedStudent" 
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
      @click="closeModal"
    >
      <div class="relative top-20 mx-auto p-5 border w-2/3 max-w-2xl shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Student Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <span class="font-medium">Name:</span> {{ selectedStudent.name }}
            </div>
            <div>
              <span class="font-medium">Email:</span> {{ selectedStudent.email }}
            </div>
            <div>
              <span class="font-medium">Enrolled Courses:</span> {{ selectedStudent.enrolled_courses_count || 0 }}
            </div>
            <div>
              <span class="font-medium">Completed Courses:</span> {{ selectedStudent.completed_courses_count || 0 }}
            </div>
            <div>
              <span class="font-medium">Average Progress:</span> {{ (selectedStudent.average_progress || 0).toFixed(1) }}%
            </div>
            <div>
              <span class="font-medium">Joined:</span> {{ formatDate(selectedStudent.created_at) }}
            </div>
          </div>
          <div class="flex justify-end space-x-3 mt-6">
            <button 
              @click="closeModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
            >
              Close
            </button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
              View Progress
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Ratings & Testimonials Section -->
    <div class="mt-8">
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">آراء العملاء والتقييمات</h2>
            <Link
              :href="route('admin.testimonials')"
              class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
            >
              إدارة جميع الآراء
            </Link>
          </div>

          <!-- Testimonials Stats -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-message-circle text-2xl text-blue-600"></i>
                </div>
                <div class="mr-3">
                  <p class="text-sm font-medium text-blue-600">إجمالي الآراء</p>
                  <p class="text-2xl font-bold text-blue-900">{{ testimonialsStats.total || 0 }}</p>
                </div>
              </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-check text-2xl text-green-600"></i>
                </div>
                <div class="mr-3">
                  <p class="text-sm font-medium text-green-600">معتمدة</p>
                  <p class="text-2xl font-bold text-green-900">{{ testimonialsStats.approved || 0 }}</p>
                </div>
              </div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-clock text-2xl text-yellow-600"></i>
                </div>
                <div class="mr-3">
                  <p class="text-sm font-medium text-yellow-600">في الانتظار</p>
                  <p class="text-2xl font-bold text-yellow-900">{{ testimonialsStats.pending || 0 }}</p>
                </div>
              </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-star text-2xl text-purple-600"></i>
                </div>
                <div class="mr-3">
                  <p class="text-sm font-medium text-purple-600">متوسط التقييم</p>
                  <p class="text-2xl font-bold text-purple-900">{{ testimonialsStats.average_rating || 0 }}/5</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Testimonials -->
          <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">أحدث آراء العملاء</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="testimonial in recentTestimonials" 
                :key="testimonial.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
              >
                <div class="flex items-start space-x-3 rtl:space-x-reverse">
                  <div class="flex-shrink-0">
                    <img
                      v-if="testimonial.user_image_url"
                      :src="testimonial.user_image_url"
                      :alt="testimonial.user_name"
                      class="h-10 w-10 rounded-full object-cover"
                    />
                    <div
                      v-else
                      class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center"
                    >
                      <span class="text-sm font-medium text-gray-700">{{ testimonial.user_name.charAt(0) }}</span>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-medium text-gray-900">{{ testimonial.user_name }}</p>
                      <div class="flex items-center">
                        <div v-html="getStarsHtml(testimonial.rating)" class="flex"></div>
                        <span class="mr-2 text-sm text-gray-500">({{ testimonial.rating }}/5)</span>
                      </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ testimonial.comment }}</p>
                    <div class="mt-2 flex items-center justify-between">
                      <span class="text-xs text-gray-500">{{ formatDate(testimonial.created_at) }}</span>
                      <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span 
                          v-if="testimonial.is_featured"
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                        >
                          مميزة
                        </span>
                        <span 
                          v-else-if="testimonial.is_approved"
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                        >
                          معتمدة
                        </span>
                        <span 
                          v-else
                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                        >
                          في الانتظار
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
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
  students: Object,
  filters: Object,
  activeStudents: Number,
  totalEnrollments: Number,
  averageProgress: Number,
  testimonialsStats: Object,
  recentTestimonials: Array,
})

const filters = ref({
  search: props.filters?.search || '',
  status: props.filters?.status || '',
})

const selectedStudent = ref(null)

const filterStudents = () => {
  router.get(route('admin.students'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  })
}

const viewStudent = (student) => {
  selectedStudent.value = student
}

const closeModal = () => {
  selectedStudent.value = null
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

const getStarsHtml = (rating) => {
  let stars = ''
  for (let i = 1; i <= 5; i++) {
    if (i <= rating) {
      stars += '<i class="ti ti-star-filled text-yellow-400"></i>'
    } else {
      stars += '<i class="ti ti-star text-gray-300"></i>'
    }
  }
  return stars
}
</script>
