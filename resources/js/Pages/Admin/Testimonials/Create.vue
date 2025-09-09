<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">إضافة شهادة جديدة</h1>
          </div>
          <div class="flex items-center space-x-4">
            <Link
              :href="route('testimonials.index')"
              class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
            >
              العودة للقائمة
            </Link>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="bg-white shadow rounded-lg">
        <form @submit.prevent="submitForm" class="p-6 space-y-6">
          <!-- User Name -->
          <div>
            <label for="user_name" class="block text-sm font-medium text-gray-700">اسم المستخدم</label>
            <input
              v-model="form.user_name"
              type="text"
              id="user_name"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.user_name }"
            />
            <p v-if="errors.user_name" class="mt-1 text-sm text-red-600">{{ errors.user_name }}</p>
          </div>

          <!-- User Image -->
          <div>
            <label for="user_image" class="block text-sm font-medium text-gray-700">صورة المستخدم</label>
            <input
              @change="handleImageChange"
              type="file"
              id="user_image"
              accept="image/*"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.user_image }"
            />
            <p v-if="errors.user_image" class="mt-1 text-sm text-red-600">{{ errors.user_image }}</p>
            <p class="mt-1 text-sm text-gray-500">اختياري - الحد الأقصى 2MB</p>
          </div>

          <!-- Rating -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">التقييم</label>
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="form.rating = star"
                class="text-2xl transition-colors duration-200"
                :class="star <= form.rating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300'"
              >
                <i class="ti ti-star-filled"></i>
              </button>
              <span class="mr-2 text-sm text-gray-500">({{ form.rating }}/5)</span>
            </div>
            <p v-if="errors.rating" class="mt-1 text-sm text-red-600">{{ errors.rating }}</p>
          </div>

          <!-- Comment -->
          <div>
            <label for="comment" class="block text-sm font-medium text-gray-700">التعليق</label>
            <textarea
              v-model="form.comment"
              id="comment"
              rows="4"
              required
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.comment }"
              placeholder="اكتب تعليقك هنا..."
            ></textarea>
            <p v-if="errors.comment" class="mt-1 text-sm text-red-600">{{ errors.comment }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ form.comment.length }}/1000 حرف</p>
          </div>

          <!-- Status Options -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="flex items-center">
                <input
                  v-model="form.is_approved"
                  type="checkbox"
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <span class="mr-2 text-sm text-gray-700">معتمدة</span>
              </label>
            </div>
            <div>
              <label class="flex items-center">
                <input
                  v-model="form.is_featured"
                  type="checkbox"
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <span class="mr-2 text-sm text-gray-700">مميزة</span>
              </label>
            </div>
          </div>

          <!-- Sort Order -->
          <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700">ترتيب العرض</label>
            <input
              v-model.number="form.sort_order"
              type="number"
              id="sort_order"
              min="0"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              :class="{ 'border-red-300': errors.sort_order }"
            />
            <p v-if="errors.sort_order" class="mt-1 text-sm text-red-600">{{ errors.sort_order }}</p>
            <p class="mt-1 text-sm text-gray-500">رقم أقل يعني ظهور أولاً</p>
          </div>

          <!-- Submit Buttons -->
          <div class="flex items-center justify-end space-x-4 rtl:space-x-reverse">
            <Link
              :href="route('testimonials.index')"
              class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              إلغاء
            </Link>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50"
            >
              <span v-if="isSubmitting">جاري الحفظ...</span>
              <span v-else>حفظ الشهادة</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'

const props = defineProps({
  errors: Object,
})

const isSubmitting = ref(false)

const form = reactive({
  user_name: '',
  user_image: null,
  rating: 5,
  comment: '',
  is_approved: false,
  is_featured: false,
  sort_order: 0,
})

const handleImageChange = (event) => {
  form.user_image = event.target.files[0]
}

const submitForm = () => {
  isSubmitting.value = true
  
  const formData = new FormData()
  formData.append('user_name', form.user_name)
  formData.append('rating', form.rating)
  formData.append('comment', form.comment)
  formData.append('is_approved', form.is_approved ? 1 : 0)
  formData.append('is_featured', form.is_featured ? 1 : 0)
  formData.append('sort_order', form.sort_order)
  
  if (form.user_image) {
    formData.append('user_image', form.user_image)
  }

  router.post(route('testimonials.store'), formData, {
    onFinish: () => {
      isSubmitting.value = false
    },
    onSuccess: () => {
      router.visit(route('testimonials.index'))
    }
  })
}
</script>
