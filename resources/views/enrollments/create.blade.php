@php($title = 'تسجيل طالب')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تسجيل طالب في دورة</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اختر الطالب والدورة وحدد الإعدادات الأولية</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للتسجيلات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-user-plus text-white text-xl"></i>
            </div>
            نموذج التسجيل
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form action="{{ route('enrollments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf

          <div>
            <label for="user_id" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الطالب *</label>
            <select id="user_id" name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
              <option value="">اختر الطالب</option>
              @foreach($students as $student)
              <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} - {{ $student->email }}</option>
              @endforeach
            </select>
            @error('user_id')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

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
            <label for="progress" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">نسبة التقدم المبدئية (%)</label>
            <input id="progress" name="progress" type="number" value="{{ old('progress', 0) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" min="0" max="100">
            @error('progress')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div>
            <label for="grade" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">التقدير</label>
            <input id="grade" name="grade" type="text" value="{{ old('grade') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" placeholder="A, B, C..">
            @error('grade')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2">
            <label for="notes" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">ملاحظات</label>
            <textarea id="notes" name="notes" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('notes') }}</textarea>
            @error('notes')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">تسجيل</button>
            <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
