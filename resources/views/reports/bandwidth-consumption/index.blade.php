@php
    $title = 'استهلاك النطاق';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-700 bg-clip-text text-transparent">
          استهلاك النطاق
        </h1>
        <p class="text-lg text-gray-600 flex items-center">
          <i class="ti ti-network mr-2 text-indigo-500"></i>
          مراقبة استهلاك عرض النطاق الترددي
        </p>
      </div>
    </div>

    <!-- Statistics Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 hover:scale-105 transition-all duration-300 animate-slide-up">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600">إجمالي استهلاك النطاق</p>
          <p class="text-4xl font-bold text-indigo-600">{{ number_format($totalBandwidth, 0) }} GB</p>
          <p class="text-sm text-gray-500 mt-1">خلال آخر 12 شهر</p>
        </div>
        <div class="w-20 h-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
          <i class="ti ti-network text-white text-3xl"></i>
        </div>
      </div>
    </div>

    <!-- Bandwidth Chart -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-600 px-8 py-6">
        <h3 class="text-xl font-bold text-white flex items-center">
          <i class="ti ti-chart-line mr-3"></i>
          استهلاك النطاق الشهري
        </h3>
      </div>
      <div class="p-8">
        <canvas id="bandwidthChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Bandwidth Chart
const bandwidthCtx = document.getElementById('bandwidthChart').getContext('2d');
new Chart(bandwidthCtx, {
    type: 'line',
    data: {
        labels: @json($chartData['labels']),
        datasets: [{
            label: 'استهلاك النطاق (GB)',
            data: @json($chartData['bandwidth']),
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' GB';
                    }
                }
            }
        }
    }
});
</script>

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
