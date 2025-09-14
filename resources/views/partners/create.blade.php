@php($title = 'إضافة شريك جديد')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-building-community text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">إضافة شريك جديد</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">أضف شريكاً جديداً إلى منصتك</p>
          </div>
        </div>
        <a href="{{ route('partners.index') }}" class="bg-white/70 backdrop-blur-xl shadow-lg rounded-2xl px-6 py-3 text-gray-700 font-semibold hover:shadow-xl transition-all duration-300 border border-white/20">
          <i class="ti ti-arrow-right mr-2"></i>العودة للشركاء
        </a>
      </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-plus text-white"></i>
            </div>
            بيانات الشريك
          </h2>
        </div>
      </div>
      
      <div class="p-8">
        <form method="POST" action="{{ route('partners.store') }}" enctype="multipart/form-data" class="space-y-8" id="partner-form">
          @csrf
          
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Fields -->
            <div class="lg:col-span-2 space-y-6">
              <!-- Partner Name -->
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label for="name" class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-building mr-2 text-blue-600"></i>اسم الشريك *
                </label>
                <input type="text" 
                       class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 @error('name') border-red-500 @enderror" 
                       id="name" name="name" value="{{ old('name') }}" 
                       placeholder="أدخل اسم الشريك" required>
                @error('name')
                <div class="text-red-600 mt-2 text-sm font-semibold">
                  <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </div>
                @enderror
              </div>

              <!-- Description -->
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label for="description" class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-file-text mr-2 text-green-600"></i>الوصف
                </label>
                <textarea class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all duration-300 @error('description') border-red-500 @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="أدخل وصفاً مختصراً عن الشريك">{{ old('description') }}</textarea>
                @error('description')
                <div class="text-red-600 mt-2 text-sm font-semibold">
                  <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </div>
                @enderror
              </div>

              <!-- Website URL -->
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label for="website" class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-world mr-2 text-purple-600"></i>رابط الموقع
                </label>
                <input type="url" 
                       class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 @error('website') border-red-500 @enderror" 
                       id="website" name="website" value="{{ old('website') }}" 
                       placeholder="https://example.com">
                @error('website')
                <div class="text-red-600 mt-2 text-sm font-semibold">
                  <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </div>
                @enderror
              </div>

              <!-- Sort Order and Status -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                  <label for="sort_order" class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                    <i class="ti ti-sort-ascending mr-2 text-orange-600"></i>ترتيب العرض
                  </label>
                  <input type="number" 
                         class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all duration-300 @error('sort_order') border-red-500 @enderror" 
                         id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" 
                         placeholder="0">
                  @error('sort_order')
                  <div class="text-red-600 mt-2 text-sm font-semibold">
                    <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                  </div>
                  @enderror
                </div>

                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                  <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                    <i class="ti ti-toggle-right mr-2 text-teal-600"></i>الحالة
                  </label>
                  <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="hidden" name="is_active" value="0">
                      <input type="checkbox" class="sr-only peer" id="is_active" name="is_active" value="1"
                             {{ old('is_active', true) ? 'checked' : '' }}>
                      <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-teal-600"></div>
                      <span class="mr-3 text-sm font-medium text-gray-700">نشط</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Logo Upload Section -->
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                <label class="block font-bold text-gray-800 mb-3" style="font-size: 1.2rem;">
                  <i class="ti ti-photo mr-2 text-indigo-600"></i>شعار الشريك
                </label>
                
                <!-- File Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-indigo-500 transition-colors duration-300" 
                     id="upload-area">
                  <div class="space-y-4">
                    <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto">
                      <i class="ti ti-cloud-upload text-indigo-600 text-2xl"></i>
                    </div>
                    <div>
                      <p class="text-gray-600 font-semibold">اسحب وأفلت الصورة هنا</p>
                      <p class="text-gray-500 text-sm">أو انقر للاختيار</p>
                    </div>
                    <input type="file" 
                           class="hidden @error('image') border-red-500 @enderror" 
                           id="image" name="image" accept="image/*">
                    <button type="button" 
                            class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-indigo-700 transition-colors duration-300"
                            onclick="document.getElementById('image').click()">
                      اختيار صورة
                    </button>
                  </div>
                </div>
                
                <!-- Image Preview -->
                <div id="image-preview" class="hidden mt-4">
                  <img id="preview-img" class="w-full h-32 object-cover rounded-xl border border-gray-200" alt="Preview">
                  <button type="button" 
                          class="mt-2 text-red-600 hover:text-red-800 font-semibold text-sm"
                          onclick="removeImage()">
                    <i class="ti ti-trash mr-1"></i>إزالة الصورة
                  </button>
                </div>
                
                @error('image')
                <div class="text-red-600 mt-2 text-sm font-semibold">
                  <i class="ti ti-alert-circle mr-1"></i>{{ $message }}
                </div>
                @enderror
                
                <div class="mt-4 text-xs text-gray-500 bg-gray-100 rounded-lg p-3">
                  <i class="ti ti-info-circle mr-1"></i>
                  الحد الأقصى لحجم الملف: 2 ميجابايت<br>
                  الصيغ المدعومة: JPG، PNG، GIF
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end space-x-4 space-x-reverse pt-6 border-t border-gray-200">
            <a href="{{ route('partners.index') }}" 
               class="bg-gray-100 text-gray-700 px-8 py-3 rounded-2xl font-semibold hover:bg-gray-200 transition-all duration-300 border border-gray-300">
              <i class="ti ti-x mr-2"></i>إلغاء
            </a>
            <button type="submit" 
                    class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
              <i class="ti ti-device-floppy mr-2"></i>حفظ الشريك
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Form submission debugging
document.getElementById('partner-form').addEventListener('submit', function(e) {
  console.log('Form submitted');
  console.log('Form data:', new FormData(this));
  
  // Check if required fields are filled
  const nameField = document.getElementById('name');
  if (!nameField.value.trim()) {
    e.preventDefault();
    alert('اسم الشريك مطلوب');
    nameField.focus();
    return false;
  }
  
  console.log('Form validation passed, submitting...');
});

// Image upload and preview functionality
document.getElementById('image').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview-img').src = e.target.result;
      document.getElementById('image-preview').classList.remove('hidden');
      document.getElementById('upload-area').classList.add('hidden');
    };
    reader.readAsDataURL(file);
  }
});

function removeImage() {
  document.getElementById('image').value = '';
  document.getElementById('image-preview').classList.add('hidden');
  document.getElementById('upload-area').classList.remove('hidden');
}

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');
uploadArea.addEventListener('dragover', function(e) {
  e.preventDefault();
  this.classList.add('border-indigo-500', 'bg-indigo-50');
});

uploadArea.addEventListener('dragleave', function(e) {
  e.preventDefault();
  this.classList.remove('border-indigo-500', 'bg-indigo-50');
});

uploadArea.addEventListener('drop', function(e) {
  e.preventDefault();
  this.classList.remove('border-indigo-500', 'bg-indigo-50');
  
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    document.getElementById('image').files = files;
    document.getElementById('image').dispatchEvent(new Event('change'));
  }
});
</script>
@endsection
