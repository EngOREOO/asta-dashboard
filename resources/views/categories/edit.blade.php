@php($title = 'تعديل القسم')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تعديل القسم</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">تحديث بيانات القسم: {{ $category->name }}</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">
          <i class="ti ti-arrow-right mr-2"></i>
          العودة للقسمات
        </a>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-category text-white text-xl"></i>
            </div>
            نموذج تعديل القسم
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">قم بتحديث بيانات القسم ثم احفظ</p>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>

      <div class="p-8">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          @csrf
          @method('PUT')
          <div>
            <label class="block text-gray-700 mb-2" for="name" style="font-size: 1.3rem;">اسم القسم</label>
            <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;" required>
            @error('name')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-gray-700 mb-2" for="slug" style="font-size: 1.3rem;">المعرف (Slug)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            <small class="text-gray-500">اتركه فارغاً لتوليده تلقائياً من الاسم</small>
            @error('slug')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-gray-700 mb-2" for="code" style="font-size: 1.3rem;">كود القسم *</label>
            <input type="text" id="code" name="code" value="{{ old('code', $category->code) }}" placeholder="CAT-001" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem; font-family: Arial, sans-serif;" required>
            <small class="text-gray-500">معرف قصير للقسم</small>
            @error('code')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-2">
            <label class="block text-gray-700 mb-2" for="description" style="font-size: 1.3rem;">الوصف</label>
            <textarea id="description" name="description" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">{{ old('description', $category->description) }}</textarea>
            @error('description')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="block text-gray-700 mb-2" for="image_url" style="font-size: 1.3rem;">رابط الصورة</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $category->image_url) }}" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" style="font-size: 1.3rem;">
            @error('image_url')<p class="text-red-600 mt-1" style="font-size: 1.3rem;">{{ $message }}</p>@enderror
          </div>

          <div class="md:col-span-2 flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl shadow hover:shadow-lg hover:from-indigo-700 hover:to-blue-700 transition" style="font-size: 1.3rem;">
              <i class="ti ti-device-floppy mr-2"></i>
              حفظ التغييرات
            </button>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 border border-gray-200 rounded-2xl hover:bg-gray-50 shadow-sm" style="font-size: 1.3rem;">إلغاء</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection