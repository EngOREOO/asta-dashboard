@extends('layouts.dash')

@section('title', 'إدارة آراء العملاء')

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
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">إدارة آراء العملاء</h1>
                    </div>
                    <p class="text-purple-100 text-xl font-medium">إدارة جميع آراء العملاء والتقييمات</p>
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
                    <a href="{{ route('admin.testimonials.create') }}" class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 inline-flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 border border-white/20">
                        <i class="ti ti-plus mr-2"></i>
                        إضافة رأي جديد
                    </a>
                    <a href="{{ route('admin.admin.dashboard') }}" class="bg-white/10 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 inline-flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 border border-white/20">
                        <i class="ti ti-arrow-right mr-2"></i>
                        العودة للوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
        <!-- Total Testimonials -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">إجمالي الآراء</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent text-center">{{ number_format($testimonialsStats['total'] ?? 0) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-message-circle text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center text-purple-600 font-medium" style="font-size: 1.12rem;">
                <i class="ti ti-trending-up mr-2 text-lg"></i>
                <span>جميع آراء العملاء</span>
            </div>
        </div>

        <!-- Approved Testimonials -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">معتمدة</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent text-center">{{ number_format($testimonialsStats['approved'] ?? 0) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-check text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center text-green-600 font-medium" style="font-size: 1.12rem;">
                <i class="ti ti-trending-up mr-2 text-lg"></i>
                <span>آراء معتمدة</span>
            </div>
        </div>

        <!-- Pending Testimonials -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">في الانتظار</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent text-center">{{ number_format($testimonialsStats['pending'] ?? 0) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-clock text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center text-yellow-600 font-medium" style="font-size: 1.12rem;">
                <i class="ti ti-clock mr-2 text-lg"></i>
                <span>في انتظار المراجعة</span>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">متوسط التقييم</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent text-center">{{ $testimonialsStats['average_rating'] ?? 0 }}/5</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-star text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center text-indigo-600 font-medium" style="font-size: 1.12rem;">
                <i class="ti ti-star mr-2 text-lg"></i>
                <span>تقييم العملاء</span>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 mb-10">
        <div class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ti ti-filter text-xl text-white"></i>
            </div>
            <h3 class="font-bold text-gray-900" style="font-size: 1.3rem;">تصفية الآراء</h3>
        </div>
        
        <form method="GET" action="{{ route('admin.testimonials') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block font-semibold text-gray-700 mb-2" style="font-size: 1.3rem;">البحث</label>
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="البحث في الآراء..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    />
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="ti ti-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-2" style="font-size: 1.3rem;">الحالة</label>
                <select
                    name="status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    style="font-size: 1.1rem;"
                >
                    <option value="">جميع الحالات</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمدة</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>مميزة</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-gray-700 mb-2" style="font-size: 1.3rem;">التقييم</label>
                <select
                    name="rating"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    style="font-size: 1.1rem;"
                >
                    <option value="">جميع التقييمات</option>
                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 نجوم</option>
                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 نجوم</option>
                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 نجوم</option>
                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 نجوم</option>
                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 نجمة</option>
                </select>
            </div>
            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    <i class="ti ti-filter mr-2"></i>
                    تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- Testimonials Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
        <div class="px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ti ti-list text-xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-900" style="font-size: 1.18rem;">قائمة الآراء</h3>
                </div>
                <div class="text-gray-500 bg-gray-100 px-4 py-2 rounded-xl" style="font-size: 1.3rem;">
                    عرض {{ $testimonials->count() }} من {{ $testimonials->total() }} رأي
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">المستخدم</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">التقييم</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">التعليق</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">التاريخ</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider" style="font-size: 1.18rem;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($testimonials as $testimonial)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($testimonial->user_image_url)
                                                <img
                                                    src="{{ $testimonial->user_image_url }}"
                                                    alt="{{ $testimonial->user_name }}"
                                                    class="h-12 w-12 rounded-full object-cover border-2 border-gray-200"
                                                />
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center border-2 border-gray-200">
                                                    <span class="text-sm font-bold text-white">{{ substr($testimonial->user_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mr-4">
                                            <div class="font-semibold text-gray-900" style="font-size: 1.3rem;">{{ $testimonial->user_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star{{ $i <= $testimonial->rating ? '-filled' : '' }} text-lg {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="mr-3 font-medium text-gray-600" style="font-size: 1.3rem;">({{ $testimonial->rating }}/5)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 max-w-xs" style="font-size: 1.3rem;">
                                        <p class="truncate">{{ Str::limit($testimonial->comment, 80) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($testimonial->is_featured)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="ti ti-star-filled mr-1"></i>مميزة
                                        </span>
                                    @elseif($testimonial->is_approved)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <i class="ti ti-check mr-1"></i>معتمدة
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="ti ti-clock mr-1"></i>في الانتظار
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500" style="font-size: 1.3rem;">
                                    <div class="flex items-center">
                                        <i class="ti ti-calendar mr-2"></i>
                                        {{ $testimonial->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        <a
                                            href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                            class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200"
                                            title="تعديل"
                                        >
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        @if(!$testimonial->is_approved)
                                            <button
                                                onclick="event.stopPropagation(); approveTestimonial({{ $testimonial->id }})"
                                                class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200"
                                                title="اعتماد"
                                            >
                                                <i class="ti ti-check"></i>
                                            </button>
                                        @else
                                            <button
                                                onclick="event.stopPropagation(); rejectTestimonial({{ $testimonial->id }})"
                                                class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                                title="رفض"
                                            >
                                                <i class="ti ti-x"></i>
                                            </button>
                                        @endif
                                        <button
                                            onclick="event.stopPropagation(); toggleFeatured({{ $testimonial->id }})"
                                            class="text-purple-600 hover:text-purple-900 p-2 rounded-lg hover:bg-purple-50 transition-colors duration-200"
                                            title="{{ $testimonial->is_featured ? 'إلغاء التمييز' : 'تمييز' }}"
                                        >
                                            <i class="ti ti-star"></i>
                                        </button>
                                        <button
                                            onclick="event.stopPropagation(); deleteTestimonial({{ $testimonial->id }})"
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                            title="حذف"
                                        >
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="ti ti-message-circle text-3xl text-gray-400"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">لا توجد آراء</p>
                                        <p class="text-gray-400 text-sm mt-1">ابدأ بإضافة آراء جديدة للعملاء</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($testimonials->hasPages())
                <div class="mt-8 flex items-center justify-between">
                    <div class="text-xl text-gray-700 bg-gray-100 px-4 py-2 rounded-xl">
                        عرض {{ $testimonials->firstItem() }} إلى {{ $testimonials->lastItem() }} من {{ $testimonials->total() }} نتيجة
                    </div>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        {{ $testimonials->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 w-11/12 max-w-3xl shadow-2xl rounded-3xl bg-white overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                        <i class="ti ti-edit text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">تعديل الرأي</h3>
                </div>
                <button onclick="closeEditModal()" class="text-white hover:text-gray-200 transition-colors duration-200 p-2 rounded-xl hover:bg-white/10">
                    <i class="ti ti-x text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-8">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xl font-semibold text-gray-700 mb-3">اسم المستخدم</label>
                        <input 
                            type="text" 
                            name="user_name" 
                            id="edit_user_name" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-xl font-semibold text-gray-700 mb-3">التقييم</label>
                        <select 
                            name="rating" 
                            id="edit_rating" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200" 
                            required
                        >
                            <option value="1">⭐ 1 نجمة</option>
                            <option value="2">⭐⭐ 2 نجمة</option>
                            <option value="3">⭐⭐⭐ 3 نجمة</option>
                            <option value="4">⭐⭐⭐⭐ 4 نجمة</option>
                            <option value="5">⭐⭐⭐⭐⭐ 5 نجمة</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-xl font-semibold text-gray-700 mb-3">التعليق</label>
                    <textarea 
                        name="comment" 
                        id="edit_comment" 
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none" 
                        required
                        placeholder="اكتب تعليق العميل هنا..."
                    ></textarea>
                </div>
                
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                        <input 
                            type="checkbox" 
                            name="is_approved" 
                            id="edit_is_approved" 
                            class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="edit_is_approved" class="mr-3 block text-sm font-semibold text-green-800">
                            <i class="ti ti-check mr-1"></i>معتمدة
                        </label>
                    </div>
                    <div class="flex items-center p-4 bg-purple-50 rounded-xl border border-purple-200">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            id="edit_is_featured" 
                            class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        >
                        <label for="edit_is_featured" class="mr-3 block text-sm font-semibold text-purple-800">
                            <i class="ti ti-star mr-1"></i>مميزة
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-8">
                    <button 
                        type="button" 
                        onclick="closeEditModal()" 
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 font-semibold"
                    >
                        <i class="ti ti-x mr-2"></i>إلغاء
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition-all duration-200 font-semibold"
                    >
                        <i class="ti ti-check mr-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-open edit modal if edit parameter is present
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit');
    if (editId) {
        editTestimonial(editId);
        // Remove the edit parameter from URL
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});

function editTestimonial(id) {
  // Fetch testimonial data
  fetch(`/api/testimonials/${id}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const testimonial = data.data;
        document.getElementById('edit_user_name').value = testimonial.user_name;
        document.getElementById('edit_rating').value = testimonial.rating;
        document.getElementById('edit_comment').value = testimonial.comment;
        document.getElementById('edit_is_approved').checked = testimonial.is_approved;
        document.getElementById('edit_is_featured').checked = testimonial.is_featured;
        
        document.getElementById('editForm').action = `/admin/testimonials/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('حدث خطأ في تحميل البيانات');
    });
}

function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}

function approveTestimonial(id) {
  if (confirm('هل أنت متأكد من اعتماد هذا الرأي؟')) {
    fetch(`/api/admin/testimonials/${id}/approve`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('حدث خطأ في اعتماد الرأي');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('حدث خطأ في اعتماد الرأي');
    });
  }
}

function rejectTestimonial(id) {
  if (confirm('هل أنت متأكد من رفض هذا الرأي؟')) {
    fetch(`/api/admin/testimonials/${id}/reject`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('حدث خطأ في رفض الرأي');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('حدث خطأ في رفض الرأي');
    });
  }
}

function toggleFeatured(id) {
  fetch(`/api/admin/testimonials/${id}/toggle-featured`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    },
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert('حدث خطأ في تغيير حالة التمييز');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('حدث خطأ في تغيير حالة التمييز');
  });
}

function deleteTestimonial(id) {
  if (confirm('هل أنت متأكد من حذف هذا الرأي؟')) {
    fetch(`/api/admin/testimonials/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type': 'application/json',
      },
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('حدث خطأ في حذف الرأي');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('حدث خطأ في حذف الرأي');
    });
  }
}
</script>
    </div>
@endsection
