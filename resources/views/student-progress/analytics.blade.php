@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($totalStudents) }}</h3>
        <p class="text-muted mb-0">إجمالي الطلاب</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-success"><i class="ti ti-user-check ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($activeStudents) }}</h3>
        <p class="text-muted mb-0">الطلاب النشطون</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-info"><i class="ti ti-percentage ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($averageProgress, 1) }}%</h3>
        <p class="text-muted mb-0">متوسط التقدم</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-trophy ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($completionRate, 1) }}%</h3>
        <p class="text-muted mb-0">معدل الإكمال</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">أفضل الطلاب أداءً</h5>
      </div>
      <div class="card-body">
        @if($topPerformers->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>الطالب</th>
                <th class="email-label-arabic">البريد الإلكتروني</th>
                <th>الدورات المُسجّل بها</th>
                <th>متوسط التقدم</th>
                <th>الأداء</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topPerformers as $student)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                      @if($student->avatar)
                        <img src="{{ Storage::url($student->avatar) }}" alt="{{ $student->name }}" class="rounded-circle">
                      @else
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          {{ Str::of($student->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                        </span>
                      @endif
                    </div>
                    <div>
                      <span class="fw-medium">{{ $student->name }}</span>
                    </div>
                  </div>
                </td>
                <td class="email-content">{{ $student->email }}</td>
                <td>
                  <span class="badge bg-label-primary">{{ $student->course_count }}</span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="progress me-2" style="width: 60px; height: 6px;">
                      <div class="progress-bar" role="progressbar" style="width: {{ $student->avg_progress }}%"></div>
                    </div>
                    <small>{{ number_format($student->avg_progress, 1) }}%</small>
                  </div>
                </td>
                <td>
                  @php
                    $performance = $student->avg_progress >= 80 ? 'Excellent' : 
                                 ($student->avg_progress >= 60 ? 'Good' : 
                                 ($student->avg_progress >= 40 ? 'Average' : 'Needs Improvement'));
                    $badgeColor = $student->avg_progress >= 80 ? 'success' : 
                                ($student->avg_progress >= 60 ? 'info' : 
                                ($student->avg_progress >= 40 ? 'warning' : 'danger'));
                  @endphp
                  <span class="badge bg-label-{{ $badgeColor }}">{{ $performance }}</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-users ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">No student progress data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Progress Trends</h5>
      </div>
      <div class="card-body">
        @if($progressByMonth->count() > 0)
          @foreach($progressByMonth as $month)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              <span class="avatar-initial rounded-circle bg-label-success">
                <i class="ti ti-calendar"></i>
              </span>
            </div>
            <div class="flex-grow-1">
              <span class="fw-medium">{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}</span>
              <br><small class="text-muted">{{ $month->completions }} completions</small>
            </div>
            <div>
              <span class="badge bg-label-success">{{ $month->completions }}</span>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-chart-line ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No progress trends available</p>
          </div>
        @endif
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Quick Stats</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="border-end">
              <h4 class="mb-1 text-primary">{{ number_format(($activeStudents / max($totalStudents, 1)) * 100, 1) }}%</h4>
              <small class="text-muted">Active Rate</small>
            </div>
          </div>
          <div class="col-6">
            <h4 class="mb-1 text-success">{{ $progressByMonth->sum('completions') }}</h4>
            <small class="text-muted">Total Completions</small>
          </div>
        </div>
        
        <hr class="my-3">
        
        <div class="d-flex justify-content-between">
          <span class="text-muted">Engagement Level</span>
          @php
            $engagementRate = ($activeStudents / max($totalStudents, 1)) * 100;
            $engagementLevel = $engagementRate >= 70 ? 'High' : ($engagementRate >= 40 ? 'Medium' : 'Low');
            $engagementColor = $engagementRate >= 70 ? 'success' : ($engagementRate >= 40 ? 'warning' : 'danger');
          @endphp
          <span class="badge bg-label-{{ $engagementColor }}">{{ $engagementLevel }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="mt-3">
  <a href="{{ route('student-progress.index') }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>Back to Student Progress
  </a>
</div>
@endsection
