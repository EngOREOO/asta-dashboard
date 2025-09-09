@php($title = 'إنشاء تقييم')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء تقييم جديد</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اربط التقييم بدورة وحدد الإعدادات</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للتقييمات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-clipboard-text text-white text-xl"></i>
            </div>
            نموذج إنشاء تقييم
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="{{ route('assessments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
            <label for="type" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نوع التقييم *</label>
            <select id="type" name="type" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر النوع</option>
              <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>اختبار</option>
              <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>امتحان</option>
              <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>واجب</option>
              <option value="survey" {{ old('type') == 'survey' ? 'selected' : '' }}>استبيان</option>
            </select>
            @error('type')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="title" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">عنوان التقييم *</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('title')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="duration_minutes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">المدة (دقائق)</label>
            <input id="duration_minutes" name="duration_minutes" type="number" value="{{ old('duration_minutes') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لبدون حد زمني</small>
            @error('duration_minutes')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="max_attempts" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">أقصى عدد محاولات</label>
            <input id="max_attempts" name="max_attempts" type="number" value="{{ old('max_attempts') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="1">
            <small class="text-gray-500">اتركه فارغاً لعدد غير محدود</small>
            @error('max_attempts')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="passing_score" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">مسار النجاح (%)</label>
            <input id="passing_score" name="passing_score" type="number" value="{{ old('passing_score') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100">
            @error('passing_score')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-3 gap-4 items-center">
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">مفعل</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">ترتيب عشوائي للأسئلة</span>
            </label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" {{ old('show_results_immediately') ? 'checked' : '' }}>
              <span style="font-size: 1.3rem;">عرض النتائج فوراً</span>
            </label>
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">إنشاء التقييم</button>
            <a href="{{ route('assessments.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
