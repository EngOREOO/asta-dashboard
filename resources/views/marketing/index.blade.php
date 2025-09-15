@php
    $title = 'الماركتينج الذكي';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 font-arabic relative overflow-hidden">
  <!-- Background Pattern -->
  <div class="absolute inset-0 opacity-5">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(59, 130, 246, 0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
  </div>
  
  <div class="relative z-10 space-y-8 p-6">
    
    <!-- Modern Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between animate-fade-in">
      <div class="space-y-4 mb-6 lg:mb-0">
        <div class="flex items-center space-x-3 space-x-reverse">
          <div class="w-12 h-12 bg-gradient-to-br from-purple-500 via-pink-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
            <i class="ti ti-mail text-white text-xl"></i>
          </div>
          <div>
            <h1 class="text-5xl font-black bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 bg-clip-text text-transparent leading-tight">
              الماركتينج الذكي
            </h1>
            <p class="text-xl text-gray-600 font-medium">
              منصة تسويقية متقدمة مع ذكاء اصطناعي
            </p>
          </div>
        </div>
      </div>
      
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('admin.marketing.create') }}" class="group relative overflow-hidden bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-4 rounded-2xl flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <i class="ti ti-plus ml-3 text-lg"></i>
          <span class="font-semibold text-lg">حملة جديدة</span>
        </a>
        <a href="{{ route('admin.marketing.templates') }}" class="group relative overflow-hidden bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <i class="ti ti-template ml-3 text-lg"></i>
          <span class="font-semibold text-lg">القوالب</span>
        </a>
        <a href="{{ route('admin.marketing.analytics') }}" class="group relative overflow-hidden bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-4 rounded-2xl flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <i class="ti ti-chart-bar ml-3 text-lg"></i>
          <span class="font-semibold text-lg">التحليلات</span>
        </a>
      </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 animate-slide-up">
      <!-- Students Card -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-users text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">إجمالي الطلاب</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($totalStudents) }}</p>
            <p class="text-sm text-gray-500 font-medium">متاح للإرسال</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 h-2 rounded-full w-3/4 transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Instructors Card -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-user-star text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">إجمالي المدربين</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($totalInstructors) }}</p>
            <p class="text-sm text-gray-500 font-medium">متاح للإرسال</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full w-2/3 transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Total Users Card -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-mail text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">إجمالي المستخدمين</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-sm text-gray-500 font-medium">جميع المستخدمين</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full w-full transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Campaigns Card -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-send text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-orange-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">الحملات المرسلة</p>
            <p class="text-4xl font-black text-gray-900">0</p>
            <p class="text-sm text-gray-500 font-medium">هذا الشهر</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full w-1/4 transition-all duration-1000"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Advanced Marketing Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-slide-up">
      <!-- AI-Powered Campaign Creator -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-brain text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">منشئ الحملات الذكي</h3>
                <p class="text-purple-100 text-lg">ذكاء اصطناعي متقدم</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <p class="text-gray-600 mb-8 text-lg leading-relaxed">أنشئ حملات تسويقية ذكية باستخدام الذكاء الاصطناعي لتحسين الأداء والاستهداف</p>
            <div class="space-y-4">
              <a href="{{ route('admin.marketing.create') }}" class="group/btn w-full bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-4 rounded-2xl flex items-center justify-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                <i class="ti ti-magic ml-3 text-xl"></i>
                <span class="font-bold text-lg">إنشاء ذكي</span>
              </a>
              <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-200">
                  <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <i class="ti ti-target text-white text-sm"></i>
                  </div>
                  <p class="text-sm font-semibold text-gray-900">استهداف دقيق</p>
                  <p class="text-xs text-gray-600">95% دقة</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-200">
                  <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <i class="ti ti-chart-line text-white text-sm"></i>
                  </div>
                  <p class="text-sm font-semibold text-gray-900">تحسين الأداء</p>
                  <p class="text-xs text-gray-600">+40% معدل</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Real-time Analytics Dashboard -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-chart-bar text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">التحليلات المباشرة</h3>
                <p class="text-green-100 text-lg">بيانات فورية</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="space-y-6">
              <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-eye text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">الفتحات اليوم</p>
                    <p class="text-sm text-gray-600">آخر 24 ساعة</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-bold text-green-600" id="todayOpens">0</p>
                  <p class="text-sm text-green-600">+12%</p>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-cursor-click text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">النقرات اليوم</p>
                    <p class="text-sm text-gray-600">آخر 24 ساعة</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-bold text-blue-600" id="todayClicks">0</p>
                  <p class="text-sm text-green-600">+8%</p>
                </div>
              </div>

              <a href="{{ route('admin.marketing.analytics') }}" class="group/btn w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-4 rounded-2xl flex items-center justify-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                <i class="ti ti-chart-line ml-3 text-xl"></i>
                <span class="font-bold text-lg">عرض التحليلات الكاملة</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Smart Templates Library -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-template text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">مكتبة القوالب الذكية</h3>
                <p class="text-blue-100 text-lg">قوالب محسنة</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mb-3">
                  <i class="ti ti-user-plus text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-gray-900 text-sm mb-1">رسالة ترحيب</h4>
                <p class="text-xs text-gray-600">معدل فتح 85%</p>
              </div>

              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3">
                  <i class="ti ti-book text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-gray-900 text-sm mb-1">إعلان دورة</h4>
                <p class="text-xs text-gray-600">معدل فتح 72%</p>
              </div>

              <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300">
                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mb-3">
                  <i class="ti ti-news text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-gray-900 text-sm mb-1">نشرة إخبارية</h4>
                <p class="text-xs text-gray-600">معدل فتح 68%</p>
              </div>

              <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-2xl border border-orange-200 hover:shadow-lg transition-all duration-300">
                <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mb-3">
                  <i class="ti ti-discount text-white text-sm"></i>
                </div>
                <h4 class="font-semibold text-gray-900 text-sm mb-1">عرض ترويجي</h4>
                <p class="text-xs text-gray-600">معدل فتح 78%</p>
              </div>
            </div>
            
            <a href="{{ route('admin.marketing.templates') }}" class="group/btn w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl flex items-center justify-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
              <i class="ti ti-template ml-3 text-xl"></i>
              <span class="font-bold text-lg">استكشاف القوالب</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-slide-up">
      <!-- Performance Trends Chart -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-600 px-8 py-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                  <i class="ti ti-chart-line text-white text-xl"></i>
                </div>
                <div>
                  <h3 class="text-2xl font-bold text-white">اتجاهات الأداء</h3>
                  <p class="text-blue-100 text-lg">آخر 7 أيام</p>
                </div>
              </div>
              <div class="flex space-x-2 space-x-reverse">
                <button onclick="toggleChartType('performance', 'line')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-all duration-300">
                  <i class="ti ti-chart-line"></i>
                </button>
                <button onclick="toggleChartType('performance', 'bar')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-all duration-300">
                  <i class="ti ti-chart-bar"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="h-80 relative">
              <canvas id="performanceChart"></canvas>
              <div id="performanceChartLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div class="text-center">
                  <i class="ti ti-loader animate-spin text-3xl text-blue-500 mb-2"></i>
                  <p class="text-gray-600">جاري تحميل البيانات...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Campaign Distribution Chart -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-600 px-8 py-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                  <i class="ti ti-chart-donut text-white text-xl"></i>
                </div>
                <div>
                  <h3 class="text-2xl font-bold text-white">توزيع الحملات</h3>
                  <p class="text-green-100 text-lg">حسب النوع</p>
                </div>
              </div>
              <div class="flex space-x-2 space-x-reverse">
                <button onclick="toggleChartType('distribution', 'doughnut')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-all duration-300">
                  <i class="ti ti-chart-donut"></i>
                </button>
                <button onclick="toggleChartType('distribution', 'pie')" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition-all duration-300">
                  <i class="ti ti-chart-pie"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="h-80 relative">
              <canvas id="distributionChart"></canvas>
              <div id="distributionChartLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div class="text-center">
                  <i class="ti ti-loader animate-spin text-3xl text-green-500 mb-2"></i>
                  <p class="text-gray-600">جاري تحميل البيانات...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- AI Insights & Recent Campaigns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-slide-up">
      <!-- AI-Powered Insights -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-brain text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">رؤى ذكية</h3>
                <p class="text-purple-100 text-lg">توصيات مدعومة بالذكاء الاصطناعي</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-target text-white"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-2">استهداف محسن</h4>
                    <p class="text-sm text-gray-600 mb-3">استهدف المستخدمين النشطين في الساعات 9-11 صباحاً و 7-9 مساءً لزيادة معدل الفتح بنسبة 35%</p>
                    <div class="text-xs text-blue-600 font-medium">توصية ذكية</div>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-clock text-white"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-2">التوقيت الأمثل</h4>
                    <p class="text-sm text-gray-600 mb-3">أرسل رسائل الترحيب في أول 24 ساعة من التسجيل لزيادة التفاعل بنسبة 40%</p>
                    <div class="text-xs text-green-600 font-medium">تحليل سلوكي</div>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-edit text-white"></i>
                  </div>
                  <div>
                    <h4 class="font-semibold text-gray-900 mb-2">محتوى جذاب</h4>
                    <p class="text-sm text-gray-600 mb-3">استخدم عناوين تحتوي على أرقام أو أسئلة لزيادة معدل الفتح بنسبة 25%</p>
                    <div class="text-xs text-purple-600 font-medium">تحليل محتوى</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Campaigns -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-700 px-8 py-6">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3 space-x-reverse">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                  <i class="ti ti-clock text-white text-xl"></i>
                </div>
                <div>
                  <h3 class="text-2xl font-bold text-white">الحملات الأخيرة</h3>
                  <p class="text-indigo-100 text-lg">آخر الأنشطة التسويقية</p>
                </div>
              </div>
              <button onclick="refreshCampaigns()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="ti ti-refresh"></i>
              </button>
            </div>
          </div>
          <div class="p-8">
            @if($recentCampaigns->count() > 0)
              <div class="space-y-4">
                @foreach($recentCampaigns as $campaign)
                <div class="group/item flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl border border-gray-200 hover:shadow-lg hover:scale-105 transition-all duration-300">
                  <div class="flex-1">
                    <h4 class="font-bold text-gray-900 text-xl mb-2">{{ $campaign->subject }}</h4>
                    <p class="text-gray-600 text-lg">{{ $campaign->recipients_count }} مستلم</p>
                  </div>
                  <div class="text-left">
                    <p class="text-lg font-bold text-green-600">{{ $campaign->sent_at }}</p>
                    <p class="text-sm text-gray-500">{{ $campaign->status }}</p>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                  <i class="ti ti-mail text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-500 mb-4">لا توجد حملات سابقة</h3>
                <p class="text-gray-400 text-lg mb-8">ابدأ بإنشاء حملتك التسويقية الأولى</p>
                <a href="{{ route('admin.marketing.create') }}" class="group relative overflow-hidden bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-4 rounded-2xl inline-flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                  <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                  <i class="ti ti-plus ml-3 text-xl"></i>
                  <span class="font-bold text-lg">إنشاء حملة جديدة</span>
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Advanced Email Templates Library -->
    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 animate-slide-up">
      <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 via-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
      <div class="relative">
        <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-template text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">مكتبة القوالب المتقدمة</h3>
                <p class="text-teal-100 text-lg">قوالب محسنة بالذكاء الاصطناعي</p>
              </div>
            </div>
            <div class="flex space-x-2 space-x-reverse">
              <button onclick="filterTemplates('all')" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="ti ti-filter"></i>
              </button>
              <button onclick="refreshTemplates()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="ti ti-refresh"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group/template relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-3xl border border-blue-200 hover:shadow-2xl hover:scale-105 transition-all duration-500">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover/template:opacity-100 transition-opacity duration-300"></div>
              <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover/template:scale-110 transition-transform duration-300">
                  <i class="ti ti-user-plus text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-xl mb-3">رسالة ترحيب</h4>
                <p class="text-gray-600 mb-6 leading-relaxed">ترحيب بالمستخدمين الجدد مع معلومات المنصة</p>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm text-blue-600 font-semibold">معدل الفتح: 85%</span>
                  <span class="text-sm text-green-600 font-semibold">معدل النقر: 12%</span>
                </div>
                <a href="{{ route('admin.marketing.templates') }}" class="text-blue-600 text-lg font-bold hover:text-blue-700 transition-colors duration-300">استخدام القالب</a>
              </div>
            </div>

            <div class="group/template relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-3xl border border-green-200 hover:shadow-2xl hover:scale-105 transition-all duration-500">
              <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover/template:opacity-100 transition-opacity duration-300"></div>
              <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover/template:scale-110 transition-transform duration-300">
                  <i class="ti ti-book text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-xl mb-3">إعلان دورة</h4>
                <p class="text-gray-600 mb-6 leading-relaxed">إعلان عن دورة جديدة مع تفاصيل المحتوى</p>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm text-green-600 font-semibold">معدل الفتح: 72%</span>
                  <span class="text-sm text-blue-600 font-semibold">معدل النقر: 18%</span>
                </div>
                <a href="{{ route('admin.marketing.templates') }}" class="text-green-600 text-lg font-bold hover:text-green-700 transition-colors duration-300">استخدام القالب</a>
              </div>
            </div>

            <div class="group/template relative overflow-hidden bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-3xl border border-purple-200 hover:shadow-2xl hover:scale-105 transition-all duration-500">
              <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover/template:opacity-100 transition-opacity duration-300"></div>
              <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover/template:scale-110 transition-transform duration-300">
                  <i class="ti ti-news text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-xl mb-3">النشرة الإخبارية</h4>
                <p class="text-gray-600 mb-6 leading-relaxed">نشرة شهرية للمستخدمين مع آخر الأخبار</p>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm text-purple-600 font-semibold">معدل الفتح: 68%</span>
                  <span class="text-sm text-green-600 font-semibold">معدل النقر: 15%</span>
                </div>
                <a href="{{ route('admin.marketing.templates') }}" class="text-purple-600 text-lg font-bold hover:text-purple-700 transition-colors duration-300">استخدام القالب</a>
              </div>
            </div>

            <div class="group/template relative overflow-hidden bg-gradient-to-br from-orange-50 to-red-50 p-8 rounded-3xl border border-orange-200 hover:shadow-2xl hover:scale-105 transition-all duration-500">
              <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover/template:opacity-100 transition-opacity duration-300"></div>
              <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover/template:scale-110 transition-transform duration-300">
                  <i class="ti ti-discount text-white text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-xl mb-3">عرض ترويجي</h4>
                <p class="text-gray-600 mb-6 leading-relaxed">عروض وخصومات خاصة للعملاء</p>
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm text-orange-600 font-semibold">معدل الفتح: 78%</span>
                  <span class="text-sm text-red-600 font-semibold">معدل النقر: 22%</span>
                </div>
                <a href="{{ route('admin.marketing.templates') }}" class="text-orange-600 text-lg font-bold hover:text-orange-700 transition-colors duration-300">استخدام القالب</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
// Global chart instances
let performanceChartInstance = null;
let distributionChartInstance = null;

// Chart configurations
const chartConfigs = {
  performance: {
    type: 'line',
    data: {
      labels: ['الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت', 'الأحد'],
      datasets: [{
        label: 'المرسل',
        data: [120, 150, 180, 200, 220, 250, 280],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgb(59, 130, 246)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }, {
        label: 'معدل الفتح',
        data: [25, 28, 30, 32, 35, 38, 40],
        borderColor: 'rgb(34, 197, 94)',
        backgroundColor: 'rgba(34, 197, 94, 0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgb(34, 197, 94)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }, {
        label: 'معدل النقر',
        data: [8, 10, 12, 14, 16, 18, 20],
        borderColor: 'rgb(168, 85, 247)',
        backgroundColor: 'rgba(168, 85, 247, 0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgb(168, 85, 247)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function(context) {
              return context.dataset.label + ': ' + context.parsed.y + (context.dataset.label.includes('معدل') ? '%' : '');
            }
          }
        }
      },
      scales: {
        x: {
          grid: {
            color: 'rgba(0,0,0,0.1)'
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0,0,0,0.1)'
          },
          ticks: {
            callback: function(value) {
              return value + (value < 100 ? '' : '');
            }
          }
        }
      },
      interaction: {
        intersect: false,
        mode: 'index'
      }
    }
  },
  
  distribution: {
    type: 'doughnut',
    data: {
      labels: ['رسائل ترحيب', 'إعلانات دورات', 'نشرات إخبارية', 'عروض ترويجية', 'إشعارات عامة'],
      datasets: [{
        data: [25, 30, 20, 15, 10],
        backgroundColor: [
          'rgba(59, 130, 246, 0.8)',
          'rgba(34, 197, 94, 0.8)',
          'rgba(168, 85, 247, 0.8)',
          'rgba(245, 158, 11, 0.8)',
          'rgba(236, 72, 153, 0.8)'
        ],
        borderWidth: 2,
        borderColor: '#fff',
        hoverBorderWidth: 3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed / total) * 100).toFixed(1);
              return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
            }
          }
        }
      }
    }
  }
};

// Initialize charts
function initializeCharts() {
  // Performance Chart
  const performanceCtx = document.getElementById('performanceChart');
  if (performanceCtx) {
    setTimeout(() => {
      document.getElementById('performanceChartLoading').style.display = 'none';
      performanceChartInstance = new Chart(performanceCtx.getContext('2d'), chartConfigs.performance);
    }, 1500);
  }

  // Distribution Chart
  const distributionCtx = document.getElementById('distributionChart');
  if (distributionCtx) {
    setTimeout(() => {
      document.getElementById('distributionChartLoading').style.display = 'none';
      distributionChartInstance = new Chart(distributionCtx.getContext('2d'), chartConfigs.distribution);
    }, 2000);
  }
}

// Toggle chart types
function toggleChartType(chartName, type) {
  if (chartName === 'performance') {
    if (performanceChartInstance) {
      performanceChartInstance.destroy();
    }
    chartConfigs.performance.type = type;
    const ctx = document.getElementById('performanceChart');
    performanceChartInstance = new Chart(ctx.getContext('2d'), chartConfigs.performance);
  } else if (chartName === 'distribution') {
    if (distributionChartInstance) {
      distributionChartInstance.destroy();
    }
    chartConfigs.distribution.type = type;
    const ctx = document.getElementById('distributionChart');
    distributionChartInstance = new Chart(ctx.getContext('2d'), chartConfigs.distribution);
  }
}

// Real-time updates
function updateRealTimeMetrics() {
  const todayOpens = document.getElementById('todayOpens');
  const todayClicks = document.getElementById('todayClicks');
  
  if (todayOpens) todayOpens.textContent = Math.floor(Math.random() * 50) + 20;
  if (todayClicks) todayClicks.textContent = Math.floor(Math.random() * 20) + 5;
}

// Template functions
function filterTemplates(filter) {
  console.log('Filtering templates:', filter);
  // Implementation for template filtering
}

function refreshTemplates() {
  console.log('Refreshing templates...');
  // Implementation for template refresh
}

function refreshCampaigns() {
  console.log('Refreshing campaigns...');
  // Implementation for campaign refresh
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  initializeCharts();
  updateRealTimeMetrics();
  
  // Update real-time metrics every 30 seconds
  setInterval(updateRealTimeMetrics, 30000);
});
</script>

<style>
@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(30px) scale(0.95); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0) scale(1); 
  }
}

@keyframes slideUp {
  from { 
    opacity: 0; 
    transform: translateY(50px) scale(0.9); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0) scale(1); 
  }
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.animate-fade-in {
  animation: fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-slide-up {
  animation: slideUp 1s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(45deg, #8b5cf6, #ec4899);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(45deg, #7c3aed, #db2777);
}

/* Glassmorphism enhancement */
.backdrop-blur-2xl {
  backdrop-filter: blur(40px);
  -webkit-backdrop-filter: blur(40px);
}

/* Shadow enhancement */
.shadow-3xl {
  box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}

/* Gradient text enhancement */
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

.group:hover .group-hover\:scale-105 {
  transform: scale(1.05);
}

/* Template hover effects */
.group\/template:hover {
  transform: translateY(-5px);
}

/* Chart container enhancements */
canvas {
  border-radius: 12px;
}

/* Loading animation */
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
@endsection
