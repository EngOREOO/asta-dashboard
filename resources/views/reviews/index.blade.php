@php($title = 'التعليقات')
@extends('layouts.dash')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 font-arabic">
  <div class="space-y-8 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between animate-fade-in">
      <div class="space-y-2">
        <h1 class="font-bold bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-700 bg-clip-text text-transparent" style="font-size: 1.9rem;">التعليقات والتقييمات</h1>
        <p class="text-gray-600" style="font-size: 1.3rem;">إدارة تعليقات الطلاب والموافقة عليها</p>
      </div>
      <div class="mt-4 sm:mt-0 flex gap-2">
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800" style="font-size: 1.2rem;">قيد المراجعة: {{ $reviews->where('is_approved', null)->count() }}</span>
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800" style="font-size: 1.2rem;">المعتمدة: {{ $reviews->where('is_approved', true)->count() }}</span>
      </div>
    </div>

    <div class="bg-white/70 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 animate-slide-up">
      <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-600 px-8 py-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-cyan-500/20"></div>
        <div class="relative z-10">
          <h2 class="font-bold text-white" style="font-size: 1.3rem;">قائمة التعليقات</h2>
        </div>
      </div>
      <div class="p-8">
        @if($reviews->count() > 0)
        <div class="admin-table-container">
          <table class="admin-table" id="reviews-table" style="font-size: 1.2rem; text-align: center;">
            <thead class="bg-gray-50">
              <tr>
                <th>#</th>
                <th>الطالب</th>
                <th>الدورة</th>
                <th>التقييم</th>
                <th>التعليق</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>الإجراءات</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reviews as $review)
                <tr class="hover:bg-gray-50">
                  <td class="text-center">{{ $review->id }}</td>
                  <td>
                    <div class="grid [grid-template-columns:48px_1fr] items-center gap-3 justify-items-end mx-auto">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white flex items-center justify-center flex-shrink-0">
                        {{ Str::of($review->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                      </div>
                      <div class="text-right">
                        <div class="font-medium">{{ optional($review->user)->name ?? '—' }}</div>
                        <!-- <div class="email-content text-ltr">{{ optional($review->user)->email ?? '—' }}</div> -->
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="inline-block text-center">
                      <div class="font-medium">{{ \Illuminate\Support\Str::words(optional($review->course)->title ?? '—', 2, ' …') }}</div>
                      <div class="text-gray-500">{{ optional($review->course->instructor)->name ?? '—' }}</div>
                    </div>
                  </td>
                  <td>
                    <div class="inline-flex items-center justify-center gap-1">
                      <i class="ti ti-star-filled text-amber-500"></i>
                      <span class="font-medium">{{ $review->rating }}/5</span>
                    </div>
                  </td>
                  <td>
                    <div class="max-w-xs text-gray-700">{{ \Illuminate\Support\Str::words($review->message ?? 'بدون تعليق', 2, ' …') }}</div>
                  </td>
                  <td>
                    @if($review->is_approved === null)
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-800" style="font-size: 1.1rem;">قيد المراجعة</span>
                    @elseif($review->is_approved)
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-green-100 text-green-800" style="font-size: 1.1rem;">معتمدة</span>
                    @else
                      <span class="inline-flex px-2.5 py-0.5 rounded-full bg-red-100 text-red-800" style="font-size: 1.1rem;">مرفوضة</span>
                    @endif
                  </td>
                  <td>{{ $review->created_at ? $review->created_at->format('Y-n-j') : '—' }}</td>
                  <td class="text-center">
                    <div class="inline-flex items-center gap-2">
                      <a class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200" href="{{ route('reviews.show', $review) }}" title="عرض"><i class="ti ti-eye text-base"></i></a>
                      @if($review->is_approved !== true)
                      <form action="{{ route('reviews.approve', $review) }}" method="POST" class="inline" onsubmit="return confirm('اعتماد هذه المراجعة؟')">
                        @csrf
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-green-700 bg-green-100 hover:bg-green-200" type="submit" title="اعتماد"><i class="ti ti-check text-base"></i></button>
                      </form>
                      @endif
                      @if($review->is_approved !== false)
                      <form action="{{ route('reviews.reject', $review) }}" method="POST" class="inline" onsubmit="return confirm('رفض هذه المراجعة؟')">
                        @csrf
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-yellow-700 bg-yellow-100 hover:bg-yellow-200" type="submit" title="رفض"><i class="ti ti-x text-base"></i></button>
                      </form>
                      @endif
                      <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('حذف هذه المراجعة نهائياً؟')">
                        @csrf
                        @method('DELETE')
                        <button class="h-9 w-9 inline-flex items-center justify-center rounded-lg text-red-700 bg-red-100 hover:bg-red-200" type="submit" title="حذف"><i class="ti ti-trash text-base"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($reviews->hasPages())
          <div class="mt-6 flex justify-center">{{ $reviews->links() }}</div>
        @endif
        @else
          <div class="text-center py-12">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="ti ti-message-2 text-4xl text-blue-500"></i></div>
            <h3 class="font-medium text-gray-900 mb-2" style="font-size: 1.9rem;">لا توجد تعليقات</h3>
            <p class="text-gray-500 mb-6" style="font-size: 1.3rem;">لا توجد تعليقات حالياً للمراجعة.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#reviews-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[6, 'desc']],
        language: { sSearch: 'ابحث:' }
      });
    }
  });
</script>
<style>
  #reviews-table td, #reviews-table th { vertical-align: middle; text-align: center; }
  #reviews-table td .text-left { text-align: left; }
  #reviews-table td .text-right { text-align: right; }
  #reviews-table td > div { margin-left: auto; margin-right: auto; }
</style>
@endsection
