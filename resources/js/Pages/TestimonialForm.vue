<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">شاركنا رأيك</h1>
        <p class="text-lg text-gray-600">نريد أن نسمع عن تجربتك مع منصتنا التعليمية</p>
      </div>

      <!-- Testimonial Form -->
      <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="px-6 py-8">
          <form @submit.prevent="submitTestimonial" class="space-y-6">
            <!-- User Name -->
            <div>
              <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">
                اسمك الكامل
              </label>
              <input
                v-model="form.user_name"
                type="text"
                id="user_name"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                :class="{ 'border-red-300 focus:ring-red-500': errors.user_name }"
                placeholder="أدخل اسمك الكامل"
              />
              <p v-if="errors.user_name" class="mt-1 text-sm text-red-600">{{ errors.user_name }}</p>
            </div>

            <!-- User Image -->
            <div>
              <label for="user_image" class="block text-sm font-medium text-gray-700 mb-2">
                صورتك الشخصية (اختياري)
              </label>
              <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <div v-if="imagePreview" class="flex-shrink-0">
                  <img
                    :src="imagePreview"
                    alt="Preview"
                    class="h-16 w-16 rounded-full object-cover border-2 border-gray-200"
                  />
                </div>
                <div class="flex-1">
                  <input
                    @change="handleImageChange"
                    type="file"
                    id="user_image"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                  />
                  <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF حتى 2MB</p>
                </div>
              </div>
              <p v-if="errors.user_image" class="mt-1 text-sm text-red-600">{{ errors.user_image }}</p>
            </div>

            <!-- Rating -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">
                كيف تقيم تجربتك معنا؟
              </label>
              <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                <button
                  v-for="star in 5"
                  :key="star"
                  type="button"
                  @click="form.rating = star"
                  @mouseenter="hoveredStar = star"
                  @mouseleave="hoveredStar = null"
                  class="text-4xl transition-all duration-200 transform hover:scale-110"
                  :class="{
                    'text-yellow-400': star <= (hoveredStar || form.rating),
                    'text-gray-300': star > (hoveredStar || form.rating)
                  }"
                >
                  <i class="ti ti-star-filled"></i>
                </button>
              </div>
              <div class="text-center mt-2">
                <span class="text-sm text-gray-600">
                  {{ getRatingText(form.rating) }}
                </span>
              </div>
              <p v-if="errors.rating" class="mt-1 text-sm text-red-600">{{ errors.rating }}</p>
            </div>

            <!-- Comment -->
            <div>
              <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                شاركنا تجربتك بالتفصيل
              </label>
              <textarea
                v-model="form.comment"
                id="comment"
                rows="5"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                :class="{ 'border-red-300 focus:ring-red-500': errors.comment }"
                placeholder="اكتب لنا عن تجربتك مع الدورات، المحاضرين، أو أي شيء آخر تريد مشاركته..."
              ></textarea>
              <div class="flex justify-between items-center mt-1">
                <p v-if="errors.comment" class="text-sm text-red-600">{{ errors.comment }}</p>
                <p class="text-sm text-gray-500">{{ form.comment.length }}/1000 حرف</p>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
              <button
                type="submit"
                :disabled="isSubmitting || form.comment.length < 10"
                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isSubmitting" class="flex items-center justify-center">
                  <i class="ti ti-loader-2 animate-spin mr-2"></i>
                  جاري الإرسال...
                </span>
                <span v-else class="flex items-center justify-center">
                  <i class="ti ti-send mr-2"></i>
                  إرسال الشهادة
                </span>
              </button>
            </div>

            <!-- Info Note -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
              <div class="flex items-start">
                <i class="ti ti-info-circle text-blue-500 mt-0.5 mr-2"></i>
                <div class="text-sm text-blue-700">
                  <p class="font-medium mb-1">ملاحظة مهمة:</p>
                  <p>ستتم مراجعة شهادتك قبل نشرها على الموقع. نحن نقدر وقتك وثقتك بنا!</p>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="showSuccess" class="mt-6 bg-green-50 border border-green-200 rounded-xl p-6">
        <div class="flex items-center">
          <i class="ti ti-check-circle text-green-500 text-2xl mr-3"></i>
          <div>
            <h3 class="text-lg font-semibold text-green-800">شكراً لك!</h3>
            <p class="text-green-700">تم إرسال شهادتك بنجاح. ستتم مراجعتها ونشرها قريباً.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import axios from 'axios'

const isSubmitting = ref(false)
const showSuccess = ref(false)
const hoveredStar = ref(null)
const errors = ref({})

const form = reactive({
  user_name: '',
  user_image: null,
  rating: 5,
  comment: '',
})

const imagePreview = ref(null)

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.user_image = file
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const getRatingText = (rating) => {
  const texts = {
    1: 'سيء جداً',
    2: 'سيء',
    3: 'متوسط',
    4: 'جيد',
    5: 'ممتاز'
  }
  return texts[rating] || ''
}

const submitTestimonial = async () => {
  isSubmitting.value = true
  errors.value = {}

  try {
    const formData = new FormData()
    formData.append('user_name', form.user_name)
    formData.append('rating', form.rating)
    formData.append('comment', form.comment)
    
    if (form.user_image) {
      formData.append('user_image', form.user_image)
    }

    const response = await axios.post('/api/testimonials', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    if (response.data.success) {
      showSuccess.value = true
      // Reset form
      form.user_name = ''
      form.user_image = null
      form.rating = 5
      form.comment = ''
      imagePreview.value = null
      
      // Scroll to success message
      setTimeout(() => {
        document.querySelector('.bg-green-50').scrollIntoView({ behavior: 'smooth' })
      }, 100)
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error submitting testimonial:', error)
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>
