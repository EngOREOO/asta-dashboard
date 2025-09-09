<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <AuthLayout>
    <Head title="تسجيل الدخول" />

    <div class="space-y-6 font-arabic" dir="rtl">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">مرحباً بك من جديد</h2>
        <p class="mt-2 text-sm text-gray-600">سجّل الدخول للمتابعة إلى حسابك</p>
      </div>

      <div v-if="status" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
        {{ status }}
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 email-label-arabic">البريد الإلكتروني</label>
          <div class="mt-1">
            <input
              id="email"
              type="email"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
              class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-base"
              :class="{ 'border-red-300': form.errors.email }"
            />
          </div>
          <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</div>
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
          <div class="mt-1">
            <input
              id="password"
              type="password"
              v-model="form.password"
              required
              autocomplete="current-password"
              class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-base"
              :class="{ 'border-red-300': form.errors.password }"
            />
          </div>
          <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">{{ form.errors.password }}</div>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember"
              type="checkbox"
              v-model="form.remember"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="remember" class="mr-2 block text-sm text-gray-900">تذكرني</label>
          </div>

          <div class="text-sm">
            <Link
              v-if="canResetPassword"
              :href="route('password.request')"
              class="font-medium text-blue-600 hover:text-blue-500"
            >
              نسيت كلمة المرور؟
            </Link>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="form.processing"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
          >
            <span v-if="form.processing" class="animate-spin h-4 w-4 mr-2">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            تسجيل الدخول
          </button>
        </div>

        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-gray-500">أو المتابعة عبر</span>
            </div>
          </div>

          <!-- <div class="mt-6">
            <Link
              :href="route('socialite.redirect', { provider: 'google' })"
              class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              <span class="mr-2">المتابعة باستخدام جوجل</span>
            </Link>
          </div> -->
        </div>

        <!-- <div class="text-center">
          <span class="text-sm text-gray-600">لا تملك حساباً؟ </span>
          <Link :href="route('register')" class="font-medium text-indigo-600 hover:text-indigo-500">
            إنشاء حساب
          </Link>
        </div> -->
      </form>
    </div>
  </AuthLayout>
</template>
