@php
  $title = 'تفاصيل الاختبار';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">{{ $quiz->title }}</h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-clipboard-check mr-2 text-cyan-500"></i>
          تفاصيل وإحصائيات الاختبار
        </p>
      </div>
      <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
        <a href="{{ route('quizzes.edit', $quiz) }}" class="group inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.2rem;">
          <i class="ti ti-edit mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          تعديل
        </a>
        <a href="{{ route('quizzes.index') }}" class="group inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl hover:bg-white hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-500/20 transition-all duration-300" style="font-size: 1.2rem;">
          <i class="ti ti-arrow-right mr-2 group-hover:-translate-x-1 transition-transform duration-300"></i>
          العودة للقائمة
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Quiz Details -->
      <div class="lg:col-span-2">
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
            <div class="relative z-10 flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
                  <i class="ti ti-clipboard-check text-white text-xl"></i>
                </div>
                <div>
                  <h2 class="font-bold text-white" style="font-size: 1.9rem;">تفاصيل الاختبار</h2>
                  <p class="text-white/80" style="font-size: 1.2rem;">معلومات شاملة عن الاختبار</p>
                </div>
              </div>
              @if($quiz->is_active)
                <span class="inline-flex items-center px-4 py-2 bg-green-500/20 text-green-100 rounded-xl font-semibold" style="font-size: 1.2rem;">
                  <i class="ti ti-check-circle mr-2"></i>
                  نشط
                </span>
              @else
                <span class="inline-flex items-center px-4 py-2 bg-gray-500/20 text-gray-100 rounded-xl font-semibold" style="font-size: 1.2rem;">
                  <i class="ti ti-x-circle mr-2"></i>
                  غير نشط
                </span>
              @endif
            </div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
          </div>

          <div class="p-8 space-y-6" style="font-size: 1.3rem;">
            <!-- Course Information -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
              <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                <i class="ti ti-book mr-2 text-blue-500"></i>
                معلومات الدورة
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <span class="text-gray-600 font-medium">الدورة:</span>
                  <p class="text-gray-900 font-semibold">{{ $quiz->course->title ?? 'لا توجد دورة' }}</p>
                </div>
                @if($quiz->course && $quiz->course->instructor)
                <div>
                  <span class="text-gray-600 font-medium">المدرب:</span>
                  <p class="text-gray-900 font-semibold">{{ $quiz->course->instructor->name }}</p>
                </div>
                @endif
              </div>
            </div>

            @if($quiz->description)
            <!-- Description -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
              <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                <i class="ti ti-file-text mr-2 text-green-500"></i>
                الوصف
              </h3>
              <p class="text-gray-700 leading-relaxed">{{ $quiz->description }}</p>
            </div>
            @endif

            <!-- Quiz Settings -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200">
              <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                <i class="ti ti-settings mr-2 text-purple-500"></i>
                إعدادات الاختبار
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">المدة:</span>
                    <div class="flex items-center text-gray-900 font-semibold">
                      @if($quiz->duration_minutes)
                        <i class="ti ti-clock mr-1"></i>
                        {{ $quiz->duration_minutes }} دقيقة
                      @else
                        <i class="ti ti-infinity mr-1"></i>
                        بدون حد
                      @endif
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">الحد الأقصى للمحاولات:</span>
                    <div class="flex items-center text-gray-900 font-semibold">
                      @if($quiz->max_attempts)
                        <i class="ti ti-repeat mr-1"></i>
                        {{ $quiz->max_attempts }}
                      @else
                        <i class="ti ti-infinity mr-1"></i>
                        غير محدود
                      @endif
                    </div>
                  </div>
                  
                  @if($quiz->passing_score)
                  <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">درجة النجاح:</span>
                    <div class="flex items-center text-gray-900 font-semibold">
                      <i class="ti ti-target mr-1"></i>
                      {{ $quiz->passing_score }}%
                    </div>
                  </div>
                  @endif
                </div>
                
                <div class="space-y-3">
                  @if($quiz->randomize_questions)
                  <div class="flex items-center">
                    <i class="ti ti-shuffle text-blue-500 mr-2"></i>
                    <span class="text-blue-700 font-medium">أسئلة عشوائية</span>
                  </div>
                  @endif
                  
                  @if($quiz->show_results_immediately)
                  <div class="flex items-center">
                    <i class="ti ti-eye text-green-500 mr-2"></i>
                    <span class="text-green-700 font-medium">عرض النتائج فوراً</span>
                  </div>
                  @endif
                  
                  @if($quiz->allow_review)
                  <div class="flex items-center">
                    <i class="ti ti-refresh text-purple-500 mr-2"></i>
                    <span class="text-purple-700 font-medium">السماح بالمراجعة</span>
                  </div>
                  @endif
                </div>
              </div>
            </div>

            @if($quiz->available_from || $quiz->available_until)
            <!-- Availability -->
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-6 rounded-2xl border border-orange-200">
              <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                <i class="ti ti-calendar mr-2 text-orange-500"></i>
                أوقات التوفر
              </h3>
              <div class="space-y-3">
                @if($quiz->available_from)
                <div class="flex items-center">
                  <i class="ti ti-calendar-plus text-orange-500 mr-2"></i>
                  <span class="text-gray-600 font-medium mr-2">من:</span>
                  <span class="text-gray-900 font-semibold">{{ $quiz->available_from->format('Y/m/d H:i') }}</span>
                </div>
                @endif
                @if($quiz->available_until)
                <div class="flex items-center">
                  <i class="ti ti-calendar-minus text-orange-500 mr-2"></i>
                  <span class="text-gray-600 font-medium mr-2">حتى:</span>
                  <span class="text-gray-900 font-semibold">{{ $quiz->available_until->format('Y/m/d H:i') }}</span>
                </div>
                @endif
              </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 pt-6">
              <a href="{{ route('quizzes.questions', $quiz) }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
                <i class="ti ti-list mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                إدارة الأسئلة
              </a>
              <a href="{{ route('quizzes.attempts', $quiz) }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-cyan-600 hover:to-teal-600 focus:outline-none focus:ring-4 focus:ring-cyan-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
                <i class="ti ti-users mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                عرض المحاولات
              </a>
              <a href="{{ route('quizzes.analytics', $quiz) }}" class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-green-500/20 transition-all duration-300 transform hover:scale-105" style="font-size: 1.3rem;">
                <i class="ti ti-chart-bar mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                التحليلات
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Quiz Statistics -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-chart-bar text-white text-lg"></i>
                </div>
                إحصائيات الاختبار
              </h3>
            </div>
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          </div>

          <div class="p-6">
            <div class="grid grid-cols-2 gap-6 mb-6">
              <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                  <i class="ti ti-help-circle text-2xl text-blue-600"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $quiz->questions->count() }}</h4>
                <p class="text-gray-600 font-medium" style="font-size: 1.2rem;">الأسئلة</p>
              </div>
              <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-100 to-green-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                  <i class="ti ti-users text-2xl text-green-600"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $quiz->attempts->count() }}</h4>
                <p class="text-gray-600 font-medium" style="font-size: 1.2rem;">المحاولات</p>
              </div>
            </div>
            
            @if($quiz->questions->count() > 0)
            <div class="space-y-4">
              <div class="bg-gray-50 p-4 rounded-xl">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-600 font-medium" style="font-size: 1.2rem;">إجمالي النقاط:</span>
                  <span class="font-bold text-gray-900" style="font-size: 1.3rem;">{{ $quiz->total_points }}</span>
                </div>
              </div>
              
              <div class="bg-gray-50 p-4 rounded-xl">
                <h4 class="text-gray-700 font-semibold mb-3 flex items-center" style="font-size: 1.2rem;">
                  <i class="ti ti-category mr-2 text-purple-500"></i>
                  أنواع الأسئلة
                </h4>
                <div class="space-y-2">
                  @php
                    $types = $quiz->questions->groupBy('type');
                  @endphp
                  @foreach($types as $type => $questions)
                    <div class="flex justify-between items-center">
                      <span class="text-gray-600" style="font-size: 1.1rem;">{{ ucfirst(str_replace('_', ' ', $type)) }}:</span>
                      <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-lg font-semibold" style="font-size: 1.1rem;">
                        {{ $questions->count() }}
                      </span>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
        
        @if($quiz->attempts->count() > 0)
        <!-- Recent Attempts -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
          <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/20 to-teal-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-clock text-white text-lg"></i>
                </div>
                المحاولات الأخيرة
              </h3>
            </div>
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          </div>

          <div class="p-6">
            <div class="space-y-4">
              @foreach($quiz->attempts->take(5) as $attempt)
              <div class="bg-gray-50 p-4 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3">
                    <span class="text-white font-bold text-sm">
                      {{ Str::of($attempt->user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <div class="font-semibold text-gray-900" style="font-size: 1.2rem;">{{ $attempt->user->name }}</div>
                    <div class="flex items-center mt-1">
                      @if($attempt->completed_at)
                        <span class="text-gray-600 mr-2" style="font-size: 1.1rem;">النتيجة: {{ number_format($attempt->score ?? 0, 1) }}%</span>
                        @if($attempt->is_passed)
                          <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded-lg font-semibold" style="font-size: 1rem;">
                            <i class="ti ti-check-circle mr-1"></i>
                            نجح
                          </span>
                        @else
                          <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded-lg font-semibold" style="font-size: 1rem;">
                            <i class="ti ti-x-circle mr-1"></i>
                            فشل
                          </span>
                        @endif
                      @else
                        <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg font-semibold" style="font-size: 1rem;">
                          <i class="ti ti-clock mr-1"></i>
                          قيد التنفيذ
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
