@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $quiz->title }}</h5>
        @if($quiz->is_active)
          <span class="badge bg-label-success">Active</span>
        @else
          <span class="badge bg-label-secondary">Inactive</span>
        @endif
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Course:</strong>
          </div>
          <div class="col-sm-9">
            {{ $quiz->course->title ?? 'No Course' }}
            @if($quiz->course && $quiz->course->instructor)
              <br><small class="text-muted">Instructor: {{ $quiz->course->instructor->name }}</small>
            @endif
          </div>
        </div>
        
        @if($quiz->description)
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Description:</strong>
          </div>
          <div class="col-sm-9">
            {{ $quiz->description }}
          </div>
        </div>
        @endif
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Duration:</strong>
          </div>
          <div class="col-sm-9">
            @if($quiz->duration_minutes)
              {{ $quiz->duration_minutes }} minutes
            @else
              No time limit
            @endif
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Max Attempts:</strong>
          </div>
          <div class="col-sm-9">
            @if($quiz->max_attempts)
              {{ $quiz->max_attempts }}
            @else
              Unlimited
            @endif
          </div>
        </div>
        
        @if($quiz->passing_score)
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Passing Score:</strong>
          </div>
          <div class="col-sm-9">
            {{ $quiz->passing_score }}%
          </div>
        </div>
        @endif
        
        @if($quiz->available_from || $quiz->available_until)
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Availability:</strong>
          </div>
          <div class="col-sm-9">
            @if($quiz->available_from)
              From: {{ $quiz->available_from->format('M j, Y H:i') }}<br>
            @endif
            @if($quiz->available_until)
              Until: {{ $quiz->available_until->format('M j, Y H:i') }}
            @endif
          </div>
        </div>
        @endif
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Settings:</strong>
          </div>
          <div class="col-sm-9">
            @if($quiz->randomize_questions)
              <span class="badge bg-label-info me-1">Randomized Questions</span>
            @endif
            @if($quiz->show_results_immediately)
              <span class="badge bg-label-success me-1">Immediate Results</span>
            @endif
            @if($quiz->allow_review)
              <span class="badge bg-label-primary me-1">Allow Review</span>
            @endif
          </div>
        </div>
        
        <div class="d-flex gap-2">
          <a href="{{ route('quizzes.questions', $quiz) }}" class="btn btn-primary">
            <i class="ti ti-list me-1"></i>Manage Questions
          </a>
          <a href="{{ route('quizzes.attempts', $quiz) }}" class="btn btn-info">
            <i class="ti ti-users me-1"></i>View Attempts
          </a>
          <a href="{{ route('quizzes.analytics', $quiz) }}" class="btn btn-success">
            <i class="ti ti-chart-bar me-1"></i>Analytics
          </a>
          <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-outline-secondary">
            <i class="ti ti-edit me-1"></i>Edit
          </a>
          <a href="{{ route('quizzes.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back to List
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Quiz Statistics</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="border-end">
              <h4 class="mb-1 text-primary">{{ $quiz->questions->count() }}</h4>
              <small class="text-muted">Questions</small>
            </div>
          </div>
          <div class="col-6">
            <h4 class="mb-1 text-success">{{ $quiz->attempts->count() }}</h4>
            <small class="text-muted">Attempts</small>
          </div>
        </div>
        
        @if($quiz->questions->count() > 0)
        <hr class="my-3">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Total Points:</span>
          <span class="fw-medium">{{ $quiz->total_points }}</span>
        </div>
        <div class="d-flex justify-content-between">
          <span class="text-muted">Question Types:</span>
          <div>
            @php
              $types = $quiz->questions->groupBy('type');
            @endphp
            @foreach($types as $type => $questions)
              <small class="badge bg-label-secondary me-1">{{ ucfirst(str_replace('_', ' ', $type)) }}: {{ $questions->count() }}</small>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
    
    @if($quiz->attempts->count() > 0)
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Recent Attempts</h5>
      </div>
      <div class="card-body">
        @foreach($quiz->attempts->take(5) as $attempt)
        <div class="d-flex align-items-center mb-2">
          <div class="avatar avatar-sm me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ Str::of($attempt->user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
            </span>
          </div>
          <div class="flex-grow-1">
            <small class="fw-medium">{{ $attempt->user->name }}</small>
            <br><small class="text-muted">
              @if($attempt->completed_at)
                Score: {{ number_format($attempt->score ?? 0, 1) }}%
                @if($attempt->is_passed)
                  <span class="badge bg-label-success">Passed</span>
                @else
                  <span class="badge bg-label-danger">Failed</span>
                @endif
              @else
                <span class="badge bg-label-warning">In Progress</span>
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
@endsection
