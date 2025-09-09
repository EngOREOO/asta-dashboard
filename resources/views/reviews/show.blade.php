@php($title = 'تفاصيل المراجعة')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">تفاصيل المراجعة</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">عرض معلومات المراجعة واتخاذ الإجراءات</p>
      </div>
      <a href="{{ route('reviews.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-2xl text-gray-700 shadow hover:shadow-lg" style="font-size: 1.3rem;">
        <i class="ti ti-arrow-right mr-2"></i>العودة للمراجعات
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main -->
      <div class="lg:col-span-2 bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-8">
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">الدورة</h3>
          <div class="text-gray-900 font-medium">{{ optional($review->course)->title ?? '—' }}</div>
          <div class="text-gray-500">{{ optional($review->course->instructor)->name ?? '—' }}</div>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">الطالب</h3>
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center">
              {{ Str::of($review->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
            </div>
            <div>
              <div class="font-medium">{{ optional($review->user)->name ?? '—' }}</div>
              <div class="email-content text-ltr">{{ optional($review->user)->email ?? '—' }}</div>
            </div>
          </div>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">التقييم</h3>
          <div class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 px-3 py-1 rounded-lg">
            <i class="ti ti-star-filled"></i>
            <span class="font-medium">{{ $review->rating }}/5</span>
          </div>
        </div>

        @if($review->message)
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">نص المراجعة</h3>
          <div class="rounded-2xl border border-gray-200 bg-white/80 p-4 text-gray-700" style="font-size: 1.15rem;">{{ $review->message }}</div>
        </div>
        @endif

        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">تاريخ المراجعة</h3>
          <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-800" style="font-size: 1.1rem;">{{ $review->created_at ? $review->created_at->format('Y-n-j H:i') : '—' }}</span>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">حالة المراجعة</h3>
          <div class="text-center mb-4">
            @if($review->is_approved === null)
              <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-800" style="font-size: 1.1rem;">قيد المراجعة</span>
            @elseif($review->is_approved)
              <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-800" style="font-size: 1.1rem;">معتمدة</span>
            @else
              <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-800" style="font-size: 1.1rem;">مرفوضة</span>
            @endif
          </div>
          <div class="flex items-center justify-center gap-2">
            @if($review->is_approved !== true)
            <form action="{{ route('reviews.approve', $review) }}" method="POST" onsubmit="return confirm('اعتماد هذه المراجعة؟')">
              @csrf
              <button class="inline-flex items-center px-4 py-2 rounded-xl bg-green-600 text-white shadow hover:bg-green-700" type="submit"><i class="ti ti-check mr-1"></i>اعتماد</button>
            </form>
            @endif
            @if($review->is_approved !== false)
            <form action="{{ route('reviews.reject', $review) }}" method="POST" onsubmit="return confirm('رفض هذه المراجعة؟')">
              @csrf
              <button class="inline-flex items-center px-4 py-2 rounded-xl bg-yellow-500 text-white shadow hover:bg-yellow-600" type="submit"><i class="ti ti-x mr-1"></i>رفض</button>
            </form>
            @endif
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('حذف هذه المراجعة نهائيًا؟')">
              @csrf
              @method('DELETE')
              <button class="inline-flex items-center px-4 py-2 rounded-xl bg-red-600 text-white shadow hover:bg-red-700" type="submit"><i class="ti ti-trash mr-1"></i>حذف</button>
            </form>
          </div>
        </div>

        @if($review->updated_at && $review->updated_at != $review->created_at)
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">آخر تحديث</h3>
          <div class="text-gray-600">{{ $review->updated_at->format('Y-n-j H:i') }}</div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
