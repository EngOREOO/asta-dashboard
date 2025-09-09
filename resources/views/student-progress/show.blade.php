@php($title = 'تفاصيل تقدم الطالب')
@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">تقدم التعلم - {{ $user->name }}</h5>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="text-center">
              <div class="avatar avatar-xl mx-auto mb-2">
                <span class="avatar-initial rounded-circle bg-label-primary">
                  {{ Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                </span>
              </div>
              <h6>{{ $user->name }}</h6>
              <small class="text-muted email-content">{{ $user->email }}</small>
            </div>
          </div>
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-3 text-center">
                <h4 class="text-primary">{{ $enrollments->count() }}</h4>
                <small class="text-muted">الدورات المسجلة</small>
              </div>
              <div class="col-md-3 text-center">
                <h4 class="text-success">{{ $enrollments->whereNotNull('completed_at')->count() }}</h4>
                <small class="text-muted">مكتملة</small>
              </div>
              <div class="col-md-3 text-center">
                <h4 class="text-warning">{{ $enrollments->where('progress', '>', 0)->whereNull('completed_at')->count() }}</h4>
                <small class="text-muted">قيد التقدم</small>
              </div>
              <div class="col-md-3 text-center">
                <h4 class="text-info">{{ $materialCompletions->count() }}</h4>
                <small class="text-muted">المواد المكتملة</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">تسجيلات الدورات</h5>
      </div>
      <div class="card-body">
        @if($enrollments->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>الدورة</th>
                <th>المحاضر</th>
                <th>التقدم</th>
                <th>المسار</th>
                <th>الحالة</th>
              </tr>
            </thead>
            <tbody>
              @foreach($enrollments as $enrollment)
              <tr>
                <td>
                  <span class="fw-medium">{{ $enrollment->course_title }}</span>
                </td>
                <td>{{ $enrollment->instructor_name ?? 'لا يوجد مدرس' }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="progress me-2" style="width: 60px; height: 6px;">
                      <div class="progress-bar" role="progressbar" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                    </div>
                    <small>{{ $enrollment->progress ?? 0 }}%</small>
                  </div>
                </td>
                <td>
                  @if($enrollment->grade)
                    <span class="badge bg-label-success">{{ $enrollment->grade }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  @if($enrollment->completed_at)
                    <span class="badge bg-label-success">مكتملة</span>
                  @elseif(($enrollment->progress ?? 0) > 0)
                    <span class="badge bg-label-warning">قيد التقدم</span>
                  @else
                    <span class="badge bg-label-secondary">لم تبدأ</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-book-2 ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">لا توجد تسجيلات دورات</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">النشاط الأخير</h5>
      </div>
      <div class="card-body">
        @if($materialCompletions->count() > 0)
          @foreach($materialCompletions->take(10) as $completion)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              <span class="avatar-initial rounded-circle bg-label-success">
                <i class="ti ti-check"></i>
              </span>
            </div>
            <div class="flex-grow-1">
              <small class="fw-medium">{{ Str::limit($completion->material_title, 25) }}</small>
              <br><small class="text-muted">{{ $completion->course_title }}</small>
              <br><small class="text-muted">{{ \Carbon\Carbon::parse($completion->completed_at)->format('M j, Y') }}</small>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-activity ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">لا يوجد نشاط حديث</p>
          </div>
        @endif
      </div>
    </div>
    
    @if($assessmentAttempts->count() > 0)
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">محاولات الاختبارات</h5>
      </div>
      <div class="card-body">
        @foreach($assessmentAttempts->take(5) as $attempt)
        <div class="d-flex align-items-center mb-2">
          <div class="avatar avatar-sm me-2">
            <span class="avatar-initial rounded-circle bg-label-info">
              <i class="ti ti-help-circle"></i>
            </span>
          </div>
          <div class="flex-grow-1">
            <small class="fw-medium">{{ Str::limit($attempt->assessment_title, 25) }}</small>
            <br>            <small class="text-muted">
              النتيجة: {{ $attempt->score ?? 'غير متوفر' }}
              @if($attempt->completed_at)
                | {{ \Carbon\Carbon::parse($attempt->completed_at)->format('M j') }}
              @endif
            </small>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</div>

<div class="mt-3">
  <a href="{{ route('student-progress.index') }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>العودة لجميع الطلاب
  </a>
</div>
@endsection
