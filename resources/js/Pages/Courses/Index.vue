<template>
  <AppLayout title="Courses">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $page.props.auth.user.hasRole('admin') ? 'All Courses' : 'My Courses' }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">Courses</h3>
            <Link
              v-if="$page.props.auth.user.hasRole('instructor')"
              :href="route('courses.create')"
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
              Create Course
            </Link>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="course in courses"
              :key="course.id"
              class="bg-white rounded-lg shadow-md overflow-hidden"
            >
              <img
                v-if="course.thumbnail"
                :src="'/storage/' + course.thumbnail"
                :alt="course.title"
                class="w-full h-48 object-cover"
              />
              <div class="p-4">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">
                  {{ course.title }}
                </h4>
                <p class="text-gray-600 text-sm mb-4">
                  {{ course.description.substring(0, 100) }}...
                </p>
                <div class="flex justify-between items-center">
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
                  <div class="flex space-x-2">
                    <Link
                      :href="route('courses.show', course.id)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      View
                    </Link>
                    <Link
                      v-if="canEdit(course)"
                      :href="route('courses.edit', course.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Edit
                    </Link>
                    <button
                      v-if="canDelete(course)"
                      @click="deleteCourse(course)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  courses: Array,
});

const canEdit = (course) => {
  return (
    course.instructor_id === $page.props.auth.user.id ||
    $page.props.auth.user.hasRole('admin')
  );
};

const canDelete = (course) => {
  return (
    course.instructor_id === $page.props.auth.user.id ||
    $page.props.auth.user.hasRole('admin')
  );
};

const deleteCourse = (course) => {
  if (confirm('Are you sure you want to delete this course?')) {
    router.delete(route('courses.destroy', course.id));
  }
};
</script>
