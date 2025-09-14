<template>
  <AppLayout title="Create Course">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Course
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="space-y-6">
              <div>
                <InputLabel for="title" value="Title" />
                <TextInput
                  id="title"
                  v-model="form.title"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.title" class="mt-2" />
              </div>

              <div>
                <InputLabel for="description" value="Description" />
                <TextArea
                  id="description"
                  v-model="form.description"
                  class="mt-1 block w-full"
                  rows="4"
                  required
                />
                <InputError :message="form.errors.description" class="mt-2" />
              </div>

              <div>
                <InputLabel for="price" value="Price" />
                <TextInput
                  id="price"
                  v-model="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.price" class="mt-2" />
              </div>

              <div>
                <InputLabel for="instructor_id" value="Instructor" />
                <select
                  id="instructor_id"
                  v-model="form.instructor_id"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  required
                >
                  <option value="">Select Instructor</option>
                  <option v-for="instructor in instructors" :key="instructor.id" :value="instructor.id">
                    {{ instructor.name }}
                  </option>
                </select>
                <InputError :message="form.errors.instructor_id" class="mt-2" />
              </div>

              <div>
                <InputLabel for="duration_days" value="Course Duration (Days)" />
                <TextInput
                  id="duration_days"
                  v-model="form.duration_days"
                  type="number"
                  min="1"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.duration_days" class="mt-2" />
              </div>

              <div>
                <InputLabel for="awarding_institution" value="Awarding Institution" />
                <TextInput
                  id="awarding_institution"
                  v-model="form.awarding_institution"
                  type="text"
                  placeholder="e.g., King Saud University"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.awarding_institution" class="mt-2" />
              </div>

              <div>
                <InputLabel for="status" value="Status" />
                <select
                  id="status"
                  v-model="form.status"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                >
                  <option value="draft">Draft</option>
                  <option value="pending">Pending Approval</option>
                  <option v-if="isAdmin" value="approved">Approved</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
              </div>

              <div>
                <InputLabel for="thumbnail" value="Thumbnail" />
                <input
                  type="file"
                  id="thumbnail"
                  @change="handleFileUpload"
                  accept="image/*"
                  class="mt-1 block w-full"
                />
                <InputError :message="form.errors.thumbnail" class="mt-2" />
              </div>

              <div class="flex items-center justify-end">
                <Link
                  :href="route('courses.index')"
                  class="mr-4 text-gray-600 hover:text-gray-900"
                >
                  Cancel
                </Link>
                <PrimaryButton
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Create Course
                </PrimaryButton>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
  instructors: Array,
  isAdmin: {
    type: Boolean,
    default: false
  }
});

const form = useForm({
  title: '',
  description: '',
  price: '',
  instructor_id: '',
  duration_days: '',
  awarding_institution: '',
  status: props.isAdmin ? 'approved' : 'pending',
  thumbnail: null,
});

const handleFileUpload = (event) => {
  form.thumbnail = event.target.files[0];
};

const submit = () => {
  form.post(route('courses.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
  });
};
</script>
