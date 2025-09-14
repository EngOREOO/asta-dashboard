@extends('layouts.dash')

@section('title', 'تحليلات اختبارات القبول')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">تحليلات اختبارات القبول</h1>
        <p class="text-gray-600 mt-2">نظرة شاملة على أداء جميع اختبارات القبول</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الاختبارات</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalQuizzes) }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-brain text-2xl text-teal-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الاختبارات النشطة</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($activeQuizzes) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-check text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الأسئلة</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalQuestions) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-help-circle text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المحاولات</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAttempts) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="ti ti-users text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">معدلات الأداء</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">متوسط النتيجة</span>
                    <span class="text-lg font-semibold text-gray-900">{{ number_format($averageScore, 1) }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">معدل النجاح</span>
                    <span class="text-lg font-semibold text-gray-900">{{ $totalAttempts > 0 ? number_format(($passRate / $totalAttempts) * 100, 1) : 0 }}%</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">أحدث الاختبارات</h3>
            @if($recentQuizzes->count() > 0)
                <div class="space-y-3">
                    @foreach($recentQuizzes as $quiz)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="ti ti-brain text-teal-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $quiz->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $quiz->category ? $quiz->category->name : 'بدون قسم' }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $quiz->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">لا توجد اختبارات حديثة</p>
            @endif
        </div>
    </div>

    <!-- Top Quizzes -->
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-6">أفضل الاختبارات</h3>
        @if($topQuizzes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاختبار</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المحاولات</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topQuizzes as $quiz)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="ti ti-brain text-teal-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $quiz->category ? $quiz->category->name : 'بدون قسم' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($quiz->attempts_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $quiz->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $quiz->created_at->format('Y-m-d') }}
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
@endsection
