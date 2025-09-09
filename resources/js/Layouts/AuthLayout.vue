<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div dir="rtl" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 via-blue-50 to-cyan-100 py-12 px-4 sm:px-6 lg:px-8 font-arabic">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <Link href="/" class="flex justify-center items-center">
          <div class="relative">
            <span class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400 blur-md opacity-30"></span>
            <div class="relative bg-white/70 rounded-2xl p-2 shadow-md backdrop-blur">
              <img :src="'/images/asta-logo.png'" alt="ASTA" class="h-12 w-12 object-contain" />
            </div>
          </div>
        </Link>
      </div>

      <div class="p-[1px] rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-500 shadow-xl">
        <div class="bg-white/95 backdrop-blur-sm py-8 px-6 rounded-2xl">
          <slot />
        </div>
      </div>

      <div class="text-center">
        <small class="text-gray-500">&copy; {{ new Date().getFullYear() }} جميع الحقوق محفوظة</small>
      </div>
    </div>
  </div>
</template>
