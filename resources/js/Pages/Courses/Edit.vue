<template>
  <AppLayout title="Edit Course">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Course
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
                <InputLabel for="thumbnail" value="Thumbnail" />
                <div class="mt-2">
                  <img
                    v-if="course.thumbnail"
                    :src="'/storage/' + course.thumbnail"
                    :alt="course.title"
                    class="w-32 h-32 object-cover rounded-lg mb-2"
                  />
                  <input
                    type="file"
                    id="thumbnail"
                    @change="handleFileUpload"
                    accept="image/*"
                    class="mt-1 block w-full"
                  />
                </div>
                <InputError :message="form.errors.thumbnail" class="mt-2" />
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <Link
                    :href="route('courses.show', course.id)"
                    class="text-gray-600 hover:text-gray-900"
                  >
                    Back to Course
                  </Link>
                </div>
                <div class="flex space-x-4">
                  <SecondaryButton
                    v-if="course.status === 'draft'"
                    @click="submitForApproval"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Submit for Approval
                  </SecondaryButton>
                  <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Update Course
                  </PrimaryButton>
                </div>
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
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  course: Object,
});

const form = useForm({
  title: props.course.title,
  description: props.course.description,
  price: props.course.price,
  thumbnail: null,
  _method: 'PUT',
});

const handleFileUpload = (event) => {
  form.thumbnail = event.target.files[0];
};

const submit = () => {
  form.post(route('courses.update', props.course.id), {
    preserveScroll: true,
  });
};

const submitForApproval = () => {
  form.post(route('courses.submit-for-approval', props.course.id), {
    preserveScroll: true,
  });
};
</script>
