@php($title = 'إنشاء مسار تعلم')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">إنشاء مسار تعلم جديد</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">اجمع الدورات في مسار واحد</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('learning-paths.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للمسارات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-route text-white text-xl"></i>
            </div>
            نموذج إنشاء مسار
          </h2>
        </div>
      </div>

      <div class="p-8">
        <form method="POST" action="{{ route('learning-paths.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf
          <div>
            <label for="name" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الاسم</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('name')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div>
            <label for="sort_order" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">ترتيب العرض</label>
            <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', 0) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            @error('sort_order')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-2">
            <label for="description" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-gray-700 mb-2" style="font-size: 1.3rem;">مفعل</label>
            <label class="inline-flex items-center gap-2">
              <input class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" type="checkbox" id="is_active" name="is_active" value="1" checked>
              <span style="font-size: 1.3rem;">نشط</span>
            </label>
          </div>
          <div class="md:col-span-2">
            <label for="course_ids" class="block text-gray-700 mb-2" style="font-size: 1.3rem;">الدورات</label>
            <select id="course_ids" name="course_ids[]" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" multiple size="6">
              @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->title }}</option>
              @endforeach
            </select>
            @error('course_ids')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">إنشاء المسار</button>
            <a href="{{ route('learning-paths.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
