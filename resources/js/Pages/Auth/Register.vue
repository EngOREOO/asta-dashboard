<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
  <AuthLayout>
    <Head title="Register" />

    <h4 class="mb-1">Create your account</h4>
    <p class="mb-4 text-muted">Join us to get started</p>

    <div class="d-grid gap-2 mb-3">
      <Link :href="route('socialite.redirect', { provider: 'google' })" class="btn btn-outline-danger">
        Sign up with Google
      </Link>
    </div>

    <div class="text-center my-3 text-muted">or sign up with email</div>

    <form @submit.prevent="submit" novalidate>
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" class="form-control" v-model="form.name" required autofocus autocomplete="name" />
        <div v-if="form.errors.name" class="text-danger small mt-1">{{ form.errors.name }}</div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" class="form-control" v-model="form.email" required autocomplete="username" />
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
          Register
        </button>
      </div>

      <div class="text-center mt-3">
        <small>Already registered? <Link :href="route('login')">Log in</Link></small>
      </div>
    </form>
  </AuthLayout>
</template>
