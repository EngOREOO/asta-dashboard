@php
    $title = 'تحليلات الماركتينج المتقدمة';
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
            <i class="ti ti-chart-bar text-white text-xl"></i>
          </div>
          <div>
            <h1 class="text-5xl font-black bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 bg-clip-text text-transparent leading-tight">
              تحليلات الماركتينج المتقدمة
            </h1>
            <p class="text-xl text-gray-600 font-medium">
              رؤى ذكية وأداء شامل للحملات التسويقية
            </p>
          </div>
        </div>
      </div>
      
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('admin.marketing.index') }}" class="group relative overflow-hidden bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-2xl flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <i class="ti ti-arrow-right ml-3 text-lg"></i>
          <span class="font-semibold text-lg">العودة</span>
        </a>
        <button onclick="exportAnalytics()" class="group relative overflow-hidden bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-8 py-4 rounded-2xl flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
          <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <i class="ti ti-download ml-3 text-lg"></i>
          <span class="font-semibold text-lg">تصدير التقرير</span>
        </button>
      </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl animate-slide-up">
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-700 px-8 py-6">
        <h3 class="text-2xl font-bold text-white flex items-center">
          <i class="ti ti-filter mr-3"></i>
          فلاتر متقدمة للتحليل
        </h3>
      </div>
      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">الفترة الزمنية</label>
            <select id="timeRange" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300">
              <option value="7">آخر 7 أيام</option>
              <option value="30" selected>آخر 30 يوم</option>
              <option value="90">آخر 90 يوم</option>
              <option value="365">آخر سنة</option>
              <option value="custom">فترة مخصصة</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">نوع الحملة</label>
            <select id="campaignType" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300">
              <option value="all">جميع الحملات</option>
              <option value="welcome">رسائل ترحيب</option>
              <option value="course">إعلانات دورات</option>
              <option value="newsletter">نشرات إخبارية</option>
              <option value="promotion">عروض ترويجية</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">المستلمين</label>
            <select id="recipientType" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300">
              <option value="all">جميع المستخدمين</option>
              <option value="students">الطلاب فقط</option>
              <option value="instructors">المدربين فقط</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">المقاييس</label>
            <select id="metrics" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300">
              <option value="all">جميع المقاييس</option>
              <option value="engagement">معدلات التفاعل</option>
              <option value="delivery">معدلات التسليم</option>
              <option value="conversion">معدلات التحويل</option>
            </select>
          </div>
        </div>
        
        <div class="flex justify-end mt-6 space-x-3 space-x-reverse">
          <button onclick="applyFilters()" class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
            <i class="ti ti-check ml-2"></i>
            تطبيق الفلاتر
          </button>
          <button onclick="resetFilters()" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-xl flex items-center shadow-lg hover:shadow-xl transition-all duration-300">
            <i class="ti ti-refresh ml-2"></i>
            إعادة تعيين
          </button>
        </div>
      </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 animate-slide-up">
      <!-- Total Sent -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-send text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">إجمالي المرسل</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_sent']) }}</p>
            <p class="text-sm text-gray-500 font-medium">رسالة إلكترونية</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-blue-500 to-cyan-600 h-2 rounded-full w-3/4 transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Delivered -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 via-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-check text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">تم التسليم</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($stats['total_delivered']) }}</p>
            <p class="text-sm text-gray-500 font-medium">رسالة</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full w-2/3 transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Open Rate -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-eye text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">معدل الفتح</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($stats['open_rate'], 1) }}%</p>
            <p class="text-sm text-gray-500 font-medium">من الرسائل المرسلة</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full w-1/2 transition-all duration-1000"></div>
          </div>
        </div>
      </div>

      <!-- Click Rate -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 hover:scale-105 hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative p-8">
          <div class="flex items-center justify-between mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500">
              <i class="ti ti-cursor-click text-white text-2xl"></i>
            </div>
            <div class="text-right">
              <div class="w-3 h-3 bg-orange-400 rounded-full animate-pulse"></div>
            </div>
          </div>
          <div class="space-y-2">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">معدل النقر</p>
            <p class="text-4xl font-black text-gray-900">{{ number_format($stats['click_rate'], 1) }}%</p>
            <p class="text-sm text-gray-500 font-medium">من الرسائل المفتوحة</p>
          </div>
          <div class="mt-6 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 h-2 rounded-full w-1/4 transition-all duration-1000"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Super Advanced Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-slide-up">
      <!-- Email Performance Trend Chart -->
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
                  <p class="text-blue-100 text-lg">معدلات الإرسال والفتح والنقر</p>
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
            <div class="h-96 relative">
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

      <!-- Campaign Types Distribution -->
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
                  <h3 class="text-2xl font-bold text-white">توزيع أنواع الحملات</h3>
                  <p class="text-green-100 text-lg">نسب الحملات حسب النوع</p>
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
            <div class="h-96 relative">
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

    <!-- Advanced Analytics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-slide-up">
      <!-- Engagement Heatmap -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-calendar text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">خريطة التفاعل</h3>
                <p class="text-purple-100 text-lg">أوقات الذروة للتفاعل</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="h-80 relative">
              <canvas id="heatmapChart"></canvas>
              <div id="heatmapChartLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div class="text-center">
                  <i class="ti ti-loader animate-spin text-3xl text-purple-500 mb-2"></i>
                  <p class="text-gray-600">جاري تحميل البيانات...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Conversion Funnel -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 via-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-funnel text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">قمع التحويل</h3>
                <p class="text-orange-100 text-lg">مسار المستخدمين</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="h-80 relative">
              <canvas id="funnelChart"></canvas>
              <div id="funnelChartLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 backdrop-blur-sm">
                <div class="text-center">
                  <i class="ti ti-loader animate-spin text-3xl text-orange-500 mb-2"></i>
                  <p class="text-gray-600">جاري تحميل البيانات...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Real-time Metrics -->
      <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <div class="relative">
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-8 py-6">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-pulse text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">المقاييس المباشرة</h3>
                <p class="text-indigo-100 text-lg">إحصائيات فورية</p>
              </div>
            </div>
          </div>
          <div class="p-8">
            <div class="space-y-6">
              <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-eye text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">الفتحات اليوم</p>
                    <p class="text-sm text-gray-600">آخر 24 ساعة</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-bold text-blue-600" id="todayOpens">0</p>
                  <p class="text-sm text-green-600">+12%</p>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-cursor-click text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">النقرات اليوم</p>
                    <p class="text-sm text-gray-600">آخر 24 ساعة</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-bold text-green-600" id="todayClicks">0</p>
                  <p class="text-sm text-green-600">+8%</p>
                </div>
              </div>

              <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl">
                <div class="flex items-center space-x-3 space-x-reverse">
                  <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="ti ti-send text-white"></i>
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">الرسائل المرسلة</p>
                    <p class="text-sm text-gray-600">آخر 24 ساعة</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-2xl font-bold text-purple-600" id="todaySent">0</p>
                  <p class="text-sm text-green-600">+15%</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Advanced Campaign Analytics Table -->
    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 animate-slide-up">
      <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
      <div class="relative">
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-700 px-8 py-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                <i class="ti ti-table text-white text-xl"></i>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-white">تحليل مفصل للحملات</h3>
                <p class="text-indigo-100 text-lg">مقارنة شاملة للأداء</p>
              </div>
            </div>
            <div class="flex space-x-2 space-x-reverse">
              <button onclick="exportTableData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="ti ti-download ml-2"></i>
                تصدير
              </button>
              <button onclick="refreshTableData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-all duration-300">
                <i class="ti ti-refresh ml-2"></i>
                تحديث
              </button>
            </div>
          </div>
        </div>
        <div class="p-8">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحملة</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">النوع</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المرسل</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">تم التسليم</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">معدل الفتح</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">معدل النقر</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">معدل التحويل</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">التاريخ</th>
                  <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الحالة</th>
                </tr>
              </thead>
              <tbody id="campaignsTableBody">
                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                  <td class="px-6 py-5 text-sm text-gray-900">لا توجد حملات</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                  <td class="px-6 py-5 text-sm text-gray-600">-</td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="text-center py-12">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="ti ti-chart-bar text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-500 mb-4">لا توجد بيانات تحليلية متاحة</h3>
            <p class="text-gray-400 text-lg mb-8">ابدأ بإرسال حملاتك التسويقية لرؤية التحليلات المتقدمة</p>
            <a href="{{ route('admin.marketing.create') }}" class="group relative overflow-hidden bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white px-8 py-4 rounded-2xl inline-flex items-center shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <i class="ti ti-plus ml-3 text-xl"></i>
              <span class="font-bold text-lg">إنشاء حملة جديدة</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- AI-Powered Insights -->
    <div class="group relative overflow-hidden bg-white/80 backdrop-blur-2xl rounded-3xl border border-white/30 shadow-2xl hover:shadow-3xl transition-all duration-700 animate-slide-up">
      <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-pink-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
      <div class="relative">
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-600 px-8 py-6">
          <div class="flex items-center space-x-3 space-x-reverse">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
              <i class="ti ti-brain text-white text-xl"></i>
            </div>
            <div>
              <h3 class="text-2xl font-bold text-white">رؤى ذكية مدعومة بالذكاء الاصطناعي</h3>
              <p class="text-purple-100 text-lg">تحليلات متقدمة وتوصيات ذكية</p>
            </div>
          </div>
        </div>
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200 hover:shadow-lg transition-all duration-300">
              <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4">
                <i class="ti ti-target text-white text-xl"></i>
              </div>
              <h4 class="font-semibold text-gray-900 mb-2">استهداف محسن</h4>
              <p class="text-sm text-gray-600 mb-4">استهدف المستخدمين النشطين في الساعات 9-11 صباحاً و 7-9 مساءً</p>
              <div class="text-xs text-blue-600 font-medium">توصية ذكية</div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200 hover:shadow-lg transition-all duration-300">
              <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4">
                <i class="ti ti-clock text-white text-xl"></i>
              </div>
              <h4 class="font-semibold text-gray-900 mb-2">التوقيت الأمثل</h4>
              <p class="text-sm text-gray-600 mb-4">أرسل رسائل الترحيب في أول 24 ساعة من التسجيل لزيادة التفاعل بنسبة 40%</p>
              <div class="text-xs text-green-600 font-medium">تحليل سلوكي</div>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200 hover:shadow-lg transition-all duration-300">
              <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4">
                <i class="ti ti-edit text-white text-xl"></i>
              </div>
              <h4 class="font-semibold text-gray-900 mb-2">محتوى جذاب</h4>
              <p class="text-sm text-gray-600 mb-4">استخدم عناوين تحتوي على أرقام أو أسئلة لزيادة معدل الفتح بنسبة 25%</p>
              <div class="text-xs text-purple-600 font-medium">تحليل محتوى</div>
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
// Global chart instances
let performanceChartInstance = null;
let distributionChartInstance = null;
let heatmapChartInstance = null;
let funnelChartInstance = null;

// Chart configurations
const chartConfigs = {
  performance: {
    type: 'line',
    data: {
      labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
      datasets: [{
        label: 'المرسل',
        data: [120, 150, 180, 200, 220, 250, 280, 300, 320, 350, 380, 400],
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
        data: [25, 28, 30, 32, 35, 38, 40, 42, 45, 48, 50, 52],
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
        data: [8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30],
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

  // Heatmap Chart
  const heatmapCtx = document.getElementById('heatmapChart');
  if (heatmapCtx) {
    setTimeout(() => {
      document.getElementById('heatmapChartLoading').style.display = 'none';
      heatmapChartInstance = new Chart(heatmapCtx.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['12ص', '2ص', '4ص', '6ص', '8ص', '10ص', '12م', '2م', '4م', '6م', '8م', '10م'],
          datasets: [{
            label: 'معدل التفاعل',
            data: [5, 8, 12, 18, 25, 30, 35, 40, 38, 32, 28, 22],
            backgroundColor: 'rgba(168, 85, 247, 0.8)',
            borderColor: 'rgb(168, 85, 247)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(0,0,0,0.1)'
              }
            },
            x: {
              grid: {
                color: 'rgba(0,0,0,0.1)'
              }
            }
          }
        }
      });
    }, 2500);
  }

  // Funnel Chart
  const funnelCtx = document.getElementById('funnelChart');
  if (funnelCtx) {
    setTimeout(() => {
      document.getElementById('funnelChartLoading').style.display = 'none';
      funnelChartInstance = new Chart(funnelCtx.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['المرسل', 'تم التسليم', 'تم الفتح', 'تم النقر', 'تم التحويل'],
          datasets: [{
            label: 'عدد المستخدمين',
            data: [1000, 950, 380, 95, 19],
            backgroundColor: [
              'rgba(245, 158, 11, 0.8)',
              'rgba(34, 197, 94, 0.8)',
              'rgba(59, 130, 246, 0.8)',
              'rgba(168, 85, 247, 0.8)',
              'rgba(236, 72, 153, 0.8)'
            ],
            borderColor: [
              'rgb(245, 158, 11)',
              'rgb(34, 197, 94)',
              'rgb(59, 130, 246)',
              'rgb(168, 85, 247)',
              'rgb(236, 72, 153)'
            ],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(0,0,0,0.1)'
              }
            },
            x: {
              grid: {
                color: 'rgba(0,0,0,0.1)'
              }
            }
          }
        }
      });
    }, 3000);
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

// Filter functions
function applyFilters() {
  const timeRange = document.getElementById('timeRange').value;
  const campaignType = document.getElementById('campaignType').value;
  const recipientType = document.getElementById('recipientType').value;
  const metrics = document.getElementById('metrics').value;
  
  console.log('Applying filters:', { timeRange, campaignType, recipientType, metrics });
  
  // Simulate loading
  showLoadingState();
  
  // Update charts with filtered data
  setTimeout(() => {
    updateChartsWithFilters({ timeRange, campaignType, recipientType, metrics });
    hideLoadingState();
  }, 2000);
}

function resetFilters() {
  document.getElementById('timeRange').value = '30';
  document.getElementById('campaignType').value = 'all';
  document.getElementById('recipientType').value = 'all';
  document.getElementById('metrics').value = 'all';
  
  // Reset charts to original data
  initializeCharts();
}

function updateChartsWithFilters(filters) {
  // Update performance chart based on filters
  if (performanceChartInstance) {
    const newData = generateFilteredData(filters);
    performanceChartInstance.data.datasets[0].data = newData.sent;
    performanceChartInstance.data.datasets[1].data = newData.opened;
    performanceChartInstance.data.datasets[2].data = newData.clicked;
    performanceChartInstance.update();
  }
}

function generateFilteredData(filters) {
  // Generate mock data based on filters
  const baseData = {
    sent: [120, 150, 180, 200, 220, 250, 280, 300, 320, 350, 380, 400],
    opened: [25, 28, 30, 32, 35, 38, 40, 42, 45, 48, 50, 52],
    clicked: [8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30]
  };
  
  // Apply filter multipliers
  let multiplier = 1;
  if (filters.campaignType === 'welcome') multiplier *= 1.2;
  if (filters.recipientType === 'students') multiplier *= 1.1;
  
  return {
    sent: baseData.sent.map(val => Math.round(val * multiplier)),
    opened: baseData.opened.map(val => Math.round(val * multiplier)),
    clicked: baseData.clicked.map(val => Math.round(val * multiplier))
  };
}

// Real-time updates
function updateRealTimeMetrics() {
  const todayOpens = document.getElementById('todayOpens');
  const todayClicks = document.getElementById('todayClicks');
  const todaySent = document.getElementById('todaySent');
  
  if (todayOpens) todayOpens.textContent = Math.floor(Math.random() * 50) + 20;
  if (todayClicks) todayClicks.textContent = Math.floor(Math.random() * 20) + 5;
  if (todaySent) todaySent.textContent = Math.floor(Math.random() * 100) + 50;
}

// Export functions
function exportAnalytics() {
  // Create export data
  const exportData = {
    timestamp: new Date().toISOString(),
    filters: {
      timeRange: document.getElementById('timeRange').value,
      campaignType: document.getElementById('campaignType').value,
      recipientType: document.getElementById('recipientType').value,
      metrics: document.getElementById('metrics').value
    },
    stats: {
      total_sent: {{ $stats['total_sent'] }},
      total_delivered: {{ $stats['total_delivered'] }},
      open_rate: {{ $stats['open_rate'] }},
      click_rate: {{ $stats['click_rate'] }}
    }
  };
  
  // Download as JSON
  const dataStr = JSON.stringify(exportData, null, 2);
  const dataBlob = new Blob([dataStr], {type: 'application/json'});
  const url = URL.createObjectURL(dataBlob);
  const link = document.createElement('a');
  link.href = url;
  link.download = 'marketing-analytics-' + new Date().toISOString().split('T')[0] + '.json';
  link.click();
  URL.revokeObjectURL(url);
}

function exportTableData() {
  console.log('Exporting table data...');
  // Implementation for table export
}

function refreshTableData() {
  console.log('Refreshing table data...');
  // Implementation for table refresh
}

function showLoadingState() {
  // Show loading indicators
  const loadingElements = document.querySelectorAll('[id$="ChartLoading"]');
  loadingElements.forEach(el => el.style.display = 'flex');
}

function hideLoadingState() {
  // Hide loading indicators
  const loadingElements = document.querySelectorAll('[id$="ChartLoading"]');
  loadingElements.forEach(el => el.style.display = 'none');
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
