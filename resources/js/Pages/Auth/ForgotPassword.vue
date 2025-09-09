<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
  <AuthLayout>
    <Head title="Forgot Password" />

    <h4 class="mb-1">Forgot your password?</h4>
    <p class="mb-4 text-muted">Enter your email and we'll send you a reset link.</p>

    <div v-if="status" class="alert alert-success" role="alert">
      {{ status }}
    </div>

    <form @submit.prevent="submit" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          id="email"
          type="email"
          class="form-control"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />
        <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary" :disabled="form.processing">
          <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
          Email Password Reset Link
        </button>
      </div>
    </form>
  </AuthLayout>
</template>
