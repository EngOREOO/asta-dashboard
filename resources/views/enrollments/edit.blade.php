@php
    $title = 'تعديل التسجيل';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100">
  <div class="space-y-8 p-6">
    
    <!-- Success/Error Notifications -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-circle text-red-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent">
          تعديل تسجيل الطالب
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-user-check mr-2 text-cyan-500"></i>
          تحديث تفاصيل تسجيل الطالب في الدورة
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('enrollments.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للتسجيلات
        </a>
      </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-user-check text-white text-xl"></i>
            </div>
            تفاصيل التسجيل
          </h2>
          <p class="text-blue-100 mt-2">تحديث معلومات تسجيل الطالب</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <form method="POST" action="{{ route('enrollments.update', [$enrollment->user_id, $enrollment->course_id]) }}" class="space-y-8">
          @csrf
          @method('PUT')
          
          <!-- Validation Errors -->
          @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 animate-shake">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="ti ti-alert-circle text-red-400 text-xl"></i>
                </div>
                <div class="mr-3">
                  <h3 class="text-sm font-medium text-red-800">يرجى تصحيح الأخطاء التالية:</h3>
                  <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          @endif

          <!-- Student Information -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
              <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="ti ti-user mr-2 text-blue-500"></i>
                معلومات الطالب
              </h3>
              
              <div class="space-y-4">
                <div>
                  <label for="student_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الطالب</label>
                  <input type="text" 
                         id="student_name" 
                         name="student_name" 
                         value="{{ old('student_name', $enrollment->student_name) }}"
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                         readonly>
                </div>
                
                <div>
                  <label for="student_email" class="block text-sm font-medium text-gray-700 mb-2 email-label-arabic">البريد الإلكتروني</label>
                  <input type="email" 
                         id="student_email" 
                         name="student_email" 
                         value="{{ old('student_email', $enrollment->student_email) }}"
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                         readonly>
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="ti ti-book mr-2 text-purple-500"></i>
                معلومات الدورة
              </h3>
              
              <div class="space-y-4">
                <div>
                  <label for="course_title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الدورة</label>
                  <input type="text" 
                         id="course_title" 
                         name="course_title" 
                         value="{{ old('course_title', $enrollment->course_title) }}"
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                         readonly>
                </div>
                
                <div>
                  <label for="instructor_name" class="block text-sm font-medium text-gray-700 mb-2">المحاضر</label>
                  <input type="text" 
                         id="instructor_name" 
                         name="instructor_name" 
                         value="{{ old('instructor_name', $enrollment->instructor_name) }}"
                         class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                         readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Progress and Status -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-chart-line mr-2 text-green-500"></i>
              التقدم والحالة
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">نسبة التقدم (%)</label>
                <input type="number" 
                       id="progress" 
                       name="progress" 
                       min="0" 
                       max="100" 
                       value="{{ old('progress', $enrollment->progress ?? 0) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                       required>
                <p class="mt-1 text-sm text-gray-500">أدخل نسبة التقدم من 0 إلى 100</p>
              </div>
              
              <div>
                <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">المسار</label>
                <input type="text" 
                       id="grade" 
                       name="grade" 
                       value="{{ old('grade', $enrollment->grade) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm"
                       placeholder="أدخل المسار (اختياري)">
                <p class="mt-1 text-sm text-gray-500">المسار المحققة في الدورة</p>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-toggle-right mr-2 text-indigo-500"></i>
              حالة التسجيل
            </h3>
            
            <div>
              <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
              <select id="status" 
                   name="status" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm">
                  <option value="not_started" {{ old('status', $enrollment->status ?? '') == 'not_started' ? 'selected' : '' }}>لم تبدأ</option>
                  <option value="in_progress" {{ old('status', $enrollment->status ?? '') == 'in_progress' ? 'selected' : '' }}>قيد التقدم</option>
                  <option value="completed" {{ old('status', $enrollment->status ?? '') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                  <option value="paused" {{ old('status', $enrollment->status ?? '') == 'paused' ? 'selected' : '' }}>متوقفة</option>
              </select>

            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8 border-t border-gray-200">
            <a href="{{ route('enrollments.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="ti ti-x mr-2"></i>
              إلغاء
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
              <i class="ti ti-check mr-2"></i>
              حفظ التغييرات
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
  20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.4s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #2563eb, #7c3aed);
}
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Auto-hide notifications after 5 seconds
    setTimeout(function() {
      const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
      notifications.forEach(notification => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
      });
    }, 5000);

    // Form validation
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
      submitButton.disabled = true;
      submitButton.innerHTML = '<i class="ti ti-loader-2 mr-2 animate-spin"></i>جاري الحفظ...';
    });
  });
</script>
@endsection
