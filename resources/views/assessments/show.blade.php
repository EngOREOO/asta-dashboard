@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $assessment->title }}</h5>
        @if($assessment->is_active)
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
            {{ optional($assessment->course)->title ?? 'No Course' }}
            @if($assessment->course && $assessment->course->instructor)
              <br><small class="text-muted">Instructor: {{ $assessment->course->instructor->name }}</small>
            @endif
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Type:</strong>
          </div>
          <div class="col-sm-9">
            @php
              $typeColors = [
                'quiz' => 'primary',
                'exam' => 'danger',
                'assignment' => 'success',
                'survey' => 'info'
              ];
              $typeIcons = [
                'quiz' => 'ti-help-circle',
                'exam' => 'ti-certificate',
                'assignment' => 'ti-clipboard',
                'survey' => 'ti-forms'
              ];
            @endphp
            <span class="badge bg-label-{{ $typeColors[$assessment->type] ?? 'secondary' }}">
              <i class="ti {{ $typeIcons[$assessment->type] ?? 'ti-file' }} me-1"></i>
              {{ ucfirst($assessment->type) }}
            </span>
          </div>
        </div>
        
        @if($assessment->description)
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Description:</strong>
          </div>
          <div class="col-sm-9">
            {{ $assessment->description }}
          </div>
        </div>
        @endif
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Duration:</strong>
          </div>
          <div class="col-sm-9">
            @if($assessment->duration_minutes)
              {{ $assessment->duration_minutes }} minutes
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
            @if($assessment->max_attempts)
              {{ $assessment->max_attempts }}
            @else
              Unlimited
            @endif
          </div>
        </div>
        
        @if($assessment->passing_score)
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Passing Score:</strong>
          </div>
          <div class="col-sm-9">
            {{ $assessment->passing_score }}%
          </div>
        </div>
        @endif
        
        <div class="row mb-3">
          <div class="col-sm-3">
            <strong>Settings:</strong>
          </div>
          <div class="col-sm-9">
            @if($assessment->randomize_questions)
              <span class="badge bg-label-info me-1">Randomized Questions</span>
            @endif
            @if($assessment->show_results_immediately)
              <span class="badge bg-label-success me-1">Immediate Results</span>
            @endif
          </div>
        </div>
        
        <div class="d-flex gap-2">
          <a href="{{ route('assessments.questions', $assessment) }}" class="btn btn-primary">
            <i class="ti ti-list me-1"></i>Manage Questions
          </a>
          <a href="{{ route('assessments.attempts', $assessment) }}" class="btn btn-info">
            <i class="ti ti-users me-1"></i>View Attempts
          </a>
          <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-outline-secondary">
            <i class="ti ti-edit me-1"></i>Edit
          </a>
          <a href="{{ route('assessments.index') }}" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i>Back to List
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Statistics</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="border-end">
              <h4 class="mb-1 text-primary">{{ $assessment->questions->count() }}</h4>
              <small class="text-muted">Questions</small>
            </div>
          </div>
          <div class="col-6">
            <h4 class="mb-1 text-success">{{ $assessment->attempts->count() }}</h4>
            <small class="text-muted">Attempts</small>
          </div>
        </div>
      </div>
    </div>
    
    @if($assessment->attempts->count() > 0)
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Recent Attempts</h5>
      </div>
      <div class="card-body">
        @foreach($assessment->attempts->take(5) as $attempt)
        <div class="d-flex align-items-center mb-2">
          <div class="avatar avatar-sm me-2">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ Str::of($attempt->user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
            </span>
          </div>
          <div class="flex-grow-1">
            <small class="fw-medium">{{ $attempt->user->name }}</small>
            <br><small class="text-muted">
              Score: {{ $attempt->score ?? 'N/A' }}
              @if($attempt->completed_at)
                | {{ $attempt->completed_at->format('M j, Y') }}
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
