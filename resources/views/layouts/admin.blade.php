<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ti-icons@1.0.1/css/themify-icons.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-e border-gray-200 sticky top-0 h-screen flex flex-col shadow-sm z-30">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg width="20" height="14" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="white" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">لوحة التحكم</span>
                </a>
            </div>

            <!-- Navigation Wrapper - Scrollable -->
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                   aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <i class="ti ti-dashboard text-lg"></i>
                    <span class="font-medium">نظرة عامة</span>
                </a>

                <!-- User Management Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">إدارة المستخدمين</h3>
                    
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('users.*') ? 'page' : 'false' }}">
                        <i class="ti ti-users text-lg"></i>
                        <span class="font-medium">جميع المستخدمين</span>
                    </a>
                    
                    <a href="{{ route('instructors.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('instructors.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('instructors.*') ? 'page' : 'false' }}">
                        <i class="ti ti-chalkboard-teacher text-lg"></i>
                        <span class="font-medium">المحاضرون</span>
                    </a>
                </div>

                <!-- Course Management Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">إدارة الدورات</h3>
                    
                    <a href="{{ route('courses.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('courses.*') ? 'page' : 'false' }}">
                        <i class="ti ti-book text-lg"></i>
                        <span class="font-medium">الدورات</span>
                    </a>
                    
                    <!-- <a href="{{ route('course-materials.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('course-materials.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('course-materials.*') ? 'page' : 'false' }}">
                        <i class="ti ti-files text-lg"></i>
                        <span class="font-medium">المواد التعليمية</span>
                    </a> -->
                    
                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('categories.*') ? 'page' : 'false' }}">
                        <i class="ti ti-tag text-lg"></i>
                        <span class="font-medium">الأقسام</span>
                    </a>
                    
                    <a href="{{ route('degrees.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('degrees.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('degrees.*') ? 'page' : 'false' }}">
                        <i class="ti ti-graduation-cap text-lg"></i>
                        <span class="font-medium">المسارات المهنية</span>
                    </a>
                    
                    <a href="{{ route('learning-paths.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('learning-paths.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('learning-paths.*') ? 'page' : 'false' }}">
                        <i class="ti ti-route text-lg"></i>
                        <span class="font-medium">المسارات التعليمية</span>
                    </a>
                </div>

                <!-- Assessment & Testing Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">الاختبارات والتقييم</h3>
                    
                    <a href="{{ route('assessments.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('assessments.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('assessments.*') ? 'page' : 'false' }}">
                        <i class="ti ti-help-circle text-lg"></i>
                        <span class="font-medium">الاختبارات</span>
                    </a>
                    
                    <a href="{{ route('quizzes.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('quizzes.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('quizzes.*') ? 'page' : 'false' }}">
                        <i class="ti ti-brain text-lg"></i>
                        <span class="font-medium">الاختبارات السريعة</span>
                    </a>
                </div>

                <!-- Student Management Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">إدارة الطلاب</h3>
                    
                    <a href="{{ route('enrollments.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('enrollments.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('enrollments.*') ? 'page' : 'false' }}">
                        <i class="ti ti-user-check text-lg"></i>
                        <span class="font-medium">التسجيلات</span>
                    </a>
                    
                    <a href="{{ route('student-progress.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('student-progress.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('student-progress.*') ? 'page' : 'false' }}">
                        <i class="ti ti-trending-up text-lg"></i>
                        <span class="font-medium">تقدم الطلاب</span>
                    </a>
                    
                    <a href="{{ route('wishlists.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('wishlists.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('wishlists.*') ? 'page' : 'false' }}">
                        <i class="ti ti-heart text-lg"></i>
                        <span class="font-medium">المفضلة</span>
                    </a>
                </div>

                <!-- Quality & Reviews Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">الجودة والتعليقات</h3>
                    
                    <a href="{{ route('reviews.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('reviews.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('reviews.*') ? 'page' : 'false' }}">
                        <i class="ti ti-star text-lg"></i>
                        <span class="font-medium">التقييمات</span>
                    </a>
                    
                    <a href="{{ route('comments.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('comments.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('comments.*') ? 'page' : 'false' }}">
                        <i class="ti ti-message-circle text-lg"></i>
                        <span class="font-medium">التعليقات</span>
                    </a>
                    
                    <a href="{{ route('notes.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('notes.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('notes.*') ? 'page' : 'false' }}">
                        <i class="ti ti-note text-lg"></i>
                        <span class="font-medium">الملاحظات</span>
                    </a>
                </div>

                <!-- Certificates & Partners Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">الشهادات والشركاء</h3>
                    
                    <a href="{{ route('certificates.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('certificates.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('certificates.*') ? 'page' : 'false' }}">
                        <i class="ti ti-certificate text-lg"></i>
                        <span class="font-medium">الشهادات</span>
                    </a>
                    
                    <a href="{{ route('partners.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('partners.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('partners.*') ? 'page' : 'false' }}">
                        <i class="ti ti-handshake text-lg"></i>
                        <span class="font-medium">الشركاء</span>
                    </a>
                </div>

                <!-- Analytics Section - Collapsible -->
                <div class="pt-4" x-data="{ open: {{ request()->routeIs('analytics.*') || request()->routeIs('*-analytics') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.*') || request()->routeIs('*-analytics') ? 'bg-blue-50 text-blue-700' : '' }}"
                            :aria-expanded="open">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <i class="ti ti-chart-bar text-lg"></i>
                            <span class="font-medium">التحليلات</span>
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">9</span>
                        </div>
                        <i class="ti ti-chevron-down text-sm transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="mt-2 space-y-1 border-e-2 border-blue-100 pe-2">
                        
                        <a href="{{ route('analytics.index') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.index') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('analytics.index') ? 'page' : 'false' }}">
                            <i class="ti ti-chart-bar text-base"></i>
                            <span>الإحصائيات العامة</span>
                        </a>

                        <a href="{{ route('analytics.users') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.users') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('analytics.users') ? 'page' : 'false' }}">
                            <i class="ti ti-users text-base"></i>
                            <span>تحليل المستخدمين</span>
                        </a>

                        <a href="{{ route('analytics.courses') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.courses') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('analytics.courses') ? 'page' : 'false' }}">
                            <i class="ti ti-book text-base"></i>
                            <span>تحليل الدورات</span>
                        </a>

                        <a href="{{ route('analytics.reviews') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.reviews') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('analytics.reviews') ? 'page' : 'false' }}">
                            <i class="ti ti-star text-base"></i>
                            <span>تحليل التقييمات</span>
                        </a>

                        <a href="{{ route('course-materials.analytics') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('course-materials.analytics') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('course-materials.analytics') ? 'page' : 'false' }}">
                            <i class="ti ti-files text-base"></i>
                            <span>تحليل المواد</span>
                        </a>

                        <a href="{{ route('assessments.analytics') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('assessments.analytics') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('assessments.analytics') ? 'page' : 'false' }}">
                            <i class="ti ti-help-circle text-base"></i>
                            <span>تحليل الاختبارات</span>
                        </a>

                        <a href="{{ route('analytics.quizzes') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('analytics.quizzes') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('analytics.quizzes') ? 'page' : 'false' }}">
                            <i class="ti ti-brain text-base"></i>
                            <span>تحليل الاختبارات السريعة</span>
                        </a>

                        <a href="{{ route('enrollments.analytics') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('enrollments.analytics') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('enrollments.analytics') ? 'page' : 'false' }}">
                            <i class="ti ti-user-check text-base"></i>
                            <span>تحليل التسجيلات</span>
                        </a>

                        <a href="{{ route('wishlists.analytics') }}" 
                           class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('wishlists.analytics') ? 'bg-blue-50 text-blue-700' : '' }}"
                           aria-current="{{ request()->routeIs('wishlists.analytics') ? 'page' : 'false' }}">
                            <i class="ti ti-heart text-base"></i>
                            <span>تحليل المفضلة</span>
                        </a>
                    </div>
                </div>

                <!-- System Settings Section -->
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">إعدادات النظام</h3>
                    
                    <a href="{{ route('system-settings.index') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('system-settings.*') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('system-settings.*') ? 'page' : 'false' }}">
                        <i class="ti ti-settings text-lg"></i>
                        <span class="font-medium">الإعدادات</span>
                    </a>
                    
                    <a href="{{ route('system-settings.info') }}" 
                       class="flex items-center space-x-3 rtl:space-x-reverse px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('system-settings.info') ? 'bg-blue-50 text-blue-700 border-e-2 border-blue-600' : '' }}"
                       aria-current="{{ request()->routeIs('system-settings.info') ? 'page' : 'false' }}">
                        <i class="ti ti-info-circle text-lg"></i>
                        <span class="font-medium">معلومات النظام</span>
                    </a>
                </div>
            </nav>

            <!-- Profile Block - Pinned at Bottom -->
            <div class="mt-auto border-t border-gray-200 px-3 py-4 bg-white">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                        <span class="text-sm font-bold text-white">{{ auth()->user() ? substr(auth()->user()->name, 0, 1) : 'U' }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user() ? auth()->user()->name : 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user() ? auth()->user()->email : 'user@example.com' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" 
                                class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-all duration-200"
                                @click="$dispatch('toggle-profile-menu')">
                            <i class="ti ti-chevron-down text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Profile Dropdown Menu -->
                <div x-data="{ open: false }" 
                     x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="mt-3 pt-3 border-t border-gray-100 space-y-1"
                     @click.away="open = false"
                     @toggle-profile-menu.window="open = !open">
                    
                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center space-x-2 rtl:space-x-reverse px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all duration-200">
                        <i class="ti ti-user text-base"></i>
                        <span>الملف الشخصي</span>
                    </a>
                    
                    <a href="{{ route('password.request') }}" 
                       class="flex items-center space-x-2 rtl:space-x-reverse px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-all duration-200">
                        <i class="ti ti-lock text-base"></i>
                        <span>تغيير كلمة المرور</span>
                    </a>
                    
                    <hr class="my-2 border-gray-100">
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center space-x-2 rtl:space-x-reverse px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                            <i class="ti ti-logout text-base"></i>
                            <span>تسجيل الخروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 px-6 py-6">
            <!-- Top Bar -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'لوحة التحكم')</h1>
                    @hasSection('subtitle')
                        <p class="mt-1 text-sm text-gray-600">@yield('subtitle')</p>
                    @endif
                </div>
                
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    @hasSection('actions')
                        @yield('actions')
                    @endif
                    
                    <!-- Mobile Menu Button -->
                    <button type="button" 
                            class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
                            @click="$dispatch('toggle-sidebar')">
                        <i class="ti ti-menu-2 text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div x-data="{ open: false }" 
         x-show="open" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
         @click="open = false"
         @toggle-sidebar.window="open = !open"></div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
