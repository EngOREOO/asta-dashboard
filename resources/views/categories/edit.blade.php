@php
    $title = 'تعديل القسم';
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

    <!-- Debug: Show all validation errors -->
    @if($errors->any())
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 mb-6">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-triangle text-yellow-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <h3 class="text-sm font-medium text-yellow-800">أخطاء التحقق:</h3>
          <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent">
          تعديل القسم
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-category-2 mr-2 text-cyan-500"></i>
          تحديث معلومات القسم: <span class="font-semibold text-gray-800">{{ $category->name }}</span>
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('categories.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:translate-x-1 transition-transform duration-300"></i>
          العودة للقسمات
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
              <i class="ti ti-category-2 text-white text-xl"></i>
            </div>
            تعديل بيانات القسم
          </h2>
          <p class="text-blue-100 mt-2">قم بتحديث معلومات القسم بسهولة وأمان</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
    
    <form method="POST" action="{{ route('categories.update', $category) }}" class="p-8">
      @csrf
      @method('PUT')
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Main Form Fields -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Category Name -->
          <div class="group space-y-3">
            <label for="name" class="block text-sm font-semibold text-gray-800 flex items-center">
              <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-2"></div>
              اسم القسم <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-blue-500">
                <i class="ti ti-category-2 text-gray-400 group-focus-within:text-blue-500"></i>
              </div>
              <input type="text" 
                     id="name" 
                     name="name" 
                     value="{{ old('name', $category->name) }}"
                     class="block w-full pr-12 pl-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 hover:border-gray-300 hover:bg-white/70 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                     placeholder="أدخل اسم القسم"
                     required>
            </div>
            @error('name')
            <div class="flex items-center space-x-2 space-x-reverse text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200 animate-shake shadow-sm">
              <i class="ti ti-alert-circle text-lg"></i>
              <span class="text-sm font-semibold">{{ $message }}</span>
            </div>
            @enderror
          </div>

          <!-- Slug -->
          <div class="group space-y-3">
            <label for="slug" class="block text-sm font-semibold text-gray-800 flex items-center">
              <div class="w-2 h-2 bg-gradient-to-r from-green-500 to-blue-500 rounded-full mr-2"></div>
              الرابط المختصر
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-green-500">
                <i class="ti ti-link text-gray-400 group-focus-within:text-green-500"></i>
              </div>
              <input type="text" 
                     id="slug" 
                     name="slug" 
                     value="{{ old('slug', $category->slug) }}"
                     class="block w-full pr-12 pl-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-300 hover:border-gray-300 hover:bg-white/70 @error('slug') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                     placeholder="category-slug">
            </div>
            @error('slug')
            <div class="flex items-center space-x-2 space-x-reverse text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200 animate-shake shadow-sm">
              <i class="ti ti-alert-circle text-lg"></i>
              <span class="text-sm font-semibold">{{ $message }}</span>
            </div>
            @enderror
          </div>

          <!-- Description -->
          <div class="group space-y-3">
            <label for="description" class="block text-sm font-semibold text-gray-800 flex items-center">
              <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-2"></div>
              الوصف
            </label>
            <div class="relative group">
              <div class="absolute top-4 right-4 pointer-events-none transition-colors duration-300 group-focus-within:text-purple-500">
                <i class="ti ti-file-text text-gray-400 group-focus-within:text-purple-500"></i>
              </div>
              <textarea id="description" 
                        name="description" 
                        rows="4"
                        class="block w-full pr-12 pl-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 hover:border-gray-300 hover:bg-white/70 resize-none @error('description') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                        placeholder="أدخل وصف القسم">{{ old('description', $category->description) }}</textarea>
            </div>
            @error('description')
            <div class="flex items-center space-x-2 space-x-reverse text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200 animate-shake shadow-sm">
              <i class="ti ti-alert-circle text-lg"></i>
              <span class="text-sm font-semibold">{{ $message }}</span>
            </div>
            @enderror
          </div>

          <!-- Image URL -->
          <div class="group space-y-3">
            <label for="image_url" class="block text-sm font-semibold text-gray-800 flex items-center">
              <div class="w-2 h-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mr-2"></div>
              رابط الصورة
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-orange-500">
                <i class="ti ti-photo text-gray-400 group-focus-within:text-orange-500"></i>
              </div>
              <input type="url" 
                     id="image_url" 
                     name="image_url" 
                     value="{{ old('image_url', $category->image_url) }}"
                     class="block w-full pr-12 pl-4 py-4 bg-white/50 backdrop-blur-sm border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300 hover:border-gray-300 hover:bg-white/70 @error('image_url') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                     placeholder="https://example.com/image.jpg">
            </div>
            @error('image_url')
            <div class="flex items-center space-x-2 space-x-reverse text-red-600 bg-red-50 px-4 py-3 rounded-xl border border-red-200 animate-shake shadow-sm">
              <i class="ti ti-alert-circle text-lg"></i>
              <span class="text-sm font-semibold">{{ $message }}</span>
            </div>
            @enderror
          </div>
        </div>

        <!-- Category Info Section -->
        <div class="space-y-8">
          <!-- Category Stats -->
          <div class="bg-gradient-to-br from-white/80 to-gray-50/80 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-xl">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
              <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mr-3">
                <i class="ti ti-chart-bar text-white text-lg"></i>
              </div>
              إحصائيات القسم
            </h3>
            
            <div class="space-y-6">
              <!-- Courses Count -->
              <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-600">عدد الدورات</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $category->courses_count ?? 0 }}</p>
                  </div>
                  <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="ti ti-book text-blue-600 text-xl"></i>
                  </div>
                </div>
              </div>

              <!-- Created Date -->
              <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-600">تاريخ الإنشاء</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $category->created_at->format('M Y') }}</p>
                  </div>
                  <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="ti ti-calendar text-green-600 text-xl"></i>
                  </div>
                </div>
              </div>

              <!-- Last Updated -->
              @if($category->updated_at != $category->created_at)
              <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-medium text-gray-600">آخر تحديث</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $category->updated_at->format('M Y') }}</p>
                  </div>
                  <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="ti ti-clock text-purple-600 text-xl"></i>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>

          <!-- Danger Zone -->
          <div class="bg-gradient-to-br from-red-50/80 to-pink-50/80 backdrop-blur-xl rounded-3xl p-8 border border-red-200/20 shadow-xl">
            <h3 class="text-xl font-bold text-red-900 mb-6 flex items-center">
              <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mr-3">
                <i class="ti ti-alert-triangle text-white text-lg"></i>
              </div>
              منطقة الخطر
            </h3>
            
            <div class="space-y-4">
              <p class="text-sm text-red-700">
                حذف القسم سيؤدي إلى إزالة جميع البيانات المرتبطة به نهائياً.
              </p>
              
              @if($category->courses_count > 0)
              <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex items-center">
                  <i class="ti ti-alert-circle text-yellow-600 mr-2"></i>
                  <p class="text-sm text-yellow-800">
                    لا يمكن حذف القسم لأنه يحتوي على {{ $category->courses_count }} دورة.
                  </p>
                </div>
              </div>
              @endif
              
              <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟ لا يمكن التراجع عن هذا الإجراء.')" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $category->courses_count > 0 ? 'disabled' : '' }}>
                  <i class="ti ti-trash mr-2"></i>
                  حذف القسم
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="mt-12 flex flex-col sm:flex-row gap-6 justify-end">
        <a href="{{ route('categories.show', $category) }}" 
           class="group inline-flex items-center justify-center px-8 py-4 bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-white hover:border-gray-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300 shadow-lg hover:shadow-xl">
          <i class="ti ti-x mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
          إلغاء
        </a>
        <button type="submit" 
                class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 border border-transparent rounded-2xl font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
          <i class="ti ti-device-floppy mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          حفظ التغييرات
        </button>
      </div>
    </form>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.5s ease-out;
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
  background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #2563eb, #7c3aed);
}
</style>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
  const name = this.value;
  const slug = name
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim('-');
  
  document.getElementById('slug').value = slug;
});

// Add smooth focus transitions
document.querySelectorAll('input, textarea').forEach(input => {
  input.addEventListener('focus', function() {
    this.parentElement.classList.add('ring-4', 'ring-blue-500/20');
  });
  
  input.addEventListener('blur', function() {
    this.parentElement.classList.remove('ring-4', 'ring-blue-500/20');
  });
});

// Add loading state to submit button
document.querySelector('form').addEventListener('submit', function() {
  const submitBtn = document.querySelector('button[type="submit"]');
  submitBtn.innerHTML = '<i class="ti ti-loader-2 mr-2 animate-spin"></i>جاري الحفظ...';
  submitBtn.disabled = true;
});

// Auto-hide debug errors after 10 seconds
setTimeout(function() {
  const debugErrors = document.querySelector('.bg-yellow-50');
  if (debugErrors) {
    debugErrors.style.transition = 'opacity 0.5s ease-out';
    debugErrors.style.opacity = '0';
    setTimeout(() => debugErrors.remove(), 500);
  }
}, 10000);
</script>
@endsection