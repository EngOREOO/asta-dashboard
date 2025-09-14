<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">إدارة الشهادات</h1>
          </div>
          <div class="flex items-center space-x-4">
            <Link
              :href="route('testimonials.create')"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
              إضافة شهادة جديدة
            </Link>
            <Link
              :href="route('dashboard')"
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
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-message-circle text-2xl text-blue-600"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">إجمالي الشهادات</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.total || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-check text-2xl text-green-600"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">معتمدة</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.approved || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-clock text-2xl text-yellow-600"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">في الانتظار</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.pending || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <i class="ti ti-star text-2xl text-purple-600"></i>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">مميزة</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ stats.featured || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
          <form @submit.prevent="filterTestimonials" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">البحث</label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="البحث في الشهادات..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">الحالة</label>
              <select
                v-model="filters.status"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
                <option value="">جميع الحالات</option>
                <option value="approved">معتمدة</option>
                <option value="pending">في الانتظار</option>
                <option value="featured">مميزة</option>
              </select>
            </div>
            <div class="flex items-end">
              <button
                type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              >
                تصفية
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Testimonials Table -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التقييم</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التعليق</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="testimonial in testimonials.data" :key="testimonial.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
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
                      <div class="mr-4">
                        <div class="text-sm font-medium text-gray-900">{{ testimonial.user_name }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div v-html="getStarsHtml(testimonial.rating)" class="flex"></div>
                      <span class="mr-2 text-sm text-gray-500">({{ testimonial.rating }}/5)</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ testimonial.comment }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 text-green-800': testimonial.is_approved,
                        'bg-yellow-100 text-yellow-800': !testimonial.is_approved,
                        'bg-purple-100 text-purple-800': testimonial.is_featured
                      }"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                      {{ getStatusText(testimonial) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(testimonial.created_at) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                      <Link
                        :href="route('testimonials.show', testimonial.id)"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        عرض
                      </Link>
                      <Link
                        :href="route('testimonials.edit', testimonial.id)"
                        class="text-yellow-600 hover:text-yellow-900"
                      >
                        تعديل
                      </Link>
                      <button
                        v-if="!testimonial.is_approved"
                        @click="approveTestimonial(testimonial.id)"
                        class="text-green-600 hover:text-green-900"
                      >
                        اعتماد
                      </button>
                      <button
                        v-if="testimonial.is_approved"
                        @click="rejectTestimonial(testimonial.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        رفض
                      </button>
                      <button
                        @click="toggleFeatured(testimonial.id)"
                        :class="testimonial.is_featured ? 'text-purple-600 hover:text-purple-900' : 'text-gray-600 hover:text-gray-900'"
                      >
                        {{ testimonial.is_featured ? 'إلغاء التمييز' : 'تمييز' }}
                      </button>
                      <button
                        @click="deleteTestimonial(testimonial.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        حذف
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="testimonials.links" class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <Link
                v-if="testimonials.prev_page_url"
                :href="testimonials.prev_page_url"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                السابق
              </Link>
              <Link
                v-if="testimonials.next_page_url"
                :href="testimonials.next_page_url"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                التالي
              </Link>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  عرض
                  <span class="font-medium">{{ testimonials.from }}</span>
                  إلى
                  <span class="font-medium">{{ testimonials.to }}</span>
                  من
                  <span class="font-medium">{{ testimonials.total }}</span>
                  نتيجة
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                  <Link
                    v-for="(link, index) in testimonials.links"
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
import { ref, onMounted } from 'vue'

const props = defineProps({
  testimonials: Object,
  filters: Object,
})

const filters = ref({
  search: props.filters?.search || '',
  status: props.filters?.status || '',
})

const stats = ref({})

const filterTestimonials = () => {
  router.get(route('admin.testimonials.index'), filters.value, {
    preserveState: true,
    preserveScroll: true,
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

const getStatusText = (testimonial) => {
  if (testimonial.is_featured) return 'مميزة'
  if (testimonial.is_approved) return 'معتمدة'
  return 'في الانتظار'
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('ar-SA')
}

const approveTestimonial = (id) => {
  router.post(route('testimonials.approve', id), {}, {
    onSuccess: () => {
      // Refresh the page or update the specific testimonial
      router.reload()
    }
  })
}

const rejectTestimonial = (id) => {
  router.post(route('testimonials.reject', id), {}, {
    onSuccess: () => {
      router.reload()
    }
  })
}

const toggleFeatured = (id) => {
  router.post(route('testimonials.toggle-featured', id), {}, {
    onSuccess: () => {
      router.reload()
    }
  })
}

const deleteTestimonial = (id) => {
  if (confirm('هل أنت متأكد من حذف هذه الشهادة؟')) {
    router.delete(route('testimonials.destroy', id), {
      onSuccess: () => {
        router.reload()
      }
    })
  }
}

const loadStats = async () => {
  try {
    const response = await fetch(route('testimonials.stats'))
    const data = await response.json()
    stats.value = data
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

onMounted(() => {
  loadStats()
})
</script>
