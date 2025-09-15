<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - ASTA</title>
    <!-- Bootstrap (for some admin pages using Bootstrap classes) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/dash.css', 'resources/js/dash/assets.js'])
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --asta-primary: #0ea5e9;
            --asta-secondary: #0891b2;
            --asta-accent: #06b6d4;
            --asta-teal: #14b8a6;
            --asta-blue: #3b82f6;
            --asta-dark: #1e40af;
        }
    </style>
</head>
<body class="bg-gray-50 font-arabic">
    <div class="flex min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100">
        <!-- Sidebar -->
        <aside class="w-80 bg-white/80 backdrop-blur-xl border-e border-white/20 sticky top-0 h-screen flex flex-col shadow-2xl">
            <!-- Logo & Header -->
            <div class="p-8 border-b border-white/20 bg-gradient-to-r from-teal-500/10 to-blue-600/10">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center p-2 shadow-lg border border-gray-200">
                        <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent">لوحة التحكم</h1>
                        <p class="text-gray-600 font-medium" style="font-size: 1.3rem;">نظام إدارة التعلم الذكي</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto p-6" x-data="sidebarState()" x-init="scrollActiveIntoView()">
                <!-- نظرة عامة -->
                <div class="mb-8">
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center space-x-4 rtl:space-x-reverse px-6 py-4 text-sm font-semibold rounded-2xl transition-all duration-300 cursor-pointer {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 shadow-xl scale-105' : 'text-gray-700 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:via-cyan-500 hover:to-blue-600 hover:shadow-lg hover:scale-105' }}"
                       @click="activeSection = 'dashboard'">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                            <i class="ti ti-dashboard text-xl"></i>
                        </div>
                        <span class="text-xl">نظرة عامة</span>
                        <div class="mr-auto">
                            <div class="w-2 h-2 rounded-full {{ request()->routeIs('dashboard') ? 'bg-white' : 'bg-transparent group-hover:bg-white' }} transition-all duration-300"></div>
                        </div>
                    </a>
                </div>

                <!-- إدارة المستخدمين -->
                @if(auth()->user()->can('users.read') || auth()->user()->can('instructors.read') || auth()->user()->can('instructor-applications.read') || auth()->user()->can('roles-permissions.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>إدارة المستخدمين</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @can('users.read')
                        <a href="{{ route('users.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('users.*') ? 'text-white bg-gradient-to-r from-teal-500 to-cyan-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-cyan-600 hover:shadow-md' }}"
                           @click="activeSection = 'users'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('users.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-users text-lg"></i>
                            </div>
                            <span>جميع المستخدمين</span>
                        </a>
                        @endcan
                        @if (Route::has('active-users.index') && auth()->user()->can('users.read'))
                        <!-- <a href="{{ route('active-users.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('active-users.*') ? 'text-white bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'active-users'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('active-users.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-user-check text-lg"></i>
                            </div>
                            <span>المستخدمون النشطون</span>
                        </a> -->
                        @endif
                        @can('instructors.read')
                        <a href="{{ route('instructors.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('instructors.*') ? 'text-white bg-gradient-to-r from-cyan-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-cyan-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'instructors'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('instructors.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-school text-lg"></i>
                            </div>
                            <span>المحاضرين</span>
                        </a>
                        @endcan
                        @if (Route::has('instructor-applications.index') && auth()->user()->can('instructor-applications.read'))
                        <a href="{{ route('instructor-applications.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('instructor-applications.*') ? 'text-white bg-gradient-to-r from-teal-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'instructor-applications'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('instructor-applications.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-id-badge text-lg"></i>
                            </div>
                            <span>طلبات المحاضرين</span>
                        </a>
                        @endif
                        @if (Route::has('roles.index') && auth()->user()->can('roles-permissions.read'))
                        <a href="{{ route('roles.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('roles.*') ? 'text-white bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-indigo-500 hover:to-purple-600 hover:shadow-md' }}"
                           @click="activeSection = 'roles'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('roles.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-lock text-lg"></i>
                            </div>
                            <span>الأدوار والصلاحيات</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- إدارة الدورات -->
                @if(auth()->user()->can('courses.read') || auth()->user()->can('categories.read') || auth()->user()->can('course-levels.read') || auth()->user()->can('topics.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-cyan-500 to-blue-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>إدارة الدورات</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @can('courses.read')
                        <a href="{{ route('courses.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('courses.index') ? 'text-white bg-gradient-to-r from-cyan-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-cyan-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'courses'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('courses.index') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-book text-lg"></i>
                            </div>
                            <span>جميع الدورات</span>
                        </a>
                        @endcan
                        @can('courses.create')
                        <a href="{{ route('courses.create') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('courses.create') ? 'text-white bg-gradient-to-r from-teal-500 to-cyan-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-cyan-600 hover:shadow-md' }}"
                           @click="activeSection = 'courses.create'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('courses.create') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-plus text-lg"></i>
                            </div>
                            <span>إضافة دورة جديدة</span>
                        </a>
                        @endcan
                        @can('categories.read')
                        <a href="{{ route('categories.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('categories.*') ? 'text-white bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:shadow-md' }}"
                           @click="activeSection = 'categories'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('categories.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-tags text-lg"></i>
                            </div>
                            <span>الأقسام</span>
                        </a>
                        <a href="{{ route('specializations.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('specializations.*') ? 'text-white bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:shadow-md' }}"
                           @click="activeSection = 'specializations'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('specializations.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-list-details text-lg"></i>
                            </div>
                            <span>التخصصات</span>
                        </a>
                        @endcan
                        @if (Route::has('course-levels.index') && auth()->user()->can('course-levels.read'))
                        <a href="{{ route('course-levels.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('course-levels.*') ? 'text-white bg-gradient-to-r from-teal-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'course-levels'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('course-levels.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-stairs-up text-lg"></i>
                            </div>
                            <span>مسارات الدورات</span>
                        </a>
                        @endif
                        @if (Route::has('topics.index') && auth()->user()->can('topics.read'))
                        <a href="{{ route('topics.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('topics.*') ? 'text-white bg-gradient-to-r from-cyan-500 to-indigo-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-cyan-500 hover:to-indigo-600 hover:shadow-md' }}"
                           @click="activeSection = 'topics'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('topics.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-list-details text-lg"></i>
                            </div>
                            <span>الموضوعات</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- الكوبونات والخصومات -->
                @if(auth()->user()->can('coupons.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-emerald-500 to-teal-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>الكوبونات والخصومات</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @php($couponsIndex = Route::has('coupons.index') ? route('coupons.index') : '#')
                        <a href="{{ $couponsIndex }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ (request()->routeIs('coupons.index') || request()->routeIs('coupons.show') || request()->routeIs('coupons.edit')) ? 'text-white bg-gradient-to-r from-emerald-500 to-teal-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-600 hover:shadow-md' }}"
                           @click="activeSection = 'coupons'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('coupons.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-ticket text-lg"></i>
                            </div>
                            <span>قسائم الخصم</span>
                        </a>
                        @can('coupons.create')
                        @php($couponsCreate = Route::has('coupons.create') ? route('coupons.create') : '#')
                        <a href="{{ $couponsCreate }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('coupons.create') ? 'text-white bg-gradient-to-r from-teal-600 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-600 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'coupons.create'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('coupons.create') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-square-plus text-lg"></i>
                            </div>
                            <span>إنشاء كوبون جديد</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- المسارات المهنية -->
                @if(auth()->user()->can('degrees.read') || auth()->user()->can('learning-paths.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-yellow-500 to-orange-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>المسارات المهنية</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @can('degrees.read')
                        <a href="{{ route('degrees.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('degrees.*') ? 'text-white bg-gradient-to-r from-yellow-500 to-orange-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-yellow-500 hover:to-orange-600 hover:shadow-md' }}"
                           @click="activeSection = 'degrees'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('degrees.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-graduation-cap text-lg"></i>
                            </div>
                            <span>المسارات المهنية</span>
                        </a>
                        @endcan
                        @can('learning-paths.read')
                        <!-- <a href="{{ route('learning-paths.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('learning-paths.*') ? 'text-white bg-gradient-to-r from-cyan-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-cyan-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'learning-paths'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('learning-paths.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-route text-lg"></i>
                            </div>
                            <span>المسارات التعليمية</span>
                        </a> -->
                        @endcan
                        <a href="{{ route('career-levels.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('career-levels.*') ? 'text-white bg-gradient-to-r from-teal-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'career-levels'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('career-levels.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-stairs text-lg"></i>
                            </div>
                            <span>المستويات المهنية</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- الشهادات -->
                @if (Route::has('certificates.index') && auth()->user()->can('certificates.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-green-500 to-emerald-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>الشهادات</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('certificates.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('certificates.*') ? 'text-white bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'certificates'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('certificates.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-certificate text-lg"></i>
                            </div>
                            <span>إدارة الشهادات</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- التعليقات والآراء -->
                @if(auth()->user()->can('reviews.read') || auth()->user()->can('testimonials.read') || auth()->user()->can('comments.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-rose-500 to-pink-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>التعليقات والآراء</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @if (Route::has('reviews.index') && auth()->user()->can('reviews.read'))
                        <a href="{{ route('reviews.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('reviews.*') ? 'text-white bg-gradient-to-r from-rose-500 to-pink-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-rose-500 hover:to-pink-600 hover:shadow-md' }}"
                           @click="activeSection = 'reviews'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('reviews.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-message-dots text-lg"></i>
                            </div>
                            <span>التعليقات</span>
                        </a>
                        @endif
                        @if (Route::has('admin.testimonials.index') && auth()->user()->can('testimonials.read'))
                        <a href="{{ route('admin.testimonials.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('testimonials.*') ? 'text-white bg-gradient-to-r from-fuchsia-500 to-purple-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-fuchsia-500 hover:to-purple-600 hover:shadow-md' }}"
                           @click="activeSection = 'testimonials'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('testimonials.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-quote text-lg"></i>
                            </div>
                            <span>الآراء</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- الملفات / مكتبة الوسائط -->
                @if (Route::has('file-uploads.index') && auth()->user()->can('files.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-sky-500 to-cyan-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>الملفات</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('file-uploads.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('file-uploads.*') ? 'text-white bg-gradient-to-r from-sky-500 to-cyan-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-sky-500 hover:to-cyan-600 hover:shadow-md' }}"
                           @click="activeSection = 'files'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('file-uploads.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-folder text-lg"></i>
                            </div>
                            <span>مكتبة الملفات</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- المحتوى (CMS خفيف) -->
                <!-- <div class="mb-8">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-gray-500 to-slate-600 rounded-full"></div>
                        <h3 class="relative text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4">المحتوى</h3>
                    </div>
                    <div class="space-y-2">
                        @if (Route::has('pages.index'))
                        <a href="{{ route('pages.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('pages.*') ? 'text-white bg-gradient-to-r from-gray-500 to-slate-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-slate-600 hover:shadow-md' }}"
                           @click="activeSection = 'pages'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('pages.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-file-description text-lg"></i>
                            </div>
                            <span>الصفحات</span>
                        </a>
                        @endif
                        @if (Route::has('banners.index'))
                        <a href="{{ route('banners.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('banners.*') ? 'text-white bg-gradient-to-r from-slate-600 to-gray-700 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-slate-600 hover:to-gray-700 hover:shadow-md' }}"
                           @click="activeSection = 'banners'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('banners.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-photo text-lg"></i>
                            </div>
                            <span>البانرات</span>
                        </a>
                        @endif
                    </div>
                </div> -->

                <!-- الدعم -->
                <!-- <div class="mb-8">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-red-400 to-red-600 rounded-full"></div>
                        <h3 class="relative text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4">الدعم</h3>
                    </div>
                    <div class="space-y-2">
                        @if (Route::has('tickets.index'))
                        <a href="{{ route('tickets.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('tickets.*') ? 'text-white bg-gradient-to-r from-red-500 to-rose-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-rose-600 hover:shadow-md' }}"
                           @click="activeSection = 'tickets'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('tickets.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-headset text-lg"></i>
                            </div>
                            <span>تذاكر الدعم</span>
                        </a>
                        @endif
                        @if (Route::has('activity-log.index'))
                        <a href="{{ route('activity-log.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('activity-log.*') ? 'text-white bg-gradient-to-r from-orange-500 to-amber-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-amber-600 hover:shadow-md' }}"
                           @click="activeSection = 'activity-log'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('activity-log.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-clipboard-text text-lg"></i>
                            </div>
                            <span>سجل النشاط</span>
                        </a>
                        @endif
                    </div>
                </div> -->

                <!-- الاختبارات والتقييم -->
                @if(auth()->user()->can('assessments.read') || auth()->user()->can('quizzes.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-red-500 to-pink-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>الاختبارات والتقييم</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @can('assessments.read')
                        <a href="{{ route('assessments.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('assessments.*') ? 'text-white bg-gradient-to-r from-red-500 to-pink-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 hover:shadow-md' }}"
                           @click="activeSection = 'assessments'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('assessments.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-help-circle text-lg"></i>
                            </div>
                            <span>الاختبارات</span>
                        </a>
                        @endcan
                        @can('assessments.read')
                        <a href="{{ route('question-bank.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('question-bank.*') ? 'text-white bg-gradient-to-r from-orange-500 to-red-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 hover:shadow-md' }}"
                           @click="activeSection = 'question-bank'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('question-bank.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-database text-lg"></i>
                            </div>
                            <span>بنك الأسئلة</span>
                        </a>
                        @endcan
                        @can('quizzes.read')
                        <a href="{{ route('quizzes.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('quizzes.*') ? 'text-white bg-gradient-to-r from-violet-500 to-purple-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-violet-500 hover:to-purple-600 hover:shadow-md' }}"
                           @click="activeSection = 'quizzes'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('quizzes.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-brain text-lg"></i>
                            </div>
                            <span>اختبارات القبول</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- إدارة الطلاب -->
                @if(auth()->user()->can('enrollments.read') || auth()->user()->can('student-progress.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-lime-500 to-green-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>إدارة الطلاب</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        @can('enrollments.read')
                        <a href="{{ route('enrollments.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('enrollments.*') ? 'text-white bg-gradient-to-r from-lime-500 to-green-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-lime-500 hover:to-green-600 hover:shadow-md' }}"
                           @click="activeSection = 'enrollments'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('enrollments.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-user-check text-lg"></i>
                            </div>
                            <span>التسجيلات</span>
                        </a>
                        @endcan
                        @can('student-progress.read')
                        <a href="{{ route('student-progress.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('student-progress.*') ? 'text-white bg-gradient-to-r from-sky-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-sky-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'student-progress'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('student-progress.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-chart-line text-lg"></i>
                            </div>
                            <span>تقدم الطلاب</span>
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- التحليلات -->
                @if(auth()->user()->can('analytics.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>التحليلات</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('assessments.general-analytics') }}" class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-2.5 text-xl font-medium text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-pink-500 hover:to-rose-600 hover:shadow-md rounded-lg transition-all duration-300 cursor-pointer">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center bg-gray-100 group-hover:bg-white/20 transition-all duration-300">
                                <i class="ti ti-chart-pie text-sm"></i>
                            </div>
                            <span>تحليلات الاختبارات</span>
                        </a>
                        <a href="{{ route('course-materials.analytics') }}" class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-2.5 text-xl font-medium text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-amber-500 hover:to-yellow-600 hover:shadow-md rounded-lg transition-all duration-300 cursor-pointer">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center bg-gray-100 group-hover:bg-white/20 transition-all duration-300">
                                <i class="ti ti-file-text text-sm"></i>
                            </div>
                            <span>تحليلات المواد</span>
                        </a>
                        <a href="{{ route('quizzes.general-analytics') }}" class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-2.5 text-xl font-medium text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-emerald-500 hover:to-teal-600 hover:shadow-md rounded-lg transition-all duration-300 cursor-pointer">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center bg-gray-100 group-hover:bg-white/20 transition-all duration-300">
                                <i class="ti ti-brain text-sm"></i>
                            </div>
                            <span>تحليلات اختبارات القبول</span>
                        </a>
                        <a href="{{ route('enrollments.analytics') }}" class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-2.5 text-xl font-medium text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-cyan-600 hover:shadow-md rounded-lg transition-all duration-300 cursor-pointer">
                            <div class="w-6 h-6 rounded-md flex items-center justify-center bg-gray-100 group-hover:bg-white/20 transition-all duration-300">
                                <i class="ti ti-users text-sm"></i>
                            </div>
                            <span>تحليلات التسجيلات</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- إعدادات النظام -->
                @if(auth()->user()->can('system-settings.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-slate-500 to-gray-500 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>إعدادات النظام</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('partners.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('partners.*') ? 'text-white bg-gradient-to-r from-slate-500 to-gray-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-slate-500 hover:to-gray-600 hover:shadow-md' }}"
                           @click="activeSection = 'partners'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('partners.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-handshake text-lg"></i>
                            </div>
                            <span>الشركاء</span>
                        </a>
                        <a href="{{ route('system-settings.info') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('system-settings.*') ? 'text-white bg-gradient-to-r from-gray-500 to-slate-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-slate-600 hover:shadow-md' }}"
                           @click="activeSection = 'system-settings'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('system-settings.*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-settings text-lg"></i>
                            </div>
                            <span>معلومات النظام</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- التقارير / Reports -->
                @if(auth()->user()->can('system-settings.read'))
                <div class="mb-8" x-data="{open:true}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>التقارير</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('admin.reports.sales') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.sales') ? 'text-white bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'sales'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.sales') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-chart-line text-lg"></i>
                            </div>
                            <span>المبيعات</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.enrollments') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.enrollments') ? 'text-white bg-gradient-to-r from-blue-500 to-cyan-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-cyan-600 hover:shadow-md' }}"
                           @click="activeSection = 'enrollments'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.enrollments') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-users text-lg"></i>
                            </div>
                            <span>التسجيلات</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.payments') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.payments') ? 'text-white bg-gradient-to-r from-yellow-500 to-orange-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-yellow-500 hover:to-orange-600 hover:shadow-md' }}"
                           @click="activeSection = 'payments'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.payments') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-credit-card text-lg"></i>
                            </div>
                            <span>المدفوعات</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.attendance') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.attendance') ? 'text-white bg-gradient-to-r from-purple-500 to-pink-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-600 hover:shadow-md' }}"
                           @click="activeSection = 'attendance'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.attendance') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-calendar-check text-lg"></i>
                            </div>
                            <span>الحضور</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.course-wise-enrollments') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.course-wise-enrollments') ? 'text-white bg-gradient-to-r from-teal-500 to-blue-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-teal-500 hover:to-blue-600 hover:shadow-md' }}"
                           @click="activeSection = 'course-wise-enrollments'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.course-wise-enrollments') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-book text-lg"></i>
                            </div>
                            <span>تسجيلات الدورات</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.promo-code-statistics') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.promo-code-statistics') ? 'text-white bg-gradient-to-r from-red-500 to-pink-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 hover:shadow-md' }}"
                           @click="activeSection = 'promo-code-statistics'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.promo-code-statistics') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-discount text-lg"></i>
                            </div>
                            <span>إحصائيات الكوبونات</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.bandwidth-consumption') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.bandwidth-consumption') ? 'text-white bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-indigo-500 hover:to-purple-600 hover:shadow-md' }}"
                           @click="activeSection = 'bandwidth-consumption'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.bandwidth-consumption') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-network text-lg"></i>
                            </div>
                            <span>استهلاك النطاق</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.storage-consumption') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.storage-consumption') ? 'text-white bg-gradient-to-r from-gray-500 to-slate-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-slate-600 hover:shadow-md' }}"
                           @click="activeSection = 'storage-consumption'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.storage-consumption') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-server text-lg"></i>
                            </div>
                            <span>استهلاك التخزين</span>
                        </a>
                        
                        <a href="{{ route('admin.reports.scheduled-tasks') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.reports.scheduled-tasks') ? 'text-white bg-gradient-to-r from-emerald-500 to-green-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-emerald-500 hover:to-green-600 hover:shadow-md' }}"
                           @click="activeSection = 'scheduled-tasks'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.reports.scheduled-tasks') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-clock text-lg"></i>
                            </div>
                            <span>المهام المجدولة</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- الماركتينج / Marketing -->
                @if(auth()->user()->can('system-settings.read'))
                <div class="mb-8" x-data="{open:false}">
                    <div class="relative mb-4 px-2">
                        <div class="absolute inset-y-0 right-0 w-1 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></div>
                        <button class="relative w-full text-right text-2xl font-bold text-gray-700 uppercase tracking-wider pr-4 flex items-center justify-between" @click="open=!open">
                            <span>الماركتينج</span>
                            <i class="ti ti-chevron-down text-base transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>
                    <div class="space-y-2" x-show="open" x-transition>
                        <a href="{{ route('admin.marketing.index') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.marketing.index') ? 'text-white bg-gradient-to-r from-purple-500 to-pink-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-600 hover:shadow-md' }}"
                           @click="activeSection = 'marketing-dashboard'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.marketing.index') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-dashboard text-lg"></i>
                            </div>
                            <span>لوحة الماركتينج</span>
                        </a>
                        
                        <a href="{{ route('admin.marketing.create') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.marketing.create') ? 'text-white bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-indigo-600 hover:shadow-md' }}"
                           @click="activeSection = 'marketing-create'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.marketing.create') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-plus text-lg"></i>
                            </div>
                            <span>إنشاء حملة جديدة</span>
                        </a>
                        
                        <a href="{{ route('admin.marketing.templates') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.marketing.templates') ? 'text-white bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-emerald-600 hover:shadow-md' }}"
                           @click="activeSection = 'marketing-templates'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.marketing.templates') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-template text-lg"></i>
                            </div>
                            <span>قوالب الرسائل</span>
                        </a>
                        
                        <a href="{{ route('admin.marketing.analytics') }}" 
                           class="group flex items-center space-x-4 rtl:space-x-reverse px-4 py-3 text-xl font-medium rounded-xl transition-all duration-300 cursor-pointer {{ request()->routeIs('admin.marketing.analytics') ? 'text-white bg-gradient-to-r from-orange-500 to-red-600 shadow-lg' : 'text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-orange-500 hover:to-red-600 hover:shadow-md' }}"
                           @click="activeSection = 'marketing-analytics'">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ request()->routeIs('admin.marketing.analytics') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-white/20' }} transition-all duration-300">
                                <i class="ti ti-chart-bar text-lg"></i>
                            </div>
                            <span>تحليلات الحملات</span>
                        </a>
                    </div>
                </div>
                @endif

            </nav>

            <!-- Profile Block - Pinned to Bottom -->
            <div class="mt-auto p-6 border-t border-white/20 bg-gradient-to-r from-blue-600/5 to-purple-600/5">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center p-2 shadow-lg">
                        <img src="{{ asset('images/asta-logo.png') }}" alt="ASTA" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate" style="font-size: 1.3rem;">أحمد محمد علي</p>
                        <p class="text-gray-600 truncate" style="font-size: 1.1rem;">admin@asta.com</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-3 rounded-xl transition-all duration-300 cursor-pointer group"
                                title="تسجيل الخروج">
                            <i class="ti ti-logout text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 px-8 py-8">
            @yield('content')
        </main>
    </div>

    <!-- Custom Styles -->
    <style>
        /* Custom scrollbar for sidebar */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        
        nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        nav::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 3px;
        }
        
        nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #2563eb, #7c3aed);
        }
        
        /* Smooth animations */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Glassmorphism effect */
        .backdrop-blur-xl {
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }
        
        /* Gradient text */
        .bg-clip-text {
            -webkit-background-clip: text;
            background-clip: text;
        }
        
        /* Hover effects */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }
        
        .group:hover .group-hover\:bg-white\/20 {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Active state indicator */
        .active-indicator {
            position: relative;
        }
        
        .active-indicator::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 2px;
        }

        /* Ensure code/LTR content uses Arial to show English numerals */
        code, pre, kbd, samp,
        .text-ltr, .email-content,
        [dir="ltr"],
        .en-num,
        input[type="email"], input[type="url"], input[type="number"], textarea.text-ltr {
            font-family: Arial, sans-serif !important;
        }
        /* Utility to force LTR direction when needed */
        .text-ltr { direction: ltr; unicode-bidi: bidi-override; }

        /* Code fields: force Arial and LTR to keep English numerals */
        .code-text, .code-badge, .font-mono,
        [data-code="true"],
        input#code, input[name="code"],
        .code-input {
            font-family: Arial, sans-serif !important;
            direction: ltr;
            unicode-bidi: bidi-override;
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap JS (Modal, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function sidebarState(){
        return {
          scrollActiveIntoView(){
            const nav = document.querySelector('aside nav');
            const active = nav.querySelector('.text-white.bg-gradient-to-r');
            if(active){
              // Place active item at the top of the view
              const targetTop = active.offsetTop - 20; // 20px padding from top
              nav.scrollTop = Math.max(0, targetTop);
            }
          }
        }
      }
    </script>
    
    <!-- Session Timeout Manager -->
    <script src="{{ asset('js/session-timeout.js') }}"></script>
    <meta name="user-authenticated" content="true">
    
    @stack('scripts')
</body>
</html>
