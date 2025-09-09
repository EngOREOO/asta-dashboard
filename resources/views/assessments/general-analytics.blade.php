@extends('layouts.dash')

@section('title', 'تحليلات الاختبارات')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">تحليلات الاختبارات</h1>
        <p class="text-gray-600 mt-2">نظرة شاملة على أداء جميع الاختبارات والتقييمات</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الاختبارات</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAssessments) }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-help-circle text-2xl text-teal-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الاختبارات حسب النوع</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $assessmentsByType->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-category text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">متوسط النتائج</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $averageScores->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-chart-line text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">المحاولات الأخيرة</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $recentAttempts->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-clock text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Types Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">توزيع أنواع الاختبارات</h3>
            @if($assessmentsByType->count() > 0)
                <div class="space-y-4">
                    @foreach($assessmentsByType as $type)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-4 h-4 bg-teal-500 rounded-full"></div>
                                <span class="text-sm text-gray-700">{{ ucfirst($type->type) }}</span>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">{{ $type->count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">لا توجد أنواع اختبارات متاحة</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">أفضل النتائج</h3>
            @if($averageScores->count() > 0)
                <div class="space-y-3">
                    @foreach($averageScores->take(5) as $score)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-trophy text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $score->assessment->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $score->assessment->course->title ?? 'بدون دورة' }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-green-600">{{ number_format($score->avg_score, 1) }}%</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">لا توجد نتائج متاحة</p>
            @endif
        </div>
    </div>

    <!-- Popular Assessments -->
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">الاختبارات الأكثر شعبية</h3>
        @if($popularAssessments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاختبار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدورة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المحاضر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المحاولات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($popularAssessments as $assessment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-help-circle text-teal-600 text-sm"></i>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">{{ $assessment->title }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assessment->course->title ?? 'بدون دورة' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $assessment->course->instructor->name ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($assessment->attempts_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($assessment->type) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">لا توجد اختبارات متاحة</p>
        @endif
    </div>

    <!-- Recent Attempts -->
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">آخر المحاولات</h3>
        @if($recentAttempts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطالب</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاختبار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النتيجة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAttempts as $attempt)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="ti ti-user text-purple-600 text-sm"></i>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">{{ $attempt->user_name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attempt->assessment_title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attempt->score ?? 'غير محدد' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $attempt->is_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $attempt->is_passed ? 'نجح' : 'فشل' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attempt->created_at->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">لا توجد محاولات حديثة</p>
        @endif
    </div>
@endsection
