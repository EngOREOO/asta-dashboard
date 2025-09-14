@php($title = 'تسجيل الدخول')
@extends('layouts.auth')
@section('content')
<div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
  <!-- Header -->
  <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
    <div class="relative z-10 text-center">
      <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
        <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA" class="w-10 h-10 object-contain">
      </div>
      <h1 class="font-bold text-white mb-2" style="font-size: 1.9rem;">مرحباً بك مرة أخرى</h1>
      <p class="text-blue-100" style="font-size: 1.3rem;">سجل دخولك للمتابعة</p>
    </div>
    <!-- Decorative elements -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
  </div>
  
  <!-- Form Content -->
  <div class="p-8">
    @if (session('status'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-green-800" style="font-size: 1.3rem;">{{ session('status') }}</p>
        </div>
      </div>
    </div>
    @endif

    @if (session('session_expired'))
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-clock text-yellow-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-yellow-800" style="font-size: 1.3rem;">{{ session('session_expired') }}</p>
        </div>
      </div>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate class="space-y-6">
      @csrf
      
      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 email-label-arabic" style="font-size: 1.3rem;">البريد الإلكتروني</label>
        <div class="relative">
          <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="ti ti-mail text-gray-400"></i>
          </div>
          <input type="email" 
                 id="email" 
                 name="email" 
                 value="{{ old('email') }}" 
                 required 
                 autofocus 
                 autocomplete="username"
                 class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror" 
                 style="font-size: 1.3rem;"
                 placeholder="أدخل بريدك الإلكتروني">
        </div>
        @error('email')
        <p class="mt-2 text-sm text-red-600" style="font-size: 1.3rem;">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2" style="font-size: 1.3rem;">كلمة المرور</label>
        <div class="relative">
          <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="ti ti-lock text-gray-400"></i>
          </div>
          <input type="password" 
                 id="password" 
                 name="password" 
                 required 
                 autocomplete="current-password"
                 class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror" 
                 style="font-size: 1.3rem;"
                 placeholder="أدخل كلمة المرور">
        </div>
        @error('password')
        <p class="mt-2 text-sm text-red-600" style="font-size: 1.3rem;">{{ $message }}</p>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="flex items-center">
        <input type="checkbox" 
               id="remember" 
               name="remember" 
               class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
        <label for="remember" class="mr-2 block text-sm text-gray-700" style="font-size: 1.3rem;">
          تذكرني
        </label>
      </div>

      <!-- Login Button -->
      <button type="submit" 
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm font-medium text-white bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-300 transform hover:scale-105" 
              style="font-size: 1.3rem;">
        <i class="ti ti-login mr-2"></i>
        تسجيل الدخول
      </button>

      <!-- Divider -->
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500" style="font-size: 1.3rem;">أو</span>
        </div>
      </div>

      <!-- Google Login -->
      <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" 
         class="w-full flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-300" 
         style="font-size: 1.3rem;">
        <i class="ti ti-brand-google mr-2 text-red-500"></i>
        المتابعة مع جوجل
      </a>

      <!-- Links -->
      <div class="flex justify-between items-center">
        <a href="{{ route('password.request') }}" 
           class="text-sm text-cyan-600 hover:text-cyan-500 transition-colors duration-200" 
           style="font-size: 1.3rem;">
          نسيت كلمة المرور؟
        </a>
        <a href="{{ route('register') }}" 
           class="text-sm text-cyan-600 hover:text-cyan-500 transition-colors duration-200" 
           style="font-size: 1.3rem;">
          إنشاء حساب جديد
        </a>
      </div>
    </form>
  </div>
</div>

<!-- Footer -->
<div class="text-center mt-6">
  <p class="text-gray-600" style="font-size: 1.3rem;">&copy; {{ date('Y') }} جميع الحقوق محفوظة - ASTA</p>
</div>
@endsection
