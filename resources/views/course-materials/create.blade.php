@php($title = 'إضافة مادة دراسية')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إضافة مادة دراسية</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">ارفع مادة أو اربطها بدورة</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('course-materials.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للمواد
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-notes text-white text-xl"></i>
            </div>
            نموذج إنشاء مادة
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="{{ route('course-materials.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf

          <div>
            <label for="course_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورة *</label>
            <select id="course_id" name="course_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الدورة</option>
              @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }} @if($course->instructor) - {{ $course->instructor->name }} @endif</option>
              @endforeach
            </select>
            @error('course_id')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="level_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">مستوى الدورة</label>
            <select id="level_id" name="level_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
              <option value="">اختياري</option>
              @foreach($levels as $level)
              <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->title }}</option>
              @endforeach
            </select>
            @error('level_id')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="title" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عنوان المادة *</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('title')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="type" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نوع المادة *</label>
            <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر النوع</option>
              <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>فيديو</option>
              <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>مستند</option>
              <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>اختبار</option>
              <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>واجب</option>
            </select>
            @error('type')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="content_url" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">رابط المحتوى</label>
            <input id="content_url" name="content_url" type="url" value="{{ old('content_url') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" placeholder="https://...">
            @error('content_url')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="file_path" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">رفع ملف</label>
            <input id="file_path" name="file_path" type="file" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            <small class="text-gray-500">الحد الأقصى 100MB. الصيغ: فيديو، PDF، DOC..</small>
            @error('file_path')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="duration" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (ثوانٍ)</label>
            <input id="duration" name="duration" type="number" value="{{ old('duration') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0">
            @error('duration')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="order" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الترتيب</label>
            <input id="order" name="order" type="number" value="{{ old('order') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0">
            @error('order')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">صلاحية الوصول</label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_free" name="is_free" value="1" {{ old('is_free') ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">مجاني</span>
            </label>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">حفظ المادة</button>
            <a href="{{ route('course-materials.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
