@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-help-circle ti-lg"></i></span>
        </div>
        @php
          $totalQuizzes = \App\Models\Quiz::count();
        @endphp
        <h3 class="card-title mb-1">{{ number_format($totalQuizzes) }}</h3>
        <p class="text-muted mb-0">Total Quizzes</p>
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
          $totalAttempts = \App\Models\QuizAttempt::count();
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
          $averageScore = \App\Models\QuizAttempt::avg('score') ?? 0;
        @endphp
        <h3 class="card-title mb-1">{{ number_format($averageScore, 1) }}%</h3>
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
          $passRate = \App\Models\QuizAttempt::where('is_passed', true)->count() / max(\App\Models\QuizAttempt::count(), 1) * 100;
        @endphp
        <h3 class="card-title mb-1">{{ number_format($passRate, 1) }}%</h3>
        <p class="text-muted mb-0">Pass Rate</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Quiz Performance</h5>
      </div>
      <div class="card-body">
        @php
          $topPerformingQuizzes = \App\Models\QuizAttempt::select(
              'quiz_attempts.quiz_id',
              'quizzes.title as quiz_title',
              'courses.title as course_title',
              \DB::raw('COUNT(quiz_attempts.id) as attempts_count'),
              \DB::raw('AVG(quiz_attempts.score) as avg_score'),
              \DB::raw('SUM(CASE WHEN quiz_attempts.is_passed = 1 THEN 1 ELSE 0 END) as passed_attempts')
            )
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->leftJoin('courses', 'quizzes.course_id', '=', 'courses.id')
            ->groupBy('quiz_attempts.quiz_id', 'quizzes.title', 'courses.title')
            ->orderBy('attempts_count', 'desc')
            ->limit(10)
            ->get();
        @endphp
        
        @if($topPerformingQuizzes->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Quiz</th>
                <th>Course</th>
                <th>Attempts</th>
                <th>Average Score</th>
                <th>Pass Rate</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topPerformingQuizzes as $quiz)
              <tr>
                <td>
                  <span class="fw-medium">{{ $quiz->quiz_title }}</span>
                </td>
                <td>{{ $quiz->course_title ?? 'No Course' }}</td>
                <td>
                  <span class="badge bg-label-primary">{{ $quiz->attempts_count }}</span>
                </td>
                <td>
                  <span class="fw-medium">{{ number_format($quiz->avg_score ?? 0, 1) }}%</span>
                </td>
                <td>
                  @php
                    $passRate = $quiz->attempts_count > 0 ? ($quiz->passed_attempts / $quiz->attempts_count) * 100 : 0;
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
          <i class="ti ti-help-circle ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">No quiz data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Recent Activity</h5>
      </div>
      <div class="card-body">
        @php
          $recentAttempts = \App\Models\QuizAttempt::select(
              'quiz_attempts.*',
              'quizzes.title as quiz_title',
              'users.name as user_name'
            )
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->orderBy('quiz_attempts.created_at', 'desc')
            ->limit(10)
            ->get();
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
              <br><small class="text-muted">{{ $attempt->quiz_title }}</small>
              <br><small class="text-muted">Score: {{ number_format($attempt->score ?? 0, 1) }}%</small>
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
@endsection
