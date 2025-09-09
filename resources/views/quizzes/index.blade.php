@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header d-md-flex align-items-center justify-content-between">
    <h5 class="mb-2 mb-md-0" style="font-size: 1.9rem;">إدارة الاختبارات</h5>
    <div class="d-flex gap-2">
      <a href="{{ route('quizzes.create') }}" class="btn btn-primary" style="font-size: 1.3rem;">
        <i class="ti ti-square-plus me-1"></i>إنشاء اختبار
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($quizzes->count() > 0)
      <div class="table-responsive">
        <table class="table" id="quizzes-table">
          <thead>
            <tr>
              <th style="font-size: 1.3rem;">#</th>
              <th style="font-size: 1.3rem;">الاختبار</th>
              <th style="font-size: 1.3rem;">الدورة</th>
              <th style="font-size: 1.3rem;">الأسئلة</th>
              <th style="font-size: 1.3rem;">المحاولات</th>
              <th style="font-size: 1.3rem;">المدة</th>
              <th style="font-size: 1.3rem;">الحالة</th>
              <th style="font-size: 1.3rem;">الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            @foreach($quizzes as $quiz)
              <tr>
                <td style="font-size: 1.3rem;">{{ $quiz->id }}</td>
                <td>
                  <div>
                    <span class="fw-medium" style="font-size: 1.3rem;">{{ $quiz->title }}</span>
                    @if($quiz->description)
                      <br><small class="text-muted" style="font-size: 1.3rem;">{{ Str::limit($quiz->description, 50) }}</small>
                    @endif
                  </div>
                </td>
                <td>
                  <div>
                    <span class="fw-medium" style="font-size: 1.3rem;">{{ $quiz->course->title ?? 'لا توجد دورة' }}</span>
                    @if($quiz->course && $quiz->course->instructor)
                      <br><small class="text-muted" style="font-size: 1.3rem;">بواسطة {{ $quiz->course->instructor->name }}</small>
                    @endif
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-info" style="font-size: 1.3rem;">{{ $quiz->questions_count ?? 0 }}</span>
                </td>
                <td>
                  <span class="badge bg-label-success" style="font-size: 1.3rem;">{{ $quiz->attempts_count ?? 0 }}</span>
                </td>
                <td>
                  @if($quiz->duration_minutes)
                    <span class="text-muted" style="font-size: 1.3rem;">{{ $quiz->duration_minutes }} دقيقة</span>
                  @else
                    <span class="text-muted" style="font-size: 1.3rem;">بدون حد</span>
                  @endif
                </td>
                <td>
                  @if($quiz->is_active)
                    <span class="badge bg-label-success" style="font-size: 1.3rem;">نشط</span>
                  @else
                    <span class="badge bg-label-secondary" style="font-size: 1.3rem;">غير نشط</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-sm btn-outline-primary" style="font-size: 1.3rem;">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="{{ route('quizzes.questions', $quiz) }}" class="btn btn-sm btn-outline-info" style="font-size: 1.3rem;">
                      <i class="ti ti-list"></i>
                    </a>
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-secondary" style="font-size: 1.3rem;">
                      <i class="ti ti-edit"></i>
                    </a>
                      <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟ سيتم حذف جميع الأسئلة والمحاولات.')">
                      @csrf
                      @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 1.3rem;">
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
      @if($quizzes->hasPages())
        <div class="mt-4">
          {{ $quizzes->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-5">
        <div class="mb-3">
          <i class="ti ti-help-circle ti-lg text-muted"></i>
        </div>
        <h6 class="mb-2" style="font-size: 1.9rem;">لا توجد اختبارات</h6>
        <p class="text-muted mb-4" style="font-size: 1.3rem;">ابدأ بإنشاء اختبارات لتقييم معرفة طلابك.</p>
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary" style="font-size: 1.3rem;">
          <i class="ti ti-square-plus me-2"></i>إنشاء أول اختبار
        </a>
      </div>
    @endif
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#quizzes-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']]
      });
    }
  });
</script>
@endsection
