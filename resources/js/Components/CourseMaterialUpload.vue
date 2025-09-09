<template>
  <div class="space-y-4">
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-medium text-gray-900">Course Materials</h3>
      <button
        @click="showUploadModal = true"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
      >
        Add Material
      </button>
    </div>

    <div class="space-y-4">
      <div
        v-for="material in materials"
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
            <button
              @click="deleteMaterial(material)"
              class="text-red-600 hover:text-red-900"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <Modal :show="showUploadModal" @close="showUploadModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Upload Course Material</h2>
        <form @submit.prevent="uploadMaterial">
          <div class="space-y-4">
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
                rows="3"
              />
              <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div>
              <InputLabel for="type" value="Type" />
              <select
                id="type"
                v-model="form.type"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
              >
                <option value="video">Video</option>
                <option value="pdf">PDF</option>
                <option value="image">Image</option>
                <option value="other">Other</option>
              </select>
              <InputError :message="form.errors.type" class="mt-2" />
            </div>

            <div>
              <InputLabel for="file" value="File" />
              <input
                type="file"
                id="file"
                @change="handleFileUpload"
                :accept="acceptedFileTypes"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.file" class="mt-2" />
            </div>

            <div>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.is_free"
                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                />
                <span class="ml-2 text-sm text-gray-600">Make this material free</span>
              </label>
            </div>

            <div class="flex justify-end space-x-4">
              <SecondaryButton @click="showUploadModal = false">Cancel</SecondaryButton>
              <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
              >
                Upload Material
              </PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
  courseId: {
    type: Number,
    required: true,
  },
  materials: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['material-added', 'material-deleted']);

const showUploadModal = ref(false);

const form = useForm({
  title: '',
  description: '',
  type: 'video',
  file: null,
  is_free: false,
});

const acceptedFileTypes = computed(() => {
  switch (form.type) {
    case 'video':
      return 'video/*';
    case 'pdf':
      return '.pdf';
    case 'image':
      return 'image/*';
    default:
      return '*/*';
  }
});

const handleFileUpload = (event) => {
  form.file = event.target.files[0];
};

const uploadMaterial = () => {
  form.post(route('api.course-materials.store', props.courseId), {
    preserveScroll: true,
    onSuccess: () => {
      showUploadModal.value = false;
      form.reset();
      emit('material-added');
    },
  });
};

const deleteMaterial = (material) => {
  if (confirm('Are you sure you want to delete this material?')) {
    router.delete(route('api.course-materials.destroy', [props.courseId, material.id]), {
      preserveScroll: true,
      onSuccess: () => {
        emit('material-deleted');
      },
    });
  }
};
</script>
