@extends('layouts.dash')

@section('title', 'نظرة عامة')

@section('content')
    <!-- Welcome Header -->
    <div class="mb-10 font-arabic">
        <div class="relative bg-gradient-to-br from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-10 text-white overflow-hidden shadow-2xl">
            <!-- Background decorative elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-16 -translate-x-16"></div>
            <div class="absolute top-1/2 left-1/2 w-24 h-24 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-2 h-8 bg-white/30 rounded-full"></div>
                        <h1 class="text-4xl font-bold text-white drop-shadow-lg">مرحباً بك في لوحة التحكم</h1>
                    </div>
                    <p class="text-cyan-100 text-xl font-medium">نظرة شاملة على أداء النظام والإحصائيات المهمة</p>
                    <div class="flex items-center space-x-4 rtl:space-x-reverse text-cyan-100">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <i class="ti ti-calendar text-xl"></i>
                            <span style="font-size: 1.3rem;" id="current-date">{{ now()->format('Y-n-j') }}</span>
                        </div>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <i class="ti ti-clock text-xl"></i>
                            <span style="font-size: 1.3rem;" id="current-time">{{ now()->format('h:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white rounded-3xl flex items-center justify-center p-4 shadow-xl border border-gray-200">
                        <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10 font-arabic">
        <!-- Total Users -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">إجمالي المستخدمين</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent text-center">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-users text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center justify-center text-teal-600 font-medium" style="font-size: 1.19rem;">
                <i class="ti ti-trending-up ml-2 text-lg"></i>
                <span>+%12 من الشهر الماضي</span>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">إجمالي الدورات</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent text-center">{{ number_format($totalCourses) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-book text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center justify-center text-cyan-600 font-medium" style="font-size: 1.19rem;">
                <i class="ti ti-trending-up ml-2 text-lg"></i>
                <span>+%8 من الشهر الماضي</span>
            </div>
        </div>

        <!-- Total Instructors -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">إجمالي المحاضرين</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent text-center">{{ number_format($totalInstructors) }}</p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-school text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center justify-center text-blue-600 font-medium" style="font-size: 1.19rem;">
                <i class="ti ti-trending-up ml-2 text-lg"></i>
                <span>+%5 من الشهر الماضي</span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="group bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <div class="text-right flex-1">
                    <p class="text-xl font-semibold text-gray-600 mb-2 text-center">إجمالي الإيرادات</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-blue-700 bg-clip-text text-transparent text-center">
                        {{ number_format($totalEnrollments * 29.99) }}
                        <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-8 h-8 inline mr-2">
                    </p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 mr-4">
                    <i class="ti ti-currency-dollar text-2xl text-white"></i>
                </div>
            </div>
            <div class="flex items-center justify-center text-indigo-600 font-medium" style="font-size: 1.19rem;">
                <i class="ti ti-trending-up ml-2 text-lg"></i>
                <span>+%15 من الشهر الماضي</span>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
        <!-- Pending Items -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-clock text-xl text-white"></i>
                </div>
                <h3 class="font-bold text-gray-900" style="font-size: 1.1rem;">العناصر المعلقة</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-teal-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-teal-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-book text-teal-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.2rem;">الدورات المعلقة</span>
                    </div>
                    <span class="text-lg font-bold text-teal-600" style="font-size: 1.40rem;">{{ $pendingCourses }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-cyan-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-school text-cyan-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">طلبات المحاضرين</span>
                    </div>
                    <span class="text-lg font-bold text-cyan-600">{{ $pendingApplications }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-star text-blue-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">التقييمات المعلقة</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ $pendingReviews }}</span>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-chart-bar text-xl text-white"></i>
                </div>
                <h3 class="font-bold text-gray-900" style="font-size: 1.1rem;">نظرة عامة على النظام</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-cyan-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-files text-cyan-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">المواد التعليمية</span>
                    </div>
                    <span class="text-lg font-bold text-cyan-600">{{ number_format($totalMaterials) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-help-circle text-blue-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">الاختبارات</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($totalAssessments) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-user-check text-indigo-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">التسجيلات</span>
                    </div>
                    <span class="text-lg font-bold text-indigo-600">{{ number_format($totalEnrollments) }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-trophy text-xl text-white"></i>
                </div>
                <h3 class="font-bold text-gray-900" style="font-size: 1.1rem;">إحصائيات سريعة</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-teal-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-teal-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-certificate text-teal-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">الشهادات</span>
                    </div>
                    <span class="text-lg font-bold text-teal-600">{{ number_format($totalCertificates) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-cyan-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-handshake text-cyan-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">الشركاء</span>
                    </div>
                    <span class="text-lg font-bold text-cyan-600">{{ number_format($totalPartners) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-message-circle text-blue-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">التعليقات</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ number_format($totalComments) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-2xl">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="ti ti-star text-purple-600 text-sm"></i>
                        </div>
                        <span class="font-medium text-gray-700" style="font-size: 1.19rem;">الشهادات</span>
                    </div>
                    <span class="text-lg font-bold text-purple-600">{{ number_format($totalTestimonials) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses & Activities Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <!-- Recent Courses -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ti ti-book text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">أحدث الدورات</h3>
                </div>
                <a href="{{ route('courses.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-semibold bg-teal-50 px-4 py-2 rounded-xl hover:bg-teal-100 transition-all duration-300">
                    عرض الكل
                </a>
            </div>
            
            @if($recentCourses->count() > 0)
                <div class="space-y-4">
                    @foreach($recentCourses->take(4) as $course)
                        <div class="group flex items-center space-x-4 rtl:space-x-reverse p-4 rounded-2xl hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 transition-all duration-300 cursor-pointer">
                            <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <i class="ti ti-book text-white text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate" style="font-size: 1.2rem;">{{ $course->title }}</p>
                                <p class="text-gray-500" style="font-size: 1.3rem;">
                                    {{ $course->instructor ? $course->instructor->name : 'محاضرغير محدد' }} • 
                                    {{ $course->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $course->status === 'approved' ? 'معتمد' : 'معلق' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-book-open text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium" style="font-size: 1.3rem;">لا توجد دورات حديثة</p>
                </div>
            @endif
        </div>

        <!-- Recent Activities -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 hover:shadow-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ti ti-activity text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">النشاطات الأخيرة</h3>
                </div>
                <a href="#" class="text-sm text-cyan-600 hover:text-cyan-700 font-semibold bg-cyan-50 px-4 py-2 rounded-xl hover:bg-cyan-100 transition-all duration-300">
                    عرض الكل
                </a>
            </div>
            
            @if($recentActivities->count() > 0)
                <div class="space-y-4">
                    @foreach($recentActivities->take(4) as $activity)
                        <div class="group flex items-center space-x-4 rtl:space-x-reverse p-4 rounded-2xl hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 transition-all duration-300 cursor-pointer">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                <i class="ti ti-{{ $activity->icon }} text-white text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900" style="font-size: 1.2rem;">{{ $activity->description }}</p>
                                <p class="text-gray-500" style="font-size: 1.3rem;">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-activity text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium" style="font-size: 1.3rem;">لا توجد نشاطات حديثة</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 mb-10 hover:shadow-2xl transition-all duration-300 cursor-pointer font-arabic" onclick="window.location.href='{{ route('admin.testimonials') }}'">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ti ti-star text-xl text-white"></i>
                </div>
                <h3 class="font-bold text-gray-900" style="font-size: 1.19rem;">آراء العملاء والتقييمات</h3>
            </div>
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <span class="text-sm text-purple-600 font-semibold bg-purple-50 px-4 py-2 rounded-xl">
                    إدارة آراء العملاء
                </span>
                <i class="ti ti-arrow-left text-purple-600"></i>
            </div>
        </div>

        <!-- Testimonials Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right flex-1">
                        <p class="font-medium text-purple-600 mb-2 text-center" style="font-size: 1.3rem;">إجمالي الآراء</p>
                        <p class="text-3xl font-bold text-purple-900 text-center">{{ number_format($totalTestimonials) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-message-circle text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right flex-1">
                        <p class="font-medium text-green-600 mb-2 text-center" style="font-size: 1.3rem;">معتمدة</p>
                        <p class="text-3xl font-bold text-green-900 text-center">{{ number_format($approvedTestimonials) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right flex-1">
                        <p class="font-medium text-yellow-600 mb-2 text-center" style="font-size: 1.3rem;">في الانتظار</p>
                        <p class="text-3xl font-bold text-yellow-900 text-center">{{ number_format($pendingTestimonials) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-right flex-1">
                        <p class="font-medium text-blue-600 mb-2 text-center" style="font-size: 1.3rem;">متوسط التقييم</p>
                        <p class="text-3xl font-bold text-blue-900 text-center">{{ $averageRating }}/5</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="ti ti-star-filled text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Testimonials -->
        @if($recentTestimonials->count() > 0)
            <div>
                <h4 class="font-semibold text-gray-900 mb-6" style="font-size: 1.19rem;">أحدث آراء العملاء</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentTestimonials->take(6) as $testimonial)
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="event.stopPropagation(); editTestimonialFromDashboard({{ $testimonial->id }})">
                            <div class="flex items-start space-x-4 rtl:space-x-reverse mb-4">
                                <div class="flex-shrink-0">
                                    @if($testimonial->user_image_url)
                                        <img src="{{ $testimonial->user_image_url }}" alt="{{ $testimonial->user_name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                            <span class="text-white font-semibold text-lg">{{ substr($testimonial->user_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h5 class="font-semibold text-gray-900 truncate" style="font-size: 1.19rem;">{{ $testimonial->user_name }}</h5>
                                    <div class="flex items-center mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ti ti-star{{ $i <= $testimonial->rating ? '-filled' : '' }} text-sm {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="text-sm text-gray-500 mr-2">({{ $testimonial->rating }}/5)</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 leading-relaxed line-clamp-3" style="font-size: 1.3rem;">{{ Str::limit($testimonial->comment, 120) }}</p>
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-500">{{ $testimonial->created_at->diffForHumans() }}</span>
                                @if($testimonial->is_featured)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="ti ti-star-filled mr-1"></i>مميزة
                                    </span>
                                @elseif($testimonial->is_approved)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="ti ti-check mr-1"></i>معتمدة
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="ti ti-clock mr-1"></i>في الانتظار
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i class="ti ti-message-circle text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium" style="font-size: 1.3rem;">لا توجد آراء حديثة</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 p-8 mb-10 font-arabic">
        <div class="flex items-center space-x-3 rtl:space-x-reverse mb-8">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="ti ti-bolt text-xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">إجراءات سريعة</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('courses.create') }}" class="group">
                <div class="p-8 rounded-3xl border-2 border-dashed border-gray-300 hover:border-teal-400 hover:bg-gradient-to-br hover:from-teal-50 hover:to-cyan-50 transition-all duration-300 text-center hover:shadow-xl hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="ti ti-plus text-2xl text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-3 text-lg">إضافة دورة جديدة</h4>
                    <p class="text-xl text-gray-600">إنشاء دورة تعليمية جديدة</p>
                </div>
            </a>

            <a href="{{ route('users.index') }}" class="group">
                <div class="p-8 rounded-3xl border-2 border-dashed border-gray-300 hover:border-cyan-400 hover:bg-gradient-to-br hover:from-cyan-50 hover:to-blue-50 transition-all duration-300 text-center hover:shadow-xl hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="ti ti-user-plus text-2xl text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-3 text-lg">إضافة مستخدم</h4>
                    <p class="text-xl text-gray-600">إنشاء حساب مستخدم جديد</p>
                </div>
            </a>

            <a href="{{ route('instructors.index') }}" class="group">
                <div class="p-8 rounded-3xl border-2 border-dashed border-gray-300 hover:border-blue-400 hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 text-center hover:shadow-xl hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="ti ti-chalkboard-teacher text-2xl text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-3 text-lg">إضافة مدرس</h4>
                    <p class="text-xl text-gray-600">تعيين محاضر جديد</p>
                </div>
            </a>

            <a href="{{ route('analytics.index') }}" class="group">
                <div class="p-8 rounded-3xl border-2 border-dashed border-gray-300 hover:border-indigo-400 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-blue-50 transition-all duration-300 text-center hover:shadow-xl hover:scale-105 cursor-pointer">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="ti ti-chart-bar text-2xl text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 mb-3 text-lg">عرض التقارير</h4>
                    <p class="text-xl text-gray-600">مراجعة الإحصائيات</p>
                </div>
            </a>
        </div>
    </div>

    <!-- System Health & Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- System Performance -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">أداء النظام</h3>
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xl font-medium text-gray-700">استخدام الذاكرة</span>
                        <span class="text-sm font-semibold text-gray-900">65%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-teal-600 h-3 rounded-full transition-all duration-500" style="width: 65%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xl font-medium text-gray-700">استخدام المعالج</span>
                        <span class="text-sm font-semibold text-gray-900">42%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: 42%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xl font-medium text-gray-700">مساحة التخزين</span>
                        <span class="text-sm font-semibold text-gray-900">78%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" style="width: 78%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">حالة النظام</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                    <span class="text-xl text-gray-700">النظام يعمل بشكل طبيعي</span>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                    <span class="text-xl text-gray-700">قاعدة البيانات متصلة</span>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                    <span class="text-xl text-gray-700">الملفات محفوظة</span>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                    <span class="text-xl text-gray-700">النسخ الاحتياطية محدثة</span>
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <span class="text-xl text-gray-700">تحديث النظام متوفر</span>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
function editTestimonialFromDashboard(id) {
    // Redirect to testimonials management page with edit parameter
    window.location.href = `{{ route('admin.testimonials') }}?edit=${id}`;
}

// Real-time clock functionality
function updateDateTime() {
    const now = new Date();
    
    // Format date as YYYY-M-D (single digits for month and day)
    const year = now.getFullYear();
    const month = now.getMonth() + 1; // No padding
    const day = now.getDate(); // No padding
    const formattedDate = `${year}-${month}-${day}`;
    
    // Format time as 12-hour format with Arabic AM/PM
    let hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const ampm = hours >= 12 ? 'مساء' : 'صباحا';
    hours = hours % 12;
    hours = hours ? hours : 12; // 0 should be 12
    const formattedTime = `${hours}:${minutes} ${ampm}`;
    
    // Update the DOM elements with proper text content
    const dateElement = document.getElementById('current-date');
    const timeElement = document.getElementById('current-time');
    
    if (dateElement) {
        dateElement.innerHTML = formattedDate;
    }
    if (timeElement) {
        timeElement.innerHTML = formattedTime;
    }
}

// Update time immediately and then every second
document.addEventListener('DOMContentLoaded', function() {
    updateDateTime();
    setInterval(updateDateTime, 1000);
});
</script>