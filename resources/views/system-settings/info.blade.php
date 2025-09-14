@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-info-circle text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">معلومات النظام</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">معلومات التطبيق والخادم</p>
          </div>
        </div>
        <a href="{{ route('system-settings.index') }}" 
           class="bg-white/70 backdrop-blur-xl shadow-lg rounded-2xl px-6 py-3 text-gray-700 font-semibold hover:shadow-xl transition-all duration-300 border border-white/20">
          <i class="ti ti-arrow-right mr-2"></i>العودة للإعدادات
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Application Information -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
        <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-app text-white"></i>
              </div>
              معلومات التطبيق
            </h2>
          </div>
        </div>
        
        <div class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-app mr-2 text-blue-600"></i>اسم التطبيق
              </label>
              <p class="text-gray-700 font-semibold">{{ config('app.name') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-world mr-2 text-green-600"></i>البيئة
              </label>
              <span class="inline-flex items-center px-4 py-2 rounded-xl font-semibold
                {{ config('app.env') === 'production' ? 'bg-green-100 text-green-800' : (config('app.env') === 'staging' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                <i class="ti ti-{{ config('app.env') === 'production' ? 'check-circle' : (config('app.env') === 'staging' ? 'alert-triangle' : 'info-circle') }} mr-2"></i>
                {{ ucfirst(config('app.env')) }}
              </span>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-bug mr-2 text-orange-600"></i>وضع التصحيح
              </label>
              <span class="inline-flex items-center px-4 py-2 rounded-xl font-semibold
                {{ config('app.debug') ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                <i class="ti ti-{{ config('app.debug') ? 'alert-triangle' : 'check-circle' }} mr-2"></i>
                {{ config('app.debug') ? 'مفعل' : 'معطل' }}
              </span>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-link mr-2 text-indigo-600"></i>الرابط
              </label>
              <p class="text-gray-700 font-semibold text-sm break-all">{{ config('app.url') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-clock mr-2 text-purple-600"></i>المنطقة الزمنية
              </label>
              <p class="text-gray-700 font-semibold">{{ config('app.timezone') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-language mr-2 text-teal-600"></i>اللغة
              </label>
              <p class="text-gray-700 font-semibold">{{ config('app.locale') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Server Information -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
        <div class="bg-gradient-to-r from-green-500 via-teal-500 to-cyan-600 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-teal-500/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-server text-white"></i>
              </div>
              معلومات الخادم
            </h2>
          </div>
        </div>
        
        <div class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-brand-php mr-2 text-blue-600"></i>إصدار PHP
              </label>
              <p class="text-gray-700 font-semibold">{{ PHP_VERSION }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-brand-laravel mr-2 text-red-600"></i>إصدار Laravel
              </label>
              <p class="text-gray-700 font-semibold">{{ app()->version() }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-server mr-2 text-green-600"></i>برنامج الخادم
              </label>
              <p class="text-gray-700 font-semibold text-sm">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'غير معروف' }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-globe mr-2 text-indigo-600"></i>مضيف HTTP
              </label>
              <p class="text-gray-700 font-semibold">{{ $_SERVER['HTTP_HOST'] ?? 'غير معروف' }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-folder mr-2 text-purple-600"></i>جذر المستندات
              </label>
              <p class="text-gray-700 font-semibold text-xs break-all">{{ $_SERVER['DOCUMENT_ROOT'] ?? 'غير معروف' }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-network mr-2 text-teal-600"></i>عنوان IP
              </label>
              <p class="text-gray-700 font-semibold">{{ $_SERVER['SERVER_ADDR'] ?? 'غير معروف' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
      <!-- Database Information -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-600 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-database text-white"></i>
              </div>
              معلومات قاعدة البيانات
            </h2>
          </div>
        </div>
        
        <div class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-settings mr-2 text-blue-600"></i>السائق
              </label>
              <p class="text-gray-700 font-semibold">{{ config('database.default') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-server mr-2 text-green-600"></i>المضيف
              </label>
              <p class="text-gray-700 font-semibold">{{ config('database.connections.' . config('database.default') . '.host') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-network mr-2 text-purple-600"></i>المنفذ
              </label>
              <p class="text-gray-700 font-semibold">{{ config('database.connections.' . config('database.default') . '.port') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-database mr-2 text-indigo-600"></i>قاعدة البيانات
              </label>
              <p class="text-gray-700 font-semibold">{{ config('database.connections.' . config('database.default') . '.database') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Cache & Session -->
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-rose-600 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-cpu text-white"></i>
              </div>
              التخزين المؤقت والجلسات
            </h2>
          </div>
        </div>
        
        <div class="p-8 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-cpu mr-2 text-blue-600"></i>سائق التخزين المؤقت
              </label>
              <p class="text-gray-700 font-semibold">{{ config('cache.default') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-user mr-2 text-green-600"></i>سائق الجلسات
              </label>
              <p class="text-gray-700 font-semibold">{{ config('session.driver') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-list mr-2 text-purple-600"></i>سائق الطوابير
              </label>
              <p class="text-gray-700 font-semibold">{{ config('queue.default') }}</p>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-mail mr-2 text-indigo-600"></i>سائق البريد
              </label>
              <p class="text-gray-700 font-semibold">{{ config('mail.default') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- PHP Extensions -->
    <div class="mt-8">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-gray-500 via-gray-600 to-gray-700 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-gray-500/20 to-gray-600/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-brand-php text-white"></i>
              </div>
              إضافات PHP
            </h2>
          </div>
        </div>
        
        <div class="p-8">
          @php
            $extensions = get_loaded_extensions();
            sort($extensions);
            $importantExtensions = ['mysqli', 'pdo', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'curl', 'zip', 'gd', 'json', 'bcmath'];
          @endphp
          
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
            @foreach($importantExtensions as $ext)
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-4 border border-gray-200">
              <div class="flex items-center">
                @if(in_array($ext, $extensions))
                  <i class="ti ti-check text-green-600 mr-3 text-lg"></i>
                  <span class="font-semibold text-gray-800">{{ $ext }}</span>
                @else
                  <i class="ti ti-x text-red-600 mr-3 text-lg"></i>
                  <span class="font-semibold text-red-600">{{ $ext }}</span>
                @endif
              </div>
            </div>
            @endforeach
          </div>
          
          <div class="text-center">
            <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl" 
                    type="button" onclick="toggleExtensions()">
              <i class="ti ti-eye mr-2"></i>عرض جميع الإضافات ({{ count($extensions) }})
            </button>
          </div>
          
          <div id="allExtensions" class="hidden mt-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
              @foreach($extensions as $extension)
              <div class="bg-gray-100 rounded-lg p-2 text-center">
                <span class="text-gray-600 text-sm font-medium">{{ $extension }}</span>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- System Statistics -->
    <div class="mt-8">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-8 py-6 relative overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
          <div class="relative z-10">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                <i class="ti ti-chart-bar text-white"></i>
              </div>
              إحصائيات النظام
            </h2>
          </div>
        </div>
        
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 text-center">
              <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-users text-white text-xl"></i>
              </div>
              <h3 class="text-gray-600 font-semibold mb-2">إجمالي المستخدمين</h3>
              <h2 class="text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</h2>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 border border-green-200 text-center">
              <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-book text-white text-xl"></i>
              </div>
              <h3 class="text-gray-600 font-semibold mb-2">إجمالي الدورات</h3>
              <h2 class="text-3xl font-bold text-green-600">{{ \App\Models\Course::count() }}</h2>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200 text-center">
              <div class="w-12 h-12 bg-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-user-check text-white text-xl"></i>
              </div>
              <h3 class="text-gray-600 font-semibold mb-2">إجمالي التسجيلات</h3>
              <h2 class="text-3xl font-bold text-purple-600">{{ \DB::table('course_user')->count() }}</h2>
            </div>

            <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl p-6 border border-orange-200 text-center">
              <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ti ti-hard-drive text-white text-xl"></i>
              </div>
              <h3 class="text-gray-600 font-semibold mb-2">المساحة المستخدمة</h3>
              @php
                $storageSize = 0;
                if (function_exists('disk_free_space')) {
                  $totalSpace = disk_total_space('/');
                  $freeSpace = disk_free_space('/');
                  $usedSpace = $totalSpace - $freeSpace;
                  $storageSize = round($usedSpace / 1024 / 1024 / 1024, 2);
                }
              @endphp
              <h2 class="text-3xl font-bold text-orange-600">{{ $storageSize ? $storageSize . ' GB' : 'غير معروف' }}</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-slide-up {
  animation: slide-up 0.6s ease-out;
}
</style>

<script>
function toggleExtensions() {
  const extensionsDiv = document.getElementById('allExtensions');
  const button = event.target;
  
  if (extensionsDiv.classList.contains('hidden')) {
    extensionsDiv.classList.remove('hidden');
    button.innerHTML = '<i class="ti ti-eye-off mr-2"></i>إخفاء الإضافات';
  } else {
    extensionsDiv.classList.add('hidden');
    button.innerHTML = '<i class="ti ti-eye mr-2"></i>عرض جميع الإضافات ({{ count($extensions) }})';
  }
}
</script>
@endsection
