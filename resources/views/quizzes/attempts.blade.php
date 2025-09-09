@extends('layouts.dash')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-1">Quiz Attempts</h4>
    <p class="text-muted mb-0">{{ $quiz->title ?? 'Quiz' }} - All Attempts</p>
  </div>
  <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>Back to Quiz
  </a>
</div>

<div class="card">
  <div class="card-header">
    <h5 class="mb-0">Attempts List</h5>
  </div>
  <div class="card-body">
    @if($attempts->count() > 0)
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Student</th>
            <th>Attempt #</th>
            <th>Score</th>
            <th>Status</th>
            <th>Duration</th>
            <th>Started At</th>
            <th>Completed At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($attempts as $attempt)
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ Str::of($attempt->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                  </span>
                </div>
                <span class="fw-medium">{{ $attempt->user->name ?? 'Unknown User' }}</span>
              </div>
            </td>
            <td>
              <span class="badge bg-label-info">#{{ $attempt->attempt_number }}</span>
            </td>
            <td>
              <span class="fw-medium">{{ number_format($attempt->score ?? 0, 1) }}%</span>
              <br><small class="text-muted">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</small>
            </td>
            <td>
              @if($attempt->is_passed)
                <span class="badge bg-label-success">Passed</span>
              @elseif($attempt->completed_at)
                <span class="badge bg-label-danger">Failed</span>
              @else
                <span class="badge bg-label-warning">In Progress</span>
              @endif
            </td>
            <td>
              @if($attempt->time_taken_minutes)
                {{ $attempt->time_taken_minutes }} min
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              <span class="text-nowrap">{{ $attempt->started_at ? $attempt->started_at->format('M j, Y H:i') : '—' }}</span>
            </td>
            <td>
              <span class="text-nowrap">{{ $attempt->completed_at ? $attempt->completed_at->format('M j, Y H:i') : '—' }}</span>
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('quizzes.show', [$quiz, 'attempt' => $attempt->id]) }}">
                    <i class="ti ti-eye me-1"></i>View Details
                  </a>
                  @if($attempt->completed_at)
                  <a class="dropdown-item" href="{{ route('quizzes.show', [$quiz, 'attempt' => $attempt->id, 'review' => true]) }}">
                    <i class="ti ti-file-text me-1"></i>Review Answers
                  </a>
                  @endif
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    
    @if($attempts->hasPages())
    <div class="mt-3">
      {{ $attempts->links() }}
    </div>
    @endif
    @else
    <div class="text-center py-5">
      <i class="ti ti-clipboard-list ti-lg text-muted mb-2"></i>
      <h6 class="text-muted">No attempts yet</h6>
      <p class="text-muted mb-0">No students have attempted this quiz.</p>
    </div>
    @endif
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Attempt Statistics</h5>
      </div>
      <div class="card-body">
        @php
          $stats = [
            'total_attempts' => $attempts->total(),
            'unique_students' => $attempts->groupBy('user_id')->count(),
            'average_score' => $attempts->avg('score') ?? 0,
            'pass_rate' => $attempts->count() > 0 ? ($attempts->where('is_passed', true)->count() / $attempts->count()) * 100 : 0,
          ];
        @endphp
        
        <div class="row">
          <div class="col-6">
            <div class="d-flex justify-content-between">
              <span>Total Attempts:</span>
              <span class="fw-medium">{{ $stats['total_attempts'] }}</span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-between">
              <span>Unique Students:</span>
              <span class="fw-medium">{{ $stats['unique_students'] }}</span>
            </div>
          </div>
          <div class="col-6 mt-2">
            <div class="d-flex justify-content-between">
              <span>Average Score:</span>
              <span class="fw-medium">{{ number_format($stats['average_score'], 1) }}%</span>
            </div>
          </div>
          <div class="col-6 mt-2">
            <div class="d-flex justify-content-between">
              <span>Pass Rate:</span>
              <span class="fw-medium">{{ number_format($stats['pass_rate'], 1) }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Score Distribution</h5>
      </div>
      <div class="card-body">
        @php
          $scoreRanges = [
            '90-100%' => $attempts->whereBetween('score', [90, 100])->count(),
            '80-89%' => $attempts->whereBetween('score', [80, 89])->count(),
            '70-79%' => $attempts->whereBetween('score', [70, 79])->count(),
            '60-69%' => $attempts->whereBetween('score', [60, 69])->count(),
            'Below 60%' => $attempts->where('score', '<', 60)->count(),
          ];
        @endphp
        
        @foreach($scoreRanges as $range => $count)
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span>{{ $range }}</span>
          <div class="d-flex align-items-center">
            <div class="progress me-2" style="width: 100px; height: 6px;">
              <div class="progress-bar" style="width: {{ $attempts->count() > 0 ? ($count / $attempts->count()) * 100 : 0 }}%"></div>
            </div>
            <span class="fw-medium">{{ $count }}</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
