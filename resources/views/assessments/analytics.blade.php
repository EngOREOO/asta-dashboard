@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clipboard-list ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($totalAssessments) }}</h3>
        <p class="text-muted mb-0">Total Assessments</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-success"><i class="ti ti-users ti-lg"></i></span>
        </div>
        @php
          $totalAttempts = \App\Models\AssessmentAttempt::count();
        @endphp
        <h3 class="card-title mb-1">{{ number_format($totalAttempts) }}</h3>
        <p class="text-muted mb-0">Total Attempts</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-info"><i class="ti ti-percentage ti-lg"></i></span>
        </div>
        @php
          $averageScore = \App\Models\AssessmentAttempt::avg('score');
        @endphp
        <h3 class="card-title mb-1">{{ $averageScore }}%</h3>
        <p class="text-muted mb-0">Average Score</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-check ti-lg"></i></span>
        </div>
        @php
          $passRate = \App\Models\AssessmentAttempt::avg('score');
        @endphp
        <h3 class="card-title mb-1">{{ $passRate }}%</h3>
        <p class="text-muted mb-0">Pass Rate</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Assessment Performance</h5>
      </div>
      <div class="card-body">
        @php
          $topPerformingAssessments = \App\Models\AssessmentAttempt::select(
              'assessment_attempts.assessment_id',
              'assessments.title as assessment_title',
              'assessments.type as assessment_type',
              'courses.title as course_title',
              \DB::raw('COUNT(assessment_attempts.id) as attempts_count'),
              \DB::raw('AVG(assessment_attempts.score) as avg_score'),
              \DB::raw('SUM(CASE WHEN assessment_attempts.status = "passed" THEN 1 ELSE 0 END) as passed_attempts')
            )
            ->join('assessments', 'assessment_attempts.assessment_id', '=', 'assessments.id')
            ->join('courses', 'assessments.course_id', '=', 'courses.id')
            ->groupBy('assessment_attempts.assessment_id', 'assessments.title', 'assessments.type', 'courses.title')
            ->orderBy('attempts_count', 'desc')
            ->limit(10)
            ->get();
        @endphp
        @if($topPerformingAssessments->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Assessment</th>
                <th>Course</th>
                <th>Attempts</th>
                <th>Average Score</th>
                <th>Pass Rate</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topPerformingAssessments as $assessment)
              <tr>
                <td>
                  <span class="fw-medium">{{ $assessment->assessment_title }}</span>
                  <br><small class="text-muted">{{ ucfirst($assessment->assessment_type) }}</small>
                </td>
                <td>{{ $assessment->course_title ?? 'No Course' }}</td>
                <td>
                  <span class="badge bg-label-primary">{{ $assessment->attempts_count }}</span>
                </td>
                <td>
                  <span class="fw-medium">{{ number_format($assessment->avg_score ?? 0, 1) }}%</span>
                </td>
                <td>
                  @php
                    $passRate = $assessment->attempts_count > 0 ? ($assessment->passed_attempts / $assessment->attempts_count) * 100 : 0;
                  @endphp
                  <span class="badge bg-label-{{ $passRate >= 70 ? 'success' : ($passRate >= 50 ? 'warning' : 'danger') }}">
                    {{ number_format($passRate, 1) }}%
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-clipboard-list ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">No assessment data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Assessment Types</h5>
      </div>
      <div class="card-body">
        @if($assessmentsByType->count() > 0)
          @foreach($assessmentsByType as $type)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              @php
                $typeIcons = [
                  'quiz' => 'ti-help-circle',
                  'exam' => 'ti-certificate',
                  'assignment' => 'ti-clipboard',
                  'survey' => 'ti-forms'
                ];
                $typeColors = [
                  'quiz' => 'primary',
                  'exam' => 'danger',
                  'assignment' => 'success',
                  'survey' => 'info'
                ];
              @endphp
              <span class="avatar-initial rounded-circle bg-label-{{ $typeColors[$type->type] ?? 'secondary' }}">
                <i class="ti {{ $typeIcons[$type->type] ?? 'ti-file' }}"></i>
              </span>
            </div>
            <div class="flex-grow-1">
              <span class="fw-medium">{{ ucfirst($type->type) }}</span>
              <br><small class="text-muted">{{ $type->count }} assessments</small>
            </div>
            <div>
              <span class="badge bg-label-info">{{ $type->count }}</span>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-chart-pie ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No type data</p>
          </div>
        @endif
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Recent Activity</h5>
      </div>
      <div class="card-body">
        @php
          $recentAttempts = \App\Models\AssessmentAttempt::orderBy('created_at', 'desc')->limit(10)->get();
        @endphp
        @if($recentAttempts->count() > 0)
          @foreach($recentAttempts as $attempt)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              <span class="avatar-initial rounded-circle bg-label-primary">
                {{ Str::of($attempt->user_name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
              </span>
            </div>
            <div class="flex-grow-1">
              <span class="fw-medium">{{ $attempt->user_name }}</span>
              <br><small class="text-muted">{{ $attempt->assessment_title }}</small>
              <br><small class="text-muted">Score: {{ $attempt->score ?? 'N/A' }}%</small>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-activity ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No recent activity</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="mt-3">
  <a href="{{ route('assessments.index') }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>Back to Assessments
  </a>
</div>
@endsection
