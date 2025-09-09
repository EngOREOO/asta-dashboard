@extends('layouts.dash')

@section('title', 'إضافة رأي جديد')

@section('content')
    <div class="font-arabic">
    <!-- Welcome Header -->
    <div class="mb-10">
        <div class="relative bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-600 rounded-3xl p-10 text-white overflow-hidden shadow-2xl">
            <!-- Background decorative elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-16 -translate-x-16"></div>
            <div class="absolute top-1/2 left-1/2 w-24 h-24 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-2 h-8 bg-white/30 rounded-full"></div>
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">إضافة رأي جديد</h1>
                    </div>
                    <p class="text-purple-100 text-xl font-medium">إضافة رأي جديد للعميل</p>
                    <div class="flex items-center space-x-4 rtl:space-x-reverse text-purple-100">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <i class="ti ti-calendar text-lg"></i>
                            <span>{{ now()->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <i class="ti ti-clock text-lg"></i>
                            <span>{{ now()->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('admin.testimonials') }}" class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 inline-flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 border border-white/20">
                        <i class="ti ti-arrow-right mr-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
        <div class="px-8 py-8">
            <div class="flex items-center space-x-3 rtl:space-x-reverse mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-plus text-xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">نموذج إضافة الرأي</h3>
            </div>
            
            <form method="POST" action="{{ route('admin.testimonials.store') }}" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xl font-semibold text-gray-700 mb-3">اسم المستخدم</label>
                        <input 
                            type="text" 
                            name="user_name" 
                            value="{{ old('user_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                            required
                            placeholder="أدخل اسم المستخدم"
                        >
                        @error('user_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xl font-semibold text-gray-700 mb-3">التقييم</label>
                        <select 
                            name="rating" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                            required
                        >
                            <option value="">اختر التقييم</option>
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ 1 نجمة</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ 2 نجمة</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ 3 نجمة</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ 4 نجمة</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 نجمة</option>
                        </select>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-xl font-semibold text-gray-700 mb-3">التعليق</label>
                    <textarea 
                        name="comment" 
                        rows="6" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none" 
                        required
                        placeholder="اكتب تعليق العميل هنا..."
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-center p-6 bg-green-50 rounded-xl border border-green-200">
                        <input 
                            type="checkbox" 
                            name="is_approved" 
                            value="1"
                            {{ old('is_approved') ? 'checked' : '' }}
                            class="h-6 w-6 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label class="mr-4 block text-sm font-semibold text-green-800">
                            <i class="ti ti-check mr-2"></i>معتمدة
                        </label>
                    </div>
                    
                    <div class="flex items-center p-6 bg-purple-50 rounded-xl border border-purple-200">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            value="1"
                            {{ old('is_featured') ? 'checked' : '' }}
                            class="h-6 w-6 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        >
                        <label class="mr-4 block text-sm font-semibold text-purple-800">
                            <i class="ti ti-star mr-2"></i>مميزة
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                    <a 
                        href="{{ route('admin.testimonials') }}" 
                        class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 font-semibold"
                    >
                        <i class="ti ti-x mr-2"></i>إلغاء
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all duration-200 font-semibold"
                    >
                        <i class="ti ti-check mr-2"></i>إضافة الرأي
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
