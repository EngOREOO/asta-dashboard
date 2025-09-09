<template>
  <AppLayout :title="course.title">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ course.title }}
        </h2>
        <div class="flex space-x-4">
          <Link
            v-if="canEdit"
            :href="route('courses.edit', course.id)"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
          >
            Edit Course
          </Link>
          <Link
            :href="route('courses.index')"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
          >
            Back to Courses
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <!-- Course Header -->
          <div class="relative">
            <img
              v-if="course.thumbnail"
              :src="'/storage/' + course.thumbnail"
              :alt="course.title"
              class="w-full h-64 object-cover"
            />
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
              <div class="text-center text-white">
                <h1 class="text-4xl font-bold mb-4">{{ course.title }}</h1>
                <p class="text-xl">By {{ course.instructor.name }}</p>
              </div>
            </div>
          </div>

          <!-- Course Content -->
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Main Content -->
              <div class="md:col-span-2">
                <div class="prose max-w-none">
                  <h3 class="text-2xl font-semibold mb-4">Description</h3>
                  <p class="text-gray-600">{{ course.description }}</p>
                </div>

                <!-- Course Materials -->
                <div class="mt-8">
                  <h3 class="text-2xl font-semibold mb-4">Course Materials</h3>
                  <div class="space-y-4">
                    <div
                      v-for="material in course.materials"
                      :key="material.id"
                      class="bg-gray-50 rounded-lg p-4"
                    >
                      <div class="flex items-center justify-between">
                        <div>
                          <h4 class="text-lg font-medium">{{ material.title }}</h4>
                          <p class="text-sm text-gray-600">{{ material.description }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                          <span
                            :class="{
                              'px-2 py-1 text-xs rounded-full': true,
                              'bg-green-100 text-green-800': material.is_free,
                              'bg-blue-100 text-blue-800': !material.is_free,
                            }"
                          >
                            {{ material.is_free ? 'Free' : 'Premium' }}
                          </span>
                          <span class="text-sm text-gray-500">{{ material.type }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sidebar -->
              <div class="md:col-span-1">
                <div class="bg-gray-50 rounded-lg p-6">
                  <div class="space-y-4">
                    <div>
                      <h4 class="text-lg font-medium">Course Status</h4>
                      <span
                        :class="{
                          'px-2 py-1 text-xs rounded-full': true,
                          'bg-yellow-100 text-yellow-800': course.status === 'draft',
                          'bg-blue-100 text-blue-800': course.status === 'pending',
                          'bg-green-100 text-green-800': course.status === 'approved',
                          'bg-red-100 text-red-800': course.status === 'rejected',
                        }"
                      >
                        {{ course.status }}
                      </span>
                    </div>

                    <div>
                      <h4 class="text-lg font-medium">Price</h4>
                      <p class="text-2xl font-bold">${{ course.price }}</p>
                    </div>

                    <!-- Admin Actions -->
                    <div v-if="isAdmin && course.status === 'pending'" class="space-y-2">
                      <h4 class="text-lg font-medium">Admin Actions</h4>
                      <div class="flex space-x-2">
                        <button
                          @click="approveCourse"
                          class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700"
                        >
                          Approve
                        </button>
                        <button
                          @click="showRejectModal = true"
                          class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700"
                        >
                          Reject
                        </button>
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

    <!-- Reject Modal -->
    <Modal :show="showRejectModal" @close="showRejectModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Reject Course</h2>
        <form @submit.prevent="rejectCourse">
          <div class="mb-4">
            <InputLabel for="rejection_reason" value="Reason for Rejection" />
            <TextArea
              id="rejection_reason"
              v-model="rejectForm.rejection_reason"
              class="mt-1 block w-full"
              rows="4"
              required
            />
            <InputError :message="rejectForm.errors.rejection_reason" class="mt-2" />
          </div>
          <div class="flex justify-end space-x-4">
            <SecondaryButton @click="showRejectModal = false">Cancel</SecondaryButton>
            <PrimaryButton
              :class="{ 'opacity-25': rejectForm.processing }"
              :disabled="rejectForm.processing"
            >
              Reject Course
            </PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  course: Object,
});

const showRejectModal = ref(false);
const isAdmin = $page.props.auth.user.hasRole('admin');
const canEdit = props.course.instructor_id === $page.props.auth.user.id || isAdmin;

const rejectForm = useForm({
  rejection_reason: '',
});

const approveCourse = () => {
  if (confirm('Are you sure you want to approve this course?')) {
    router.post(route('courses.approve', props.course.id));
  }
};

const rejectCourse = () => {
  rejectForm.post(route('courses.reject', props.course.id), {
    preserveScroll: true,
    onSuccess: () => {
      showRejectModal.value = false;
      rejectForm.reset();
    },
  });
};
</script>
