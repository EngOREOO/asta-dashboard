@php
    $title = 'الشركاء';
@endphp
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    
    <!-- Success/Error Notifications -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-check-circle text-green-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-green-800" style="font-size: 1.3rem;">{{ session('success') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 animate-slide-down">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <i class="ti ti-alert-circle text-red-400 text-xl"></i>
        </div>
        <div class="mr-3">
          <p class="font-medium text-red-800" style="font-size: 1.3rem;">{{ session('error') }}</p>
        </div>
        <div class="mr-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button type="button" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
              <i class="ti ti-x text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">
          الشركاء التجاريون
        </h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-handshake mr-2 text-cyan-500"></i>
          إدارة الشركاء التجاريين والمنظمات
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('partners.create') }}" 
           class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 border border-transparent rounded-2xl font-semibold text-white uppercase tracking-widest hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105" style="font-size: 1.3rem;">
          <i class="ti ti-square-plus mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          إضافة شريك جديد
        </a>
      </div>
    </div>

    <!-- Partners Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-building text-white text-xl"></i>
            </div>
            قائمة الشركاء
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض وإدارة جميع الشركاء التجاريين</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        @if($partners->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover" id="partners-table">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">#</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الشعار</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الاسم</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الموقع</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الحالة</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">ترتيب العرض</th>
            <th class="px-6 py-4 text-right font-medium text-gray-500 uppercase tracking-wider" style="font-size: 1.3rem;">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($partners as $partner)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" style="font-size: 1.3rem;">{{ $partner->id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($partner->image)
                <div class="flex items-center">
                  <img src="{{ asset($partner->image) }}" 
                       alt="{{ $partner->name }}" 
                       class="h-12 w-12 rounded-lg shadow-sm object-cover border-2 border-gray-200">
                </div>
                @else
                <div class="flex items-center">
                  <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-sm">
                    <i class="ti ti-building text-white text-lg"></i>
                  </div>
                </div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div>
                  <div class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $partner->name }}</div>
                  @if($partner->description)
                  <div class="text-gray-500" style="font-size: 1.3rem;">{{ Str::limit($partner->description, 50) }}</div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($partner->website)
                <a href="{{ $partner->website }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                  <i class="ti ti-external-link mr-1 text-sm"></i>
                  <span style="font-size: 1.3rem;">زيارة الموقع</span>
                </a>
                @else
                <span class="text-gray-400" style="font-size: 1.3rem;">—</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($partner->is_active)
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                    نشط
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                    غير نشط
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800" style="font-size: 1.3rem;">
                  {{ $partner->sort_order ?? 0 }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap font-medium" style="font-size: 1.3rem;">
                <div class="flex items-center space-x-2 space-x-reverse">
                  <a href="{{ route('partners.show', $partner) }}" 
                     class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" style="font-size: 1.3rem;" title="عرض">
                    <i class="ti ti-eye text-sm"></i>
                  </a>
                  <a href="{{ route('partners.edit', $partner) }}" 
                     class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200" style="font-size: 1.3rem;" title="تعديل">
                    <i class="ti ti-edit text-sm"></i>
                  </a>
                  <form action="{{ route('partners.destroy', $partner) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الشريك؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" style="font-size: 1.3rem;" title="حذف">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($partners->hasPages())
          <div class="mt-6 flex justify-center">
            {{ $partners->links() }}
          </div>
        @endif
        @else
        <div class="text-center py-12">
          <div class="mb-6">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto">
              <i class="ti ti-handshake text-4xl text-blue-500"></i>
            </div>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا يوجد شركاء</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإضافة أول شريك تجاري لك.</p>
          <a href="{{ route('partners.create') }}" 
             class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl" style="font-size: 1.3rem;">
            <i class="ti ti-square-plus mr-2"></i>إضافة أول شريك
          </a>
        </div>
        @endif
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

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fadeIn 0.6s ease-out;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out;
}

.animate-slide-down {
  animation: slideDown 0.4s ease-out;
}

/* Custom scrollbar */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: linear-gradient(to right, #3b82f6, #8b5cf6);
  border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to right, #2563eb, #7c3aed);
}
</style>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#partners-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[5, 'asc']], // Sort by sort_order ascending
        language: {
          "sProcessing": "جاري التحميل...",
          "sLengthMenu": "أظهر _MENU_ سجل",
          "sZeroRecords": "لم يعثر على أية سجلات",
          "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل",
          "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
          "sInfoFiltered": "(منتقاة من مجموع _MAX_ سجل)",
          "sInfoPostFix": "",
          "sSearch": "ابحث:",
          "sUrl": "",
          "oPaginate": {
            "sFirst": "الأول",
            "sPrevious": "السابق",
            "sNext": "التالي",
            "sLast": "الأخير"
          }
        }
      });
    }

    // Auto-hide notifications after 5 seconds
    setTimeout(function() {
      const notifications = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
      notifications.forEach(notification => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
      });
    }, 5000);
  });
</script>
@endsection
