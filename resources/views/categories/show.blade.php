@extends('layouts.dash')

@section('title', $category->name)

@section('content')
    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden mb-8">
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
                    <i class="ti ti-category"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-white/80 mt-1 text-sm">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-arrow-right mr-2"></i>
                    العودة للأقسام
                </a>
                <a href="{{ route('categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-edit mr-2"></i>
                    تعديل القسم
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-admin.card 
            title="إجمالي الدورات" 
            :value="$category->courses->count()"
            icon="ti ti-book"
            color="blue"
        />
        
        <x-admin.card 
            title="الدورات المعتمدة" 
            :value="$category->courses->where('status', 'approved')->count()"
            icon="ti ti-check-circle"
            color="green"
        />
        
        <x-admin.card 
            title="الدورات المعلقة" 
            :value="$category->courses->where('status', 'pending')->count()"
            icon="ti ti-clock"
            color="yellow"
        />
        
        <x-admin.card 
            title="إجمالي الطلاب" 
            :value="$category->courses->sum(function($course) { return $course->students->count(); })"
            icon="ti ti-users"
            color="purple"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - Courses -->
        <div class="lg:col-span-2">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">الدورات في هذا القسم</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $category->courses->count() }} دورة
                    </span>
                </div>

                @if($category->courses && $category->courses->count() > 0)
                    <div class="space-y-4">
                        @foreach($category->courses as $course)
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-teal-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start space-x-4 rtl:space-x-reverse">
                                    <!-- Course Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-blue-600 rounded-xl flex items-center justify-center">
                                            <i class="ti ti-book text-white text-lg"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Course Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900 hover:text-teal-600 transition-colors duration-200">
                                                <a href="{{ route('courses.show', $course) }}" class="hover:underline">
                                                    {{ $course->title }}
                                                </a>
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->status === 'approved' ? 'bg-green-100 text-green-800' : ($course->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $course->status === 'approved' ? 'معتمدة' : ($course->status === 'pending' ? 'قيد المراجعة' : 'مسودة') }}
                                            </span>
                                        </div>
                                        
                                        @if($course->description)
                                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                                {{ Str::limit($course->description, 120) }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 rtl:space-x-reverse text-sm text-gray-500">
                                                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                                                    <i class="ti ti-user text-gray-400"></i>
                                                    <span>{{ optional($course->instructor)->name ?? 'محاضرغير محدد' }}</span>
                                                </div>
                                                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                                                    <i class="ti ti-users text-gray-400"></i>
                                                    <span>{{ $course->students->count() }} طالب</span>
                                                </div>
                                                <div class="flex items-center space-x-1 rtl:space-x-reverse">
                                                    <i class="ti ti-calendar text-gray-400"></i>
                                                    <span>{{ $course->created_at->format('Y-n-j') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                                <x-admin.button variant="outline" size="sm" href="{{ route('courses.show', $course) }}">
                                                    <i class="ti ti-eye mr-1"></i>
                                                    عرض
                                                </x-admin.button>
                                                <x-admin.button variant="secondary" size="sm" href="{{ route('courses.edit', $course) }}">
                                                    <i class="ti ti-edit"></i>
                                                </x-admin.button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-book-off text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد دورات</h3>
                        <p class="text-gray-500 mb-6">لم يتم إنشاء أي دورات في هذا القسم بعد.</p>
                        <x-admin.button variant="primary" href="{{ route('courses.create') }}">
                            <i class="ti ti-plus mr-2"></i>
                            إضافة دورة جديدة
                        </x-admin.button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Category Information -->
        <div class="lg:col-span-1">
            <!-- Category Info Card -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات القسم</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">الاسم</span>
                        <span class="text-sm font-medium text-gray-900">{{ $category->name }}</span>
                    </div>
                    
                    @if($category->slug)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">الرابط المختصر</span>
                            <code class="text-xs bg-gray-200 px-2 py-1 rounded text-gray-700">{{ $category->slug }}</code>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-600">تاريخ الإنشاء</span>
                        <span class="text-sm font-medium text-gray-900">{{ $category->created_at->format('Y-n-j') }}</span>
                    </div>
                    
                    @if($category->updated_at != $category->created_at)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">آخر تحديث</span>
                            <span class="text-sm font-medium text-gray-900">{{ $category->updated_at->format('Y-n-j') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Category Image -->
            @if($category->image_url)
                <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">صورة القسم</h3>
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden">
                        <img src="{{ $category->image_url }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <x-admin.button variant="primary" size="sm" href="{{ route('courses.create') }}" class="w-full justify-center">
                        <i class="ti ti-plus mr-2"></i>
                        إضافة دورة جديدة
                    </x-admin.button>
                    
                    <x-admin.button variant="outline" size="sm" href="{{ route('categories.edit', $category) }}" class="w-full justify-center">
                        <i class="ti ti-edit mr-2"></i>
                        تعديل القسم
                    </x-admin.button>
                    
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <x-admin.button variant="danger" size="sm" type="submit" class="w-full justify-center"
                                onclick="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                            <i class="ti ti-trash mr-2"></i>
                            حذف القسم
                        </x-admin.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
