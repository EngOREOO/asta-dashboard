@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-quote text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">الشهادات</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">إدارة شهادات العملاء والمراجعات</p>
          </div>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" 
           class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
          <i class="ti ti-plus mr-2"></i>إضافة شهادة جديدة
        </a>
      </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl">
      <div class="flex items-center">
        <i class="ti ti-check-circle mr-2"></i>
        {{ session('success') }}
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl">
      <div class="flex items-center">
        <i class="ti ti-alert-circle mr-2"></i>
        {{ session('error') }}
      </div>
    </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
              <i class="ti ti-list text-white"></i>
            </div>
            قائمة الشهادات
          </h2>
        </div>
      </div>

      <div class="p-8">
        @if($testimonials->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-right py-4 px-6 font-bold text-gray-800">الصورة</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">الاسم</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">التقييم</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">التعليق</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">الحالة</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">الترتيب</th>
                  <th class="text-right py-4 px-6 font-bold text-gray-800">الإجراءات</th>
                </tr>
              </thead>
              <tbody>
                @foreach($testimonials as $testimonial)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                  <!-- User Image -->
                  <td class="py-4 px-6">
                    @if($testimonial->user_image)
                      <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
                        <img src="{{ $testimonial->user_image_url }}" alt="{{ $testimonial->user_name }}" 
                             class="w-full h-full object-cover">
                      </div>
                    @else
                      <div class="w-12 h-12 rounded-full bg-gradient-to-r from-gray-400 to-gray-500 flex items-center justify-center">
                        <i class="ti ti-user text-white"></i>
                      </div>
                    @endif
                  </td>

                  <!-- User Name -->
                  <td class="py-4 px-6">
                    <div class="font-semibold text-gray-800">{{ $testimonial->user_name }}</div>
                    <div class="text-sm text-gray-500">{{ $testimonial->created_at->format('Y-m-d') }}</div>
                  </td>

                  <!-- Rating -->
                  <td class="py-4 px-6">
                    <div class="flex items-center">
                      @for($i = 1; $i <= 5; $i++)
                        @if($i <= $testimonial->rating)
                          <i class="ti ti-star-filled text-yellow-400 text-lg"></i>
                        @else
                          <i class="ti ti-star text-gray-300 text-lg"></i>
                        @endif
                      @endfor
                      <span class="mr-2 text-gray-600 font-semibold">{{ $testimonial->rating }}/5</span>
                    </div>
                  </td>

                  <!-- Comment -->
                  <td class="py-4 px-6">
                    <div class="max-w-xs">
                      <p class="text-gray-700 text-sm line-clamp-2">{{ Str::limit($testimonial->comment, 100) }}</p>
                    </div>
                  </td>

                  <!-- Status -->
                  <td class="py-4 px-6">
                    <div class="flex flex-col space-y-2">
                      <!-- Approval Status -->
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        {{ $testimonial->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        <i class="ti ti-{{ $testimonial->is_approved ? 'check-circle' : 'clock' }} mr-1"></i>
                        {{ $testimonial->is_approved ? 'موافق عليه' : 'في الانتظار' }}
                      </span>
                      
                      <!-- Featured Status -->
                      @if($testimonial->is_featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                          <i class="ti ti-star mr-1"></i>
                          مميز
                        </span>
                      @endif
                    </div>
                  </td>

                  <!-- Sort Order -->
                  <td class="py-4 px-6">
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-semibold">
                      {{ $testimonial->sort_order }}
                    </span>
                  </td>

                  <!-- Actions -->
                  <td class="py-4 px-6">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                      <!-- View -->
                      <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                         class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200 transition-colors duration-200"
                         title="عرض">
                        <i class="ti ti-eye"></i>
                      </a>

                      <!-- Edit -->
                      <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                         class="bg-green-100 text-green-600 p-2 rounded-lg hover:bg-green-200 transition-colors duration-200"
                         title="تعديل">
                        <i class="ti ti-edit"></i>
                      </a>

                      <!-- Toggle Approval -->
                      <button onclick="toggleApproval({{ $testimonial->id }})" 
                              class="bg-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-100 text-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-600 p-2 rounded-lg hover:bg-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-200 transition-colors duration-200"
                              title="{{ $testimonial->is_approved ? 'إلغاء الموافقة' : 'الموافقة' }}">
                        <i class="ti ti-{{ $testimonial->is_approved ? 'ban' : 'check' }}"></i>
                      </button>

                      <!-- Toggle Featured -->
                      <button onclick="toggleFeatured({{ $testimonial->id }})" 
                              class="bg-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-100 text-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-600 p-2 rounded-lg hover:bg-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-200 transition-colors duration-200"
                              title="{{ $testimonial->is_featured ? 'إلغاء التمييز' : 'تمييز' }}">
                        <i class="ti ti-star"></i>
                      </button>

                      <!-- Delete -->
                      <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline"
                            onsubmit="return confirm('هل أنت متأكد من حذف هذه الشهادة؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition-colors duration-200"
                                title="حذف">
                          <i class="ti ti-trash"></i>
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
          <div class="mt-8">
            {{ $testimonials->links() }}
          </div>
        @else
          <!-- Empty State -->
          <div class="text-center py-12">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="ti ti-quote text-white text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">لا توجد شهادات</h3>
            <p class="text-gray-500 mb-6">لم يتم إضافة أي شهادات بعد</p>
            <a href="{{ route('admin.testimonials.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
              <i class="ti ti-plus mr-2"></i>إضافة أول شهادة
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
function toggleApproval(testimonialId) {
  fetch(`/admin/testimonials/${testimonialId}/toggle-approval`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    },
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('حدث خطأ أثناء تحديث حالة الموافقة');
  });
}

function toggleFeatured(testimonialId) {
  fetch(`/admin/testimonials/${testimonialId}/toggle-featured`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Content-Type': 'application/json',
    },
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      location.reload();
    } else {
      alert(data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('حدث خطأ أثناء تحديث حالة التمييز');
  });
}
</script>
@endsection