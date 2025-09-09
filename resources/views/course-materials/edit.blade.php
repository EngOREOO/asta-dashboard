@php
    $title = 'تعديل مادة الدورة';
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
          تعديل مادة الدورة
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-file mr-2 text-cyan-500"></i>
          تحديث معلومات مادة الدورة
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="{{ route('course-materials.show', $courseMaterial) }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          عرض
        </a>
        <a href="{{ route('course-materials.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للمواد
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
              <i class="ti ti-file text-white text-xl"></i>
            </div>
            معلومات المادة
          </h2>
          <p class="text-blue-100 mt-2">تحديث بيانات مادة الدورة</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <form action="{{ route('course-materials.update', $courseMaterial) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
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

          <!-- Course and Level Selection -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-book mr-2 text-blue-500"></i>
              اختيار الدورة والمستوى
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">الدورة *</label>
                <select id="course_id" 
                        name="course_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('course_id') border-red-300 @enderror"
                        required>
                  <option value="">اختر الدورة</option>
                  @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $courseMaterial->course_id) == $course->id ? 'selected' : '' }}>
                      {{ $course->title }} @if($course->instructor) - بواسطة {{ $course->instructor->name }} @endif
                    </option>
                  @endforeach
                </select>
                @error('course_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="level_id" class="block text-sm font-medium text-gray-700 mb-2">مستوى الدورة</label>
                <select id="level_id" 
                        name="level_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('level_id') border-red-300 @enderror">
                  <option value="">اختر المستوى (اختياري)</option>
                  @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $courseMaterial->level_id) == $level->id ? 'selected' : '' }}>
                      {{ $level->title }}
                    </option>
                  @endforeach
                </select>
                @error('level_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Material Information -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-info-circle mr-2 text-purple-500"></i>
              معلومات المادة
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div class="lg:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان المادة *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $courseMaterial->title) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('title') border-red-300 @enderror"
                       required>
                @error('title')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع المادة *</label>
                <select id="type" 
                        name="type" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('type') border-red-300 @enderror"
                        required>
                  <option value="">اختر النوع</option>
                  <option value="video" {{ old('type', $courseMaterial->type) == 'video' ? 'selected' : '' }}>فيديو</option>
                  <option value="document" {{ old('type', $courseMaterial->type) == 'document' ? 'selected' : '' }}>مستند</option>
                  <option value="quiz" {{ old('type', $courseMaterial->type) == 'quiz' ? 'selected' : '' }}>اختبار</option>
                  <option value="assignment" {{ old('type', $courseMaterial->type) == 'assignment' ? 'selected' : '' }}>واجب</option>
                </select>
                @error('type')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
            
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
              <textarea id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('description') border-red-300 @enderror">{{ old('description', $courseMaterial->description) }}</textarea>
              @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Content and File -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-upload mr-2 text-green-500"></i>
              المحتوى والملف
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="content_url" class="block text-sm font-medium text-gray-700 mb-2">رابط المحتوى</label>
                <input type="url" 
                       id="content_url" 
                       name="content_url" 
                       value="{{ old('content_url', $courseMaterial->content_url) }}"
                       placeholder="https://..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('content_url') border-red-300 @enderror">
                @error('content_url')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="file_path" class="block text-sm font-medium text-gray-700 mb-2">رفع ملف جديد</label>
                <input type="file" 
                       id="file_path" 
                       name="file_path"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('file_path') border-red-300 @enderror">
                <p class="mt-1 text-sm text-gray-500">الحد الأقصى لحجم الملف: 100 ميجابايت. اتركه فارغاً للاحتفاظ بالملف الحالي.</p>
                @if($courseMaterial->file_path)
                  <div class="mt-2">
                    <p class="text-sm text-green-600">الملف الحالي: {{ basename($courseMaterial->file_path) }}</p>
                  </div>
                @endif
                @error('file_path')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>

          <!-- Settings -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-settings mr-2 text-indigo-500"></i>
              الإعدادات
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
              <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">المدة (بالثواني)</label>
                <input type="number" 
                       id="duration" 
                       name="duration" 
                       value="{{ old('duration', $courseMaterial->duration) }}"
                       min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('duration') border-red-300 @enderror">
                @error('duration')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">الترتيب</label>
                <input type="number" 
                       id="order" 
                       name="order" 
                       value="{{ old('order', $courseMaterial->order) }}"
                       min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('order') border-red-300 @enderror">
                @error('order')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الوصول</label>
                <div class="flex items-center space-x-3 space-x-reverse">
                  <input type="checkbox" 
                         id="is_free" 
                         name="is_free" 
                         value="1" 
                         {{ old('is_free', $courseMaterial->is_free) ? 'checked' : '' }}
                         class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                  <label for="is_free" class="text-sm font-medium text-gray-700">وصول مجاني</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8 border-t border-gray-200">
            <a href="{{ route('course-materials.show', $courseMaterial) }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="ti ti-x mr-2"></i>
              إلغاء
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
              <i class="ti ti-check mr-2"></i>
              تحديث المادة
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
      submitButton.innerHTML = '<i class="ti ti-loader-2 mr-2 animate-spin"></i>جاري التحديث...';
    });
  });
</script>
@endsection