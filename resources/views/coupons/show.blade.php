@php($title = 'عرض الكوبون')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="relative z-10 flex items-center justify-between">
          <div class="text-right">
            <h2 class="font-bold text-white flex items-center" style="font-size: 1.9rem;">
              <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-sm"><i class="ti ti-ticket text-white text-xl"></i></div>
              تفاصيل الكوبون
            </h2>
            <p class="text-blue-100 mt-2" style="font-size: 1.3rem;">{{ $coupon->code }} — خصم {{ $coupon->percentage }}%</p>
          </div>
          <div class="flex items-center gap-2">
            <a href="{{ route('coupons.edit', $coupon) }}" class="inline-flex items-center px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-2xl shadow" style="font-size: 1.2rem;"><i class="ti ti-edit mr-2"></i>تعديل</a>
            <a href="{{ route('coupons.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-2xl shadow" style="font-size: 1.2rem;"><i class="ti ti-arrow-right mr-2"></i>رجوع</a>
          </div>
        </div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
      </div>
      <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white/70 border border-white/20 rounded-2xl p-6">
          <div class="space-y-3 text-gray-700" style="font-size: 1.2rem;">
            <div>الكود: <span class="font-mono">{{ $coupon->code }}</span></div>
            <div>نسبة الخصم: <span class="font-semibold">{{ $coupon->percentage }}%</span></div>
            <div>النطاق: {{ $coupon->applies_to === 'all' ? 'كل الدورات' : 'دورات محددة' }}</div>
            <div>الفترة: {{ optional($coupon->starts_at)->format('Y-n-j') }} - {{ optional($coupon->ends_at)->format('Y-n-j') }}</div>
            <div>الحالة: <span class="inline-flex px-2.5 py-0.5 rounded-full font-medium {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $coupon->is_active ? 'نشط' : 'متوقف' }}</span></div>
          </div>
        </div>
        <div class="bg-white/70 border border-white/20 rounded-2xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">الدورات المرتبطة</h3>
          @if($coupon->applies_to === 'all')
            <p class="text-gray-500">ينطبق على جميع الدورات في المنصة.</p>
          @else
            <ul class="list-disc pr-6 text-gray-700 space-y-1" style="font-size: 1.1rem;">
              @forelse($coupon->courses as $course)
                <li>{{ $course->title }}</li>
              @empty
                <li class="text-gray-500">لا توجد دورات محددة.</li>
              @endforelse
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


