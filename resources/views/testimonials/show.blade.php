@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
  <div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4">
            <i class="ti ti-quote text-white text-xl"></i>
          </div>
          <div>
            <h1 class="font-bold text-gray-800" style="font-size: 2rem;">عرض الشهادة</h1>
            <p class="text-gray-600" style="font-size: 1.1rem;">تفاصيل شهادة {{ $testimonial->user_name }}</p>
          </div>
        </div>
        <div class="flex items-center space-x-4 rtl:space-x-reverse">
          <a href="{{ route('testimonials.edit', $testimonial) }}" 
             class="bg-gradient-to-r from-green-600 to-teal-600 text-white px-6 py-3 rounded-2xl font-semibold hover:from-green-700 hover:to-teal-700 transition-all duration-300 shadow-lg hover:shadow-xl">
            <i class="ti ti-edit mr-2"></i>تعديل
          </a>
          <a href="{{ route('testimonials.index') }}" 
             class="bg-white/70 backdrop-blur-xl shadow-lg rounded-2xl px-6 py-3 text-gray-700 font-semibold hover:shadow-xl transition-all duration-300 border border-white/20">
            <i class="ti ti-arrow-right mr-2"></i>العودة للقائمة
          </a>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2">
        <!-- Testimonial Card -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 mb-8">
          <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20"></div>
            <div class="relative z-10">
              <h2 class="font-bold text-white flex items-center" style="font-size: 1.6rem;">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                  <i class="ti ti-quote text-white"></i>
                </div>
                الشهادة
              </h2>
            </div>
          </div>

          <div class="p-8">
            <!-- User Info -->
            <div class="flex items-center mb-8">
              @if($testimonial->user_image)
                <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 mr-6">
                  <img src="{{ $testimonial->user_image_url }}" alt="{{ $testimonial->user_name }}" 
                       class="w-full h-full object-cover">
                </div>
              @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-gray-400 to-gray-500 flex items-center justify-center mr-6">
                  <i class="ti ti-user text-white text-2xl"></i>
                </div>
              @endif
              
              <div>
                <h3 class="font-bold text-gray-800 text-2xl">{{ $testimonial->user_name }}</h3>
                <div class="flex items-center mt-2">
                  @for($i = 1; $i <= 5; $i++)
                    @if($i <= $testimonial->rating)
                      <i class="ti ti-star-filled text-yellow-400 text-xl"></i>
                    @else
                      <i class="ti ti-star text-gray-300 text-xl"></i>
                    @endif
                  @endfor
                  <span class="mr-3 text-gray-600 font-semibold text-lg">{{ $testimonial->rating }}/5</span>
                </div>
              </div>
            </div>

            <!-- Comment -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
              <h4 class="font-bold text-gray-800 mb-4" style="font-size: 1.2rem;">
                <i class="ti ti-message-circle mr-2 text-purple-600"></i>التعليق
              </h4>
              <p class="text-gray-700 text-lg leading-relaxed">{{ $testimonial->comment }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-8">
        <!-- Status Information -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-green-500 via-teal-500 to-cyan-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-teal-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.3rem;">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                  <i class="ti ti-info-circle text-white"></i>
                </div>
                معلومات الحالة
              </h3>
            </div>
          </div>

          <div class="p-6 space-y-4">
            <!-- Approval Status -->
            <div class="flex items-center justify-between">
              <span class="text-gray-700 font-semibold">حالة الموافقة:</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                {{ $testimonial->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                <i class="ti ti-{{ $testimonial->is_approved ? 'check-circle' : 'clock' }} mr-1"></i>
                {{ $testimonial->is_approved ? 'موافق عليه' : 'في الانتظار' }}
              </span>
            </div>

            <!-- Featured Status -->
            <div class="flex items-center justify-between">
              <span class="text-gray-700 font-semibold">حالة التمييز:</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                {{ $testimonial->is_featured ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                <i class="ti ti-star mr-1"></i>
                {{ $testimonial->is_featured ? 'مميز' : 'عادي' }}
              </span>
            </div>

            <!-- Sort Order -->
            <div class="flex items-center justify-between">
              <span class="text-gray-700 font-semibold">ترتيب العرض:</span>
              <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-semibold">
                {{ $testimonial->sort_order }}
              </span>
            </div>
          </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-rose-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.3rem;">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                  <i class="ti ti-clock text-white"></i>
                </div>
                التواريخ
              </h3>
            </div>
          </div>

          <div class="p-6 space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-gray-700 font-semibold">تاريخ الإنشاء:</span>
              <span class="text-gray-600">{{ $testimonial->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-700 font-semibold">آخر تحديث:</span>
              <span class="text-gray-600">{{ $testimonial->updated_at->format('Y-m-d H:i') }}</span>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
          <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-600 px-6 py-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20"></div>
            <div class="relative z-10">
              <h3 class="font-bold text-white flex items-center" style="font-size: 1.3rem;">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                  <i class="ti ti-settings text-white"></i>
                </div>
                إجراءات سريعة
              </h3>
            </div>
          </div>

          <div class="p-6 space-y-3">
            <!-- Toggle Approval -->
            <button onclick="toggleApproval({{ $testimonial->id }})" 
                    class="w-full bg-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-100 text-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-700 px-4 py-3 rounded-xl font-semibold hover:bg-{{ $testimonial->is_approved ? 'yellow' : 'green' }}-200 transition-all duration-300">
              <i class="ti ti-{{ $testimonial->is_approved ? 'ban' : 'check' }} mr-2"></i>
              {{ $testimonial->is_approved ? 'إلغاء الموافقة' : 'الموافقة' }}
            </button>

            <!-- Toggle Featured -->
            <button onclick="toggleFeatured({{ $testimonial->id }})" 
                    class="w-full bg-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-100 text-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-700 px-4 py-3 rounded-xl font-semibold hover:bg-{{ $testimonial->is_featured ? 'gray' : 'purple' }}-200 transition-all duration-300">
              <i class="ti ti-star mr-2"></i>
              {{ $testimonial->is_featured ? 'إلغاء التمييز' : 'تمييز' }}
            </button>

            <!-- Delete -->
            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" 
                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الشهادة؟')">
              @csrf
              @method('DELETE')
              <button type="submit" 
                      class="w-full bg-red-100 text-red-700 px-4 py-3 rounded-xl font-semibold hover:bg-red-200 transition-all duration-300">
                <i class="ti ti-trash mr-2"></i>حذف الشهادة
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleApproval(testimonialId) {
  fetch(`/testimonials/${testimonialId}/toggle-approval`, {
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
  fetch(`/testimonials/${testimonialId}/toggle-featured`, {
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
