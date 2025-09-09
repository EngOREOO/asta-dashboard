@extends('layouts.dash')

@section('title', $courseMaterial->title)

@section('content')
    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden mb-8">
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/20 flex items-center justify-center text-xl font-bold">
                    <i class="ti ti-file-description"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold">{{ $courseMaterial->title }}</h1>
                    @if($courseMaterial->description)
                        <p class="text-white/80 mt-1 text-sm">{{ $courseMaterial->description }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('course-materials.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-arrow-right mr-2"></i>
                    العودة للمواد
                </a>
                <a href="{{ route('course-materials.edit', $courseMaterial) }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl">
                    <i class="ti ti-edit mr-2"></i>
                    تعديل المادة
                </a>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content - Material Details -->
        <div class="lg:col-span-2">
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <div class="flex items-start space-x-4 rtl:space-x-reverse mb-6">
                    <!-- Material Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-blue-600 rounded-2xl flex items-center justify-center">
                            <i class="ti ti-{{ $courseMaterial->type === 'video' ? 'video' : ($courseMaterial->type === 'document' ? 'file-document' : ($courseMaterial->type === 'quiz' ? 'help-circle' : 'clipboard')) }} text-white text-2xl"></i>
                        </div>
                    </div>
                    
                    <!-- Material Info -->
                    <div class="flex-1">
                        <h2 class="font-bold text-gray-900 mb-2" style="font-size: 1.3rem;">{{ $courseMaterial->title }}</h2>
                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium {{ $courseMaterial->type === 'video' ? 'bg-blue-100 text-blue-800' : ($courseMaterial->type === 'document' ? 'bg-purple-100 text-purple-800' : ($courseMaterial->type === 'quiz' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}" style="font-size: 1.3rem;">
                                <i class="ti ti-{{ $courseMaterial->type === 'video' ? 'video' : ($courseMaterial->type === 'document' ? 'file-document' : ($courseMaterial->type === 'quiz' ? 'help-circle' : 'clipboard')) }} mr-1"></i>
                                {{ $courseMaterial->type === 'video' ? 'فيديو' : ($courseMaterial->type === 'document' ? 'مستند' : ($courseMaterial->type === 'quiz' ? 'اختبار' : 'مهمة')) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium {{ $courseMaterial->is_free ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}" style="font-size: 1.3rem;">
                                <i class="ti ti-{{ $courseMaterial->is_free ? 'gift' : 'lock' }} mr-1"></i>
                                {{ $courseMaterial->is_free ? 'مجاني' : 'مدفوع' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($courseMaterial->description)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3" style="font-size: 1.3rem;">وصف المادة</h3>
                        <p class="text-gray-600 leading-relaxed" style="font-size: 1.3rem;">{{ $courseMaterial->description }}</p>
                    </div>
                @endif

                <!-- Course Information -->
                @if($courseMaterial->course)
                    <div class="border-t pt-6 mb-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">معلومات الدورة</h3>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $courseMaterial->course->title }}</h4>
                                    @if($courseMaterial->course->instructor)
                                        <p class="text-gray-600" style="font-size: 1.3rem;">المحاضر: {{ $courseMaterial->course->instructor->name }}</p>
                                    @endif
                                </div>
                                <x-admin.button variant="outline" size="sm" href="{{ route('courses.show', $courseMaterial->course) }}">
                                    <i class="ti ti-eye mr-1"></i>
                                    عرض الدورة
                                </x-admin.button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- File and Content Access -->
                @if($courseMaterial->file_path || $courseMaterial->content_url)
                    <div class="border-t pt-6">
                        <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">الوصول للمحتوى</h3>
                        <div class="space-y-3">
                            @if($courseMaterial->type === 'video' && $courseMaterial->file_path)
                                <div class="p-4 border border-gray-200 rounded-2xl bg-black/5">
                                    <video controls class="w-full rounded-xl shadow" style="max-height: 520px;">
                                        <source src="{{ asset('storage/' . $courseMaterial->file_path) }}" type="video/mp4">
                                        متصفحك لا يدعم تشغيل الفيديو. <a href="{{ asset('storage/' . $courseMaterial->file_path) }}" class="text-blue-600 underline">تحميل الملف</a>
                                    </video>
                                </div>
                            @endif
                            @if($courseMaterial->file_path)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-download text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" style="font-size: 1.3rem;">ملف المادة</p>
                                            @if($courseMaterial->file_size)
                                                <p class="text-gray-500" style="font-size: 1.3rem;">الحجم: {{ number_format($courseMaterial->file_size / 1024 / 1024, 2) }} ميجا</p>
                                            @endif
                                        </div>
                                    </div>
                                    <x-admin.button variant="primary" size="sm" href="{{ asset('storage/' . $courseMaterial->file_path) }}" target="_blank">
                                        <i class="ti ti-download mr-1"></i>
                                        تحميل الملف
                                    </x-admin.button>
                                </div>
                            @endif

                            @if($courseMaterial->content_url)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl">
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="ti ti-external-link text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" style="font-size: 1.3rem;">رابط خارجي</p>
                                            <p class="text-gray-500" style="font-size: 1.3rem;">{{ parse_url($courseMaterial->content_url, PHP_URL_HOST) }}</p>
                                        </div>
                                    </div>
                                    <x-admin.button variant="secondary" size="sm" href="{{ $courseMaterial->content_url }}" target="_blank">
                                        <i class="ti ti-external-link mr-1"></i>
                                        فتح الرابط
                                    </x-admin.button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar - Material Info -->
        <div class="lg:col-span-1">
            <!-- Material Details Card -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">تفاصيل المادة</h3>
                <div class="space-y-4">
                    @if($courseMaterial->duration)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600" style="font-size: 1.3rem;">المدة</span>
                            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ gmdate('H:i:s', $courseMaterial->duration) }}</span>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600" style="font-size: 1.3rem;">الترتيب</span>
                        <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $courseMaterial->order ?? 'غير محدد' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600" style="font-size: 1.3rem;">تاريخ الإنشاء</span>
                        <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $courseMaterial->created_at->format('Y-n-j') }}</span>
                    </div>
                    
                    @if($courseMaterial->updated_at != $courseMaterial->created_at)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600" style="font-size: 1.3rem;">آخر تحديث</span>
                            <span class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $courseMaterial->updated_at->format('Y-n-j') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Completion Statistics -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">إحصائيات الإكمال</h3>
                
                @if($courseMaterial->completions && $courseMaterial->completions->count() > 0)
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-teal-600 mb-2">{{ $courseMaterial->completions->count() }}</div>
                        <p class="text-gray-600" style="font-size: 1.3rem;">إجمالي المكملين</p>
                    </div>
                    
                    <h4 class="font-semibold text-gray-900 mb-3" style="font-size: 1.3rem;">آخر المكملين</h4>
                    <div class="space-y-3">
                        @foreach($courseMaterial->completions->take(5) as $completion)
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ substr($completion->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate" style="font-size: 1.3rem;">{{ $completion->user->name }}</p>
                                    <p class="text-gray-500" style="font-size: 1.3rem;">{{ $completion->completed_at->format('Y-n-j') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ti ti-users text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-2" style="font-size: 1.3rem;">لا توجد إكمالات</h4>
                        <p class="text-gray-500" style="font-size: 1.3rem;">لم يكمل أحد هذه المادة بعد.</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
                <h3 class="font-semibold text-gray-900 mb-4" style="font-size: 1.3rem;">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <x-admin.button variant="primary" size="sm" href="{{ route('course-materials.edit', $courseMaterial) }}" class="w-full justify-center">
                        <i class="ti ti-edit mr-2"></i>
                        تعديل المادة
                    </x-admin.button>
                    
                    <x-admin.button variant="outline" size="sm" href="{{ route('course-materials.index') }}" class="w-full justify-center">
                        <i class="ti ti-list mr-2"></i>
                        عرض جميع المواد
                    </x-admin.button>
                    
                    @if($courseMaterial->course)
                        <x-admin.button variant="secondary" size="sm" href="{{ route('courses.show', $courseMaterial->course) }}" class="w-full justify-center">
                            <i class="ti ti-book mr-2"></i>
                            عرض الدورة
                        </x-admin.button>
                    @endif
                    
                    <form action="{{ route('course-materials.destroy', $courseMaterial) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <x-admin.button variant="danger" size="sm" type="submit" class="w-full justify-center"
                                onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')">
                            <i class="ti ti-trash mr-2"></i>
                            حذف المادة
                        </x-admin.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
