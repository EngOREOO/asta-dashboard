@php
    $title = 'تعديل الدورة';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
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
          تعديل الدورة
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-book mr-2 text-cyan-500"></i>
          تحديث معلومات الدورة
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-3">
        <a href="{{ route('courses.show', $course) }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-eye mr-2 group-hover:scale-110 transition-transform duration-300"></i>
          عرض
        </a>
        <a href="{{ route('courses.index') }}" 
           class="group inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl font-semibold text-sm text-gray-700 shadow-lg hover:shadow-xl hover:bg-white hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للدورات
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
              <i class="ti ti-book text-white text-xl"></i>
            </div>
            معلومات الدورة
          </h2>
          <p class="text-blue-100 mt-2">تحديث بيانات الدورة</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8" x-data="{step:1, max:3, lessons:[{title:'',type:'video',video:null}], materials:[{file:null}], next(){ if(this.step < this.max) this.step++; }, prev(){ if(this.step>1) this.step--; }}">
        <!-- Stepper -->
        <div class="flex items-center justify-between mb-8">
          <template x-for="s in max" :key="s">
            <div class="flex-1 flex items-center">
              <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold" :class="step>=s ? 'bg-gradient-to-r from-teal-500 to-blue-600 text-white shadow' : 'bg-gray-200 text-gray-500'" x-text="s"></div>
              <div class="flex-1 h-1 mx-2 rounded" :class="s<max ? (step> s ? 'bg-gradient-to-r from-teal-500 to-blue-600' : 'bg-gray-200') : ''"></div>
            </div>
          </template>
        </div>

        <form method="POST" action="{{ route('courses.update', $course) }}" enctype="multipart/form-data" class="space-y-8">
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

          <!-- Step 1: Basic Information -->
          <div class="space-y-6" x-show="step===1">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-info-circle mr-2 text-blue-500"></i>
              المعلومات الأساسية
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الدورة</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $course->title) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('title') border-red-300 @enderror"
                       required>
                @error('title')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-4 h-4 text-gray-500">
                  </div>
                  <input type="number" 
                         step="0.01" 
                         id="price" 
                         name="price" 
                         value="{{ old('price', $course->price) }}"
                         class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('price') border-red-300 @enderror"
                         required>
                </div>
                @error('price')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">كود الدورة</label>
                <input type="text" 
                       id="code" 
                       name="code" 
                       value="{{ old('code', $course->code) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('code') border-red-300 @enderror"
                       required>
                @error('code')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-2">المدة المقدرة (ساعات)</label>
                <input type="number" 
                       id="estimated_duration" 
                       name="estimated_duration" 
                       value="{{ old('estimated_duration', $course->estimated_duration) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('estimated_duration') border-red-300 @enderror">
                @error('estimated_duration')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">المدرّس</label>
                <select id="instructor_id" name="instructor_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('instructor_id') border-red-300 @enderror" required>
                  <option value="">اختر المدرّس</option>
                  @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                      {{ $instructor->name }}
                    </option>
                  @endforeach
                </select>
                @error('instructor_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">مدة الدوره بالأيام</label>
                <input type="number" 
                       id="duration_days" 
                       name="duration_days" 
                       value="{{ old('duration_days', $course->duration_days) }}"
                       min="1"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('duration_days') border-red-300 @enderror">
                @error('duration_days')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="awarding_institution" class="block text-sm font-medium text-gray-700 mb-2">الجهه المانحه</label>
                <input type="text" 
                       id="awarding_institution" 
                       name="awarding_institution" 
                       value="{{ old('awarding_institution', $course->awarding_institution) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('awarding_institution') border-red-300 @enderror">
                @error('awarding_institution')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">الفئة</label>
                <select id="category_id" name="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('category_id') border-red-300 @enderror">
                  <option value="">اختر الفئة</option>
                  @foreach(\App\Models\Category::orderBy('name')->get(['id', 'name']) as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
                @error('category_id')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                <select id="specialization" name="specialization" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('specialization') border-red-300 @enderror">
                  <option value="">اختر التخصص</option>
                  @isset($specializations)
                    @foreach($specializations as $spec)
                      <option value="{{ $spec->name }}" @selected(old('specialization', $course->specialization) === $spec->name)>{{ $spec->name }}</option>
                    @endforeach
                  @endisset
                </select>
                @error('specialization')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              @if($isAdmin)
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('status') border-red-300 @enderror">
                  <option value="pending" {{ old('status', $course->status) == 'pending' ? 'selected' : '' }}>في انتظار الموافقة</option>
                  <option value="approved" {{ old('status', $course->status) == 'approved' ? 'selected' : '' }}>موافق عليها</option>
                  <option value="rejected" {{ old('status', $course->status) == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                  <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>مسودة</option>
                </select>
                @error('status')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              @endif
            </div>
            
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الدورة</label>
              <textarea id="description" 
                        name="description" 
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('description') border-red-300 @enderror"
                        required>{{ old('description', $course->description) }}</textarea>
              @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Image Upload (Step 1) -->
          <div class="space-y-6" x-show="step===1">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-photo mr-2 text-green-500"></i>
              صورة الدورة
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">صورة الدورة</label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white/80 backdrop-blur-sm @error('image') border-red-300 @enderror">
                @error('image')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              
              @if($course->thumbnail)
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الصورة الحالية</label>
                <div class="relative">
                  <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists($course->thumbnail) ? \Illuminate\Support\Facades\Storage::disk('public')->url($course->thumbnail) : asset($course->thumbnail) }}" 
                       alt="صورة الدورة" 
                       class="w-full h-48 object-cover rounded-xl border border-gray-200">
                </div>
              </div>
              @endif
            </div>
          </div>

          <!-- Step 2: Lessons -->
          <div class="space-y-6" x-show="step===2">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-list-details mr-2 text-pink-500"></i>
              دروس الدورة
            </h3>
            <template x-for="(lesson,idx) in lessons" :key="idx">
              <div class="border border-gray-200 rounded-2xl p-4 bg-white/70 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الدرس</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" x-model="lesson.title" name="lessons[title][]">
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                    <select class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" x-model="lesson.type" name="lessons[type][]">
                      <option value="video">درس فيديو</option>
                      <option value="quiz">اختبار</option>
                    </select>
                  </div>
                  <div class="flex items-end justify-end">
                    <button type="button" class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200" @click="lessons.splice(idx,1)" x-show="lessons.length>1">حذف</button>
                  </div>
                </div>
                <div x-show="lesson.type==='video'">
                  <label class="block text-sm font-medium text-gray-700 mb-1">رفع فيديو</label>
                  <input type="file" accept="video/*" class="w-full px-4 py-2 border border-gray-300 rounded-xl" name="lessons[video][]">
                </div>
              </div>
            </template>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl shadow hover:shadow-lg" @click="lessons.push({title:'',type:'video',video:null})"><i class="ti ti-plus mr-2"></i>إضافة درس</button>
          </div>

          <!-- Step 3: Materials -->
          <div class="space-y-6" x-show="step===3">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-folder mr-2 text-amber-500"></i>
              مواد إضافية
            </h3>
            <template x-for="(m, i) in materials" :key="i">
              <div class="border border-gray-200 rounded-2xl p-4 bg-white/70 space-y-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">ملف (PDF, DOCX)</label>
                  <input type="file" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx" class="w-full px-4 py-2 border border-gray-300 rounded-xl" name="materials[file][]">
                </div>
                <div class="flex justify-end">
                  <button type="button" class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200" @click="materials.splice(i,1)" x-show="materials.length>1">حذف</button>
                </div>
              </div>
            </template>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl shadow hover:shadow-lg" @click="materials.push({file:null})"><i class="ti ti-plus mr-2"></i>إضافة مادة</button>
          </div>

          <!-- Learning Paths (remain outside steps to allow quick changes) -->
          @if($learningPaths->count() > 0)
          <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
              <i class="ti ti-route mr-2 text-purple-500"></i>
              مسارات التعلم
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              @foreach($learningPaths as $path)
                <label class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                  <input type="checkbox" 
                         name="learning_path_ids[]" 
                         value="{{ $path->id }}"
                         {{ in_array($path->id, old('learning_path_ids', $course->learningPaths->pluck('id')->toArray())) ? 'checked' : '' }}
                         class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                  <span class="mr-3 text-sm font-medium text-gray-700">{{ $path->name }}</span>
                </label>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Wizard Navigation -->
          <div class="flex items-center justify-between pt-8 border-t border-gray-200">
            <div>
              <a href="{{ route('courses.show', $course) }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-lg hover:shadow-xl">إلغاء</a>
            </div>
            <div class="flex items-center gap-3">
              <button type="button" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-800 rounded-xl shadow hover:bg-gray-200" @click="prev()" x-show="step>1">السابق</button>
              <button type="button" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl shadow hover:shadow-xl" @click="next()" x-show="step<max">التالي</button>
              <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow hover:shadow-xl" x-show="step===max"><i class="ti ti-check mr-2"></i>حفظ التغييرات</button>
            </div>
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
  10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
  20%, 40%, 60%, 80% { transform: translateX(10px); }
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
</style>
@endsection