<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">Instructor Applications</h1>
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
          <form @submit.prevent="filterApplications" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="filters.status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Field</label>
              <input
                v-model="filters.field"
                type="text"
                placeholder="Search by field..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
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

      <!-- Applications Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Experience</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="application in applications.data" :key="application.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                          <span class="text-sm font-medium text-gray-700">{{ application.user?.name?.charAt(0) || 'U' }}</span>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ application.user?.name || 'Unknown User' }}</div>
                        <div class="text-sm text-gray-500">{{ application.user?.email || '' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ application.field || 'Not specified' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ application.experience || 'Not specified' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 text-green-800': application.status === 'approved',
                        'bg-yellow-100 text-yellow-800': application.status === 'pending',
                        'bg-red-100 text-red-800': application.status === 'rejected'
                      }"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                    >
                      {{ application.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(application.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button 
                      @click="viewApplication(application)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      View
                    </button>
                    <button 
                      v-if="application.status === 'pending'" 
                      @click="approveApplication(application.id)"
                      class="text-green-600 hover:text-green-900"
                    >
                      Approve
                    </button>
                    <button 
                      v-if="application.status === 'pending'" 
                      @click="rejectApplication(application.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Reject
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="applications.links" class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="applications.prev_page_url"
                :href="applications.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Previous
              </Link>
              <Link
                v-if="applications.next_page_url"
                :href="applications.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Next
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ applications.from }}</span>
                  to
                  <span class="font-medium">{{ applications.to }}</span>
                  of
                  <span class="font-medium">{{ applications.total }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="(link, index) in applications.links"
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

    <!-- Application Details Modal -->
    <div 
      v-if="selectedApplication" 
      class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
      @click="closeModal"
    >
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
          <div class="space-y-3">
            <div>
              <span class="font-medium">Name:</span> {{ selectedApplication.user?.name }}
            </div>
            <div>
              <span class="font-medium">Email:</span> {{ selectedApplication.user?.email }}
            </div>
            <div>
              <span class="font-medium">Field:</span> {{ selectedApplication.field }}
            </div>
            <div>
              <span class="font-medium">Experience:</span> {{ selectedApplication.experience }}
            </div>
            <div>
              <span class="font-medium">Motivation:</span> {{ selectedApplication.motivation || 'Not provided' }}
            </div>
            <div>
              <span class="font-medium">Applied:</span> {{ formatDate(selectedApplication.created_at) }}
            </div>
          </div>
          <div class="flex justify-end space-x-3 mt-6">
            <button 
              @click="closeModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
            >
              Close
            </button>
            <button 
              v-if="selectedApplication.status === 'pending'"
              @click="approveApplication(selectedApplication.id)"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
            >
              Approve
            </button>
            <button 
              v-if="selectedApplication.status === 'pending'"
              @click="rejectApplication(selectedApplication.id)"
              class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
            >
              Reject
            </button>
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
  applications: Object,
  filters: Object,
})

const filters = ref({
  status: props.filters?.status || '',
  field: props.filters?.field || '',
})

const selectedApplication = ref(null)

const filterApplications = () => {
  router.get(route('admin.applications'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  })
}

const viewApplication = (application) => {
  selectedApplication.value = application
}

const closeModal = () => {
  selectedApplication.value = null
}

const approveApplication = (applicationId) => {
  router.post(route('instructor-applications.approve', applicationId), {}, {
    onSuccess: () => {
      closeModal()
      // Refresh the page or show success message
    }
  })
}

const rejectApplication = (applicationId) => {
  if (confirm('Are you sure you want to reject this application?')) {
    router.post(route('instructor-applications.reject', applicationId), {}, {
      onSuccess: () => {
        closeModal()
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