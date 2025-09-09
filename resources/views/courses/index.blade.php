@php
    $title = 'الدورات';
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
          الدورات التعليمية
        </h1>
        <p class="text-gray-600 flex items-center" style="font-size: 1.3rem;">
          <i class="ti ti-book mr-2 text-cyan-500"></i>
          إدارة جميع الدورات التعليمية في النظام
        </p>
      </div>
      <div class="mt-4 sm:mt-0">
        <a href="{{ route('courses.create') }}" 
           class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 border border-transparent rounded-2xl font-semibold text-white uppercase tracking-widest hover:from-teal-600 hover:via-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 disabled:opacity-25 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105" style="font-size: 1.3rem;">
          <i class="ti ti-square-plus mr-2 group-hover:rotate-12 transition-transform duration-300"></i>
          إضافة دورة جديدة
        </a>
      </div>
    </div>

    <!-- Courses Table -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm">
              <i class="ti ti-book text-white text-xl"></i>
            </div>
            قائمة الدورات
          </h2>
          <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">عرض وإدارة جميع الدورات التعليمية</p>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      
      <div class="p-8">
        <!-- Filters -->
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-6 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">القسم</label>
            <select name="category_id" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500">
              <option value="">الكل</option>
              @isset($categories)
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" @selected(($filters['category_id'] ?? '') == $cat->id)>{{ $cat->name }}</option>
                @endforeach
              @endisset
            </select>
          </div>
          @if(isset($instructors) && $instructors->count())
          <div>
            <label class="block text-sm text-gray-600 mb-1">المحاضر</label>
            <select name="instructor_id" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500">
              <option value="">الكل</option>
              @foreach($instructors as $inst)
                <option value="{{ $inst->id }}" @selected(($filters['instructor_id'] ?? '') == $inst->id)>{{ $inst->name }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div>
            <label class="block text-sm text-gray-600 mb-1">السعر الأدنى</label>
            <input type="number" step="0.01" name="price_min" value="{{ $filters['price_min'] ?? '' }}" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">السعر الأقصى</label>
            <input type="number" step="0.01" name="price_max" value="{{ $filters['price_max'] ?? '' }}" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">الخصم</label>
            <select name="discount" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500">
              <option value="">الكل</option>
              <option value="yes" @selected(($filters['discount'] ?? '')==='yes')>به خصم</option>
              <option value="no" @selected(($filters['discount'] ?? '')==='no')>بدون خصم</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">الحالة</label>
            <select name="status" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500">
              <option value="">الكل</option>
              @php($statuses=['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'])
              @foreach($statuses as $key=>$label)
                <option value="{{ $key }}" @selected(($filters['status'] ?? '')===$key)>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">ترتيب الطلاب</label>
            <select name="students_order" class="w-full rounded-xl border-gray-300 focus:border-cyan-500 focus:ring-cyan-500">
              <option value="">الافتراضي</option>
              <option value="desc" @selected(($filters['students_order'] ?? '')==='desc')>من الأعلى إلى الأقل</option>
              <option value="asc" @selected(($filters['students_order'] ?? '')==='asc')>من الأقل إلى الأعلى</option>
            </select>
          </div>
          <div class="md:col-span-6 flex items-end gap-3">
            <button class="px-5 py-2.5 rounded-xl bg-cyan-600 text-white hover:bg-cyan-700">تصفية</button>
            <a href="{{ route('courses.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200">مسح</a>
          </div>
        </form>
        @if($courses->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover" id="courses-table">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">#</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">الصورة</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">اسم الدورة</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">المحاضر</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">القسم</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">السعر</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">الخصم</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">الحالة</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">الطلاب</th>
            <th class="px-6 py-4 text-right font-medium text-black uppercase tracking-wider" style="font-size: 1.3rem;">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach($courses as $course)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" style="font-size: 1.3rem;">{{ $course->id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if($course->thumbnail && file_exists(public_path($course->thumbnail)))
                  <img src="{{ asset($course->thumbnail) }}" 
                       alt="{{ $course->title }}" 
                       class="h-12 w-12 rounded-lg shadow-sm object-cover border-2 border-gray-200">
                @else
                  <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-sm">
                    <i class="ti ti-book text-white text-lg"></i>
                  </div>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div>
                  <div class="font-medium text-gray-900" style="font-size: 1.3rem;">{{ $course->title }}</div>
                  @if($course->description)
                  <div class="text-gray-500" style="font-size: 1.3rem;">{{ Str::limit($course->description, 50) }}</div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-gray-900" style="font-size: 1.3rem;">
                  {{ optional($course->instructor)->name ?? 'بدون مدرس' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-gray-900" style="font-size: 1.3rem;">
                  {{ optional($course->category)->name ?? '—' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @php($original = (float)($course->price ?? 0))
                @php($discounted = (float)$course->discounted_price)
                @if($original > 0)
                  @if($discounted < $original)
                    <div class="flex items-center gap-2">
                      <span class="line-through text-gray-400" style="font-size: 1.2rem;">{{ number_format($original, 2) }}</span>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800" style="font-size: 1.3rem;">
                        {{ number_format($discounted, 2) }}
                        <img src="{{ asset('riyal.svg') }}" style="width: 1.5rem; height: 1.5rem; filter: brightness(0);" alt="ريال" class="w-3 h-3 ml-1">
                      </span>
                    </div>
                  @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800" style="font-size: 1.3rem;">
                      {{ number_format($original, 2) }}
                      <img src="{{ asset('riyal.svg') }}" style="width: 1.5rem; height: 1.5rem; filter: brightness(0);" alt="ريال" class="w-3 h-3 ml-1">
                    </span>
                  @endif
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">مجاني</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900" style="font-size: 1.3rem;">
                @php($original = (float)($course->price ?? 0))
                @php($discounted = (float)$course->discounted_price)
                @if($original > 0 && $discounted < $original)
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-pink-100 text-pink-800" style="font-size: 1.3rem;">
                    خصم {{ round((1 - ($discounted/$original)) * 100) }}%
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">لا يوجد</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @php($statusAr = ['approved'=>'معتمدة','pending'=>'قيد المراجعة','rejected'=>'مرفوضة','draft'=>'مسودة'][$course->status] ?? $course->status)
                @if($course->status === 'approved')
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-green-100 text-green-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                    {{ $statusAr }}
                  </span>
                @elseif($course->status === 'pending')
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-yellow-100 text-yellow-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></div>
                    {{ $statusAr }}
                  </span>
                @elseif($course->status === 'rejected')
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-red-100 text-red-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></div>
                    {{ $statusAr }}
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800" style="font-size: 1.3rem;">
                    <div class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></div>
                    {{ $statusAr }}
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-900" style="font-size: 1.3rem;">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-indigo-100 text-indigo-800" style="font-size: 1.3rem;">
                  {{ $course->students_count ?? 0 }} طالب
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap font-medium" style="font-size: 1.3rem;">
                <div class="flex items-center space-x-2 space-x-reverse">
                  <a href="{{ route('courses.show', $course) }}" 
                     class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200" style="font-size: 1.3rem;" title="عرض">
                    <i class="ti ti-eye text-sm"></i>
                  </a>
                  @can('update', $course)
                  <a href="{{ route('courses.edit', $course) }}" 
                     class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200" style="font-size: 1.3rem;" title="تعديل">
                    <i class="ti ti-edit text-sm"></i>
                  </a>
                  @endcan
                  @can('delete', $course)
                  <form action="{{ route('courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدورة؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" style="font-size: 1.3rem;" title="حذف">
                      <i class="ti ti-trash text-sm"></i>
                    </button>
                  </form>
                  @endcan
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        @if($courses->hasPages())
          <div class="mt-6 flex justify-center">
            {{ $courses->links() }}
          </div>
        @endif
        @else
        <div class="text-center py-12">
          <div class="mb-6">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto">
              <i class="ti ti-book text-4xl text-blue-500"></i>
            </div>
          </div>
          <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد دورات</h3>
          <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">ابدأ بإنشاء دورة جديدة لطلابك.</p>
          <a href="{{ route('courses.create') }}" 
             class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 border border-transparent rounded-xl font-semibold text-white uppercase tracking-widest hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl" style="font-size: 1.3rem;">
            <i class="ti ti-square-plus mr-2"></i>إنشاء دورة جديدة
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
      jQuery('#courses-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']], // Sort by ID descending
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