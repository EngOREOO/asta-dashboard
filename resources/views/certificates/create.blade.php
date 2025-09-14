@php($title = 'إصدار شهادة')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء شهادة جديدة</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-certificate mr-2 text-cyan-500"></i>
          أدخل بيانات الشهادة ثم احفظ
        </p>
      </div>
      <a href="{{ route('certificates.index') }}" class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300" style="font-size: 1.3rem;">
        <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
        العودة للشهادات
      </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-certificate text-white text-xl"></i>
            </div>
            نموذج إصدار شهادة
          </h2>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        <form method="POST" action="{{ route('certificates.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="font-size: 1.3rem;">
          @csrf

          <!-- Student -->
          <div>
            <label for="user_id" class="block text-gray-700 mb-2">الطالب *</label>
            <select id="user_id" name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 @error('user_id') border-red-300 @enderror" required>
              <option value="">اختر الطالب...</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                  {{ $user->name }} ({{ $user->email }})
                </option>
              @endforeach
            </select>
            @error('user_id')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>

          <!-- Course -->
          <div>
            <label for="course_id" class="block text-gray-700 mb-2">الدورة *</label>
            <select id="course_id" name="course_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 @error('course_id') border-red-300 @enderror" required>
              <option value="">اختر الدورة...</option>
              @foreach($courses as $course)
                <option value="{{ $course->id }}" data-code-prefix="{{ $course->code ?: Str::slug($course->title, '-') }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
              @endforeach
            </select>
            @error('course_id')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
          </div>

          <!-- Certificate Code (auto-generated) -->
          <div id="code-wrapper" class="hidden">
            <label for="code" class="block text-gray-700 mb-2" style="font-family: Arial, sans-serif;">رمز الشهادة</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-family: Arial, sans-serif;" readonly>
            <p class="text-gray-500 mt-1">يتم توليده تلقائياً حسب الدورة.</p>
          </div>

          <!-- Issued at -->
          <div>
            <label for="issued_at" class="block text-gray-700 mb-2" style="font-family: Arial, sans-serif;">تاريخ الإصدار</label>
            <input type="datetime-local" id="issued_at" name="issued_at" value="{{ old('issued_at', now()->format('Y-m-d\\TH:i')) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 @error('issued_at') border-red-300 @enderror" style="font-family: Arial, sans-serif;">
            @error('issued_at')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-gray-500 mt-1">اتركها فارغة لاستخدام التاريخ والوقت الحاليين</p>
          </div>

          {{-- رابط الشهادة (مخفي مؤقتاً) --}}
          {{--
          <div>
            <label for="certificate_url" class="block text-gray-700 mb-2">رابط الشهادة</label>
            <input type="url" id="certificate_url" name="certificate_url" value="{{ old('certificate_url') }}" placeholder="https://example.com/certificate.pdf" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 @error('certificate_url') border-red-300 @enderror">
            @error('certificate_url')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-gray-500 mt-1">اختياري: رابط لتحميل الشهادة</p>
          </div>
          --}}

          <!-- Actions -->
          <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('certificates.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm">إلغاء</a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl shadow hover:shadow-lg hover:scale-105 transition">
              <i class="ti ti-certificate mr-2"></i>
              إصدار الشهادة
            </button>
          </div>
        </form>
        <script>
          (function() {
            const courseSelect = document.getElementById('course_id');
            const codeWrapper = document.getElementById('code-wrapper');
            const codeInput = document.getElementById('code');

            function generateCode(prefix) {
              const n = Math.floor(Math.random() * 1000000);
              const six = String(n).padStart(6, '0');
              return (prefix || 'asta').toLowerCase() + '-' + six;
            }

            function updateCode() {
              const opt = courseSelect.options[courseSelect.selectedIndex];
              const prefix = opt ? (opt.getAttribute('data-code-prefix') || 'asta') : 'asta';
              codeInput.value = generateCode(prefix);
              codeWrapper.classList.remove('hidden');
            }

            if (courseSelect) {
              courseSelect.addEventListener('change', updateCode);
              if (courseSelect.value) {
                updateCode();
              }
            }
          })();
        </script>
      </div>
    </div>
  </div>
</div>
@endsection
