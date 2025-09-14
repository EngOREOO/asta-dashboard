@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="text-center mb-12">
      <div class="flex justify-center mb-6">
        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center">
          <i class="ti ti-quote text-white text-2xl"></i>
        </div>
      </div>
      <h1 class="text-4xl font-bold text-gray-800 mb-4">شهادات عملائنا</h1>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">
        اكتشف آراء عملائنا الكرام حول تجربتهم مع منصتنا التعليمية
      </p>
    </div>

    <!-- Call to Action -->
    <div class="text-center mb-12">
      <a href="{{ route('testimonial.form') }}" 
         class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl text-lg">
        <i class="ti ti-plus mr-2"></i>شاركنا رأيك
      </a>
    </div>

    <!-- Testimonials Grid -->
    @if($testimonials->count() > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        @foreach($testimonials as $testimonial)
          <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-3xl p-8 border border-white/20 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
            <!-- User Info -->
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 rounded-full overflow-hidden mr-4 flex-shrink-0">
                @if($testimonial->user_image)
                  <img src="{{ asset('storage/' . $testimonial->user_image) }}" 
                       alt="{{ $testimonial->user_name }}" 
                       class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <span class="text-white font-bold text-xl">{{ substr($testimonial->user_name, 0, 1) }}</span>
                  </div>
                @endif
              </div>
              <div>
                <h3 class="font-bold text-gray-800 text-lg">{{ $testimonial->user_name }}</h3>
                <div class="flex items-center mt-1">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="ti ti-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                  @endfor
                </div>
              </div>
            </div>

            <!-- Testimonial Content -->
            <div class="mb-6">
              <p class="text-gray-700 leading-relaxed text-lg" style="line-height: 1.8;">
                "{{ $testimonial->comment }}"
              </p>
            </div>

            <!-- Featured Badge -->
            @if($testimonial->is_featured)
              <div class="flex justify-end">
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                  <i class="ti ti-star mr-1"></i>مميز
                </span>
              </div>
            @endif

            <!-- Date -->
            <div class="text-right mt-4">
              <span class="text-gray-500 text-sm">
                {{ $testimonial->created_at->format('d M Y') }}
              </span>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="flex justify-center">
        {{ $testimonials->links() }}
      </div>
    @else
      <!-- Empty State -->
      <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="ti ti-quote text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-600 mb-4">لا توجد شهادات بعد</h3>
        <p class="text-gray-500 mb-8">كن أول من يشاركنا رأيه حول تجربته مع منصتنا</p>
        <a href="{{ route('testimonial.form') }}" 
           class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl">
          <i class="ti ti-plus mr-2"></i>شاركنا رأيك
        </a>
      </div>
    @endif
  </div>
</div>

<style>
@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.bg-white\/70 {
  animation: slide-up 0.6s ease-out;
}

.hover\:-translate-y-2:hover {
  animation: slide-up 0.3s ease-out;
}
</style>
@endsection
