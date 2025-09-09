@php
    $title = 'تعديل مسار التعلم';
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
          تعديل مسار التعلم
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-route mr-2 text-cyan-500"></i>
          تحديث معلومات مسار التعلم
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('learning-paths.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للمسارات
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
              <i class="ti ti-route text-white text-xl"></i>
            </div>
            معلومات المسار
          </h2>
          <p class="text-blue-100 mt-2">تحديث بيانات مسار التعلم</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <form method="POST" action="{{ route('learning-paths.update', $learning_path) }}" class="space-y-8">
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

          <!-- Basic Information -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-info-circle mr-2 text-blue-500"></i>
              المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المسار *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $learning_path->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('name') border-red-300 @enderror"
                       required>
                @error('name')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب الفرز</label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', $learning_path->sort_order) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('sort_order') border-red-300 @enderror">
                @error('sort_order')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
            
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
              <textarea id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('description') border-red-300 @enderror">{{ old('description', $learning_path->description) }}</textarea>
              @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Status -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-toggle-right mr-2 text-purple-500"></i>
              حالة المسار
            </h3>
            
            <div>
              <div class="flex items-center space-x-3 space-x-reverse">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1" 
                       {{ old('is_active', $learning_path->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                <label for="is_active" class="text-sm font-medium text-gray-700">نشط</label>
              </div>
              <p class="mt-1 text-sm text-gray-500">تفعيل أو إلغاء تفعيل مسار التعلم</p>
            </div>
          </div>

          <!-- Courses Selection -->
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-book mr-2 text-green-500"></i>
              اختيار الدورات
            </h3>
            
            <div>
              <label for="course_ids" class="block text-sm font-medium text-gray-700 mb-2">الدورات</label>
              @php($selected = collect(old('course_ids', $learning_path->courses->pluck('id')->all())))
              
              <!-- Custom Multi-Select Container -->
              <div class="relative">
                <div id="course-selector" 
                     class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm cursor-pointer @error('course_ids') border-red-300 @enderror"
                     onclick="toggleCourseDropdown()">
                  <div class="flex items-center justify-between">
                    <div class="flex flex-wrap gap-2" id="selected-courses-display">
                      @if($selected->count() > 0)
                        @foreach($selected as $courseId)
                          @php($course = $courses->firstWhere('id', $courseId))
                          @if($course)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                              {{ $course->title }}
                              <button type="button" 
                                      class="mr-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 transition-colors duration-200"
                                      onclick="removeCourse({{ $courseId }}, event)">
                                <i class="ti ti-x text-xs"></i>
                              </button>
                            </span>
                          @endif
                        @endforeach
                      @else
                        <span class="text-gray-500">اختر الدورات</span>
                      @endif
                    </div>
                    <i class="ti ti-chevron-down text-gray-400 transition-transform duration-200" id="dropdown-icon"></i>
                  </div>
                </div>
                
                <!-- Hidden select for form submission -->
                <select id="course_ids" 
                        name="course_ids[]" 
                        multiple 
                        class="hidden">
                  @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected($selected->contains($course->id))>{{ $course->title }}</option>
                  @endforeach
                </select>
                
                <!-- Dropdown Menu -->
                <div id="course-dropdown" 
                     class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                  <div class="p-2">
                    <div class="relative mb-2">
                      <input type="text" 
                             id="course-search" 
                             placeholder="ابحث عن دورة..."
                             class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                             onkeyup="filterCourses(this.value)">
                    </div>
                    <div id="course-options">
                      @foreach($courses as $course)
                        <div class="course-option flex items-center p-2 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors duration-200"
                             data-course-id="{{ $course->id }}"
                             data-course-title="{{ $course->title }}"
                             onclick="toggleCourse({{ $course->id }}, '{{ $course->title }}')">
                          <input type="checkbox" 
                                 class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                 {{ $selected->contains($course->id) ? 'checked' : '' }}
                                 data-course-id="{{ $course->id }}">
                          <span class="mr-2 text-sm text-gray-900">{{ $course->title }}</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
              
              @error('course_ids')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
              <p class="mt-1 text-sm text-gray-500">اختر الدورات التي ستكون جزءاً من هذا المسار</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end space-x-4 space-x-reverse pt-8 border-t border-gray-200">
            <a href="{{ route('learning-paths.index') }}" 
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
  // Course selection functionality
  let selectedCourses = new Set();
  
  // Initialize selected courses from existing data
  document.addEventListener('DOMContentLoaded', function() {
    const hiddenSelect = document.getElementById('course_ids');
    const options = hiddenSelect.querySelectorAll('option[selected]');
    options.forEach(option => {
      selectedCourses.add(parseInt(option.value));
    });
  });

  function toggleCourseDropdown() {
    const dropdown = document.getElementById('course-dropdown');
    const icon = document.getElementById('dropdown-icon');
    
    if (dropdown.classList.contains('hidden')) {
      dropdown.classList.remove('hidden');
      icon.style.transform = 'rotate(180deg)';
    } else {
      dropdown.classList.add('hidden');
      icon.style.transform = 'rotate(0deg)';
    }
  }

  function toggleCourse(courseId, courseTitle) {
    const hiddenSelect = document.getElementById('course_ids');
    const checkbox = document.querySelector(`input[data-course-id="${courseId}"]`);
    
    if (selectedCourses.has(courseId)) {
      // Remove course
      selectedCourses.delete(courseId);
      const option = hiddenSelect.querySelector(`option[value="${courseId}"]`);
      if (option) option.removeAttribute('selected');
      checkbox.checked = false;
    } else {
      // Add course
      selectedCourses.add(courseId);
      const option = hiddenSelect.querySelector(`option[value="${courseId}"]`);
      if (option) option.setAttribute('selected', 'selected');
      checkbox.checked = true;
    }
    
    updateSelectedCoursesDisplay();
  }

  function removeCourse(courseId, event) {
    event.stopPropagation();
    toggleCourse(courseId, '');
  }

  function updateSelectedCoursesDisplay() {
    const displayContainer = document.getElementById('selected-courses-display');
    const courses = @json($courses);
    
    if (selectedCourses.size === 0) {
      displayContainer.innerHTML = '<span class="text-gray-500">اختر الدورات</span>';
    } else {
      let html = '';
      selectedCourses.forEach(courseId => {
        const course = courses.find(c => c.id == courseId);
        if (course) {
          html += `
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              ${course.title}
              <button type="button" 
                      class="mr-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 transition-colors duration-200"
                      onclick="removeCourse(${courseId}, event)">
                <i class="ti ti-x text-xs"></i>
              </button>
            </span>
          `;
        }
      });
      displayContainer.innerHTML = html;
    }
  }

  function filterCourses(searchTerm) {
    const options = document.querySelectorAll('.course-option');
    const term = searchTerm.toLowerCase();
    
    options.forEach(option => {
      const title = option.getAttribute('data-course-title').toLowerCase();
      if (title.includes(term)) {
        option.style.display = 'flex';
      } else {
        option.style.display = 'none';
      }
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const selector = document.getElementById('course-selector');
    const dropdown = document.getElementById('course-dropdown');
    
    if (!selector.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.add('hidden');
      document.getElementById('dropdown-icon').style.transform = 'rotate(0deg)';
    }
  });

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