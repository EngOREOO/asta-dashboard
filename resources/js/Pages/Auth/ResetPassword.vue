<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
  <AuthLayout>
    <Head title="Reset Password" />

    <h4 class="mb-1">Reset your password</h4>
    <p class="mb-4 text-muted">Enter your new password below.</p>

    <form @submit.prevent="submit" novalidate>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" class="form-control" v-model="form.email" required autofocus autocomplete="username" />
        <div v-if="form.errors.email" class="text-danger small mt-1">{{ form.errors.email }}</div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" class="form-control" v-model="form.password" required autocomplete="new-password" />
        <div v-if="form.errors.password" class="text-danger small mt-1">{{ form.errors.password }}</div>
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" class="form-control" v-model="form.password_confirmation" required autocomplete="new-password" />
        <div v-if="form.errors.password_confirmation" class="text-danger small mt-1">{{ form.errors.password_confirmation }}</div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary" :disabled="form.processing">
          <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
          Reset Password
        </button>
      </div>
    </form>
  </AuthLayout>
</template>
