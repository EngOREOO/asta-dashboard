@php($title = $partner->name)
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-building-community text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">{{ $partner->name }}</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">تفاصيل الشريك</p>
          </div>
        </div>
        <div class="flex space-x-3 space-x-reverse">
          <a href="{{ route('partners.edit', $partner) }}" 
             class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="ti ti-edit mr-2"></i>تعديل الشريك
          </a>
          <a href="{{ route('partners.index') }}" 
             class="bg-white/70 backdrop-blur-xl shadow-lg rounded-2xl px-6 py-3 text-gray-700 font-semibold hover:shadow-xl transition-all duration-300 border border-white/20">
            <i class="ti ti-arrow-right mr-2"></i>العودة للشركاء
          </a>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Partner Information Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-info-circle text-white"></i>
                </div>
                معلومات الشريك
              </h2>
            </div>
          </div>
          
          <div class="p-8 space-y-6">
            <!-- Partner Name -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-building mr-2 text-blue-600"></i>اسم الشريك
              </label>
              <p class="text-gray-700 font-semibold" style="font-size: 1.3rem;">{{ $partner->name }}</p>
            </div>

            <!-- Description -->
            @if($partner->description)
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-file-text mr-2 text-green-600"></i>الوصف
              </label>
              <p class="text-gray-700 leading-relaxed">{{ $partner->description }}</p>
            </div>
            @endif

            <!-- Website -->
            @if($partner->website)
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                <i class="ti ti-world mr-2 text-indigo-600"></i>الموقع الإلكتروني
              </label>
              <a href="{{ $partner->website }}" target="_blank" 
                 class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold transition-colors duration-300">
                <i class="ti ti-external-link mr-2"></i>
                {{ $partner->website }}
              </a>
            </div>
            @endif

            <!-- Status and Sort Order -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-toggle-right mr-2 text-teal-600"></i>الحالة
                </label>
                @if($partner->is_active)
                  <span class="inline-flex items-center px-4 py-2 rounded-xl bg-green-100 text-green-800 font-semibold">
                    <i class="ti ti-check-circle mr-2"></i>نشط
                  </span>
                @else
                  <span class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-100 text-gray-800 font-semibold">
                    <i class="ti ti-x-circle mr-2"></i>غير نشط
                  </span>
                @endif
              </div>

              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-sort-ascending mr-2 text-purple-600"></i>ترتيب العرض
                </label>
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-purple-100 text-purple-800 font-semibold">
                  <i class="ti ti-hash mr-2"></i>{{ $partner->sort_order ?? 0 }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Timestamps Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-gray-500 via-gray-600 to-gray-700 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-500/20 to-gray-600/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-clock text-white"></i>
                </div>
                معلومات التوقيت
              </h2>
            </div>
          </div>
          
          <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-calendar-plus mr-2 text-blue-600"></i>تاريخ الإنشاء
                </label>
                <p class="text-gray-700 font-semibold">
                  {{ $partner->created_at ? $partner->created_at->format('Y/m/d - H:i') : 'غير محدد' }}
                </p>
              </div>

              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-calendar-edit mr-2 text-green-600"></i>آخر تحديث
                </label>
                <p class="text-gray-700 font-semibold">
                  {{ $partner->updated_at ? $partner->updated_at->format('Y/m/d - H:i') : 'غير محدد' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Logo Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-photo text-white"></i>
                </div>
                شعار الشريك
              </h2>
            </div>
          </div>
          
          <div class="p-8">
            @if($partner->image)
              <div class="text-center">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                  <img src="{{ asset($partner->image) }}" 
                       alt="{{ $partner->name }}" 
                       class="w-full h-48 object-contain rounded-xl shadow-lg">
                </div>
                <p class="text-gray-600 mt-4 font-semibold">شعار {{ $partner->name }}</p>
              </div>
            @else
              <div class="text-center">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-8 border border-gray-200">
                  <div class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-building text-gray-400 text-3xl"></i>
                  </div>
                  <p class="text-gray-600 font-semibold">لم يتم رفع شعار</p>
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-green-500 via-teal-500 to-cyan-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-teal-500/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-bolt text-white"></i>
                </div>
                الإجراءات السريعة
              </h2>
            </div>
          </div>
          
          <div class="p-8 space-y-4">
            <a href="{{ route('partners.edit', $partner) }}" 
               class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center">
              <i class="ti ti-edit mr-2"></i>تعديل الشريك
            </a>
            
            <a href="{{ route('partners.index') }}" 
               class="w-full bg-white text-gray-700 px-6 py-3 rounded-2xl font-semibold hover:bg-gray-50 transition-all duration-300 border border-gray-300 flex items-center justify-center">
              <i class="ti ti-arrow-right mr-2"></i>العودة للشركاء
            </a>
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
@endsection
