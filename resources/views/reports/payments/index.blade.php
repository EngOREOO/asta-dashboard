@php
    $title = 'تقرير المدفوعات';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-yellow-600 via-orange-600 to-red-700 bg-clip-text text-transparent">
          تقرير المدفوعات
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-credit-card mr-2 text-yellow-500"></i>
          تحليل شامل للمعاملات المالية
        </p>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-slide-up">
      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي المدفوعات</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($totalPayments, 2) }} ريال</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-currency-dollar text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">إجمالي المعاملات</p>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalTransactions) }}</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-receipt text-white text-2xl"></i>
          </div>
        </div>
      </div>

      <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">متوسط المعاملة</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalTransactions > 0 ? number_format($totalPayments / $totalTransactions, 2) : 0 }} ريال</p>
          </div>
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
            <i class="ti ti-chart-bar text-white text-2xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Status Distribution -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-yellow-500 via-orange-500 to-red-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-chart-pie mr-3"></i>
          توزيع حالة المدفوعات
        </h3>
      </div>
      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="text-center">
            <div class="text-4xl font-bold text-green-600 mb-2">{{ number_format($paymentStatus['completed']) }}</div>
            <div class="text-gray-600">مكتملة</div>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-yellow-600 mb-2">{{ number_format($paymentStatus['pending']) }}</div>
            <div class="text-gray-600">في الانتظار</div>
          </div>
          <div class="text-center">
            <div class="text-4xl font-bold text-red-600 mb-2">{{ number_format($paymentStatus['failed']) }}</div>
            <div class="text-gray-600">فشلت</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}
</style>
@endsection
