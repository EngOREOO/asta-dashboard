@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header">
    <h5 class="mb-0">Edit Quiz</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('quizzes.update', $quiz) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="course_id" class="form-label">Course *</label>
          <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
            <option value="">Select Course</option>
            @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>
              {{ $course->title }} @if($course->instructor) - by {{ $course->instructor->name }} @endif
            </option>
            @endforeach
          </select>
          @error('course_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="title" class="form-label">Quiz Title *</label>
          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $quiz->title) }}" required>
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $quiz->description) }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="duration_minutes" class="form-label">Duration (minutes)</label>
          <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes) }}" min="1">
          <div class="form-text">Leave empty for no time limit</div>
          @error('duration_minutes')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="max_attempts" class="form-label">Max Attempts</label>
          <input type="number" class="form-control @error('max_attempts') is-invalid @enderror" id="max_attempts" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1">
          <div class="form-text">Leave empty for unlimited attempts</div>
          @error('max_attempts')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label for="passing_score" class="form-label">Passing Score (%)</label>
          <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100" step="0.01">
          @error('passing_score')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="available_from" class="form-label">Available From</label>
          <input type="datetime-local" class="form-control @error('available_from') is-invalid @enderror" id="available_from" name="available_from" value="{{ old('available_from', $quiz->available_from ? $quiz->available_from->format('Y-m-d\TH:i') : '') }}">
          @error('available_from')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="available_until" class="form-label">Available Until</label>
          <input type="datetime-local" class="form-control @error('available_until') is-invalid @enderror" id="available_until" name="available_until" value="{{ old('available_until', $quiz->available_until ? $quiz->available_until->format('Y-m-d\TH:i') : '') }}">
          @error('available_until')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ old('randomize_questions', $quiz->randomize_questions) ? 'checked' : '' }}>
            <label class="form-check-label" for="randomize_questions">Randomize Questions</label>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="show_results_immediately" name="show_results_immediately" value="1" {{ old('show_results_immediately', $quiz->show_results_immediately) ? 'checked' : '' }}>
            <label class="form-check-label" for="show_results_immediately">Show Results Immediately</label>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="allow_review" name="allow_review" value="1" {{ old('allow_review', $quiz->allow_review) ? 'checked' : '' }}>
            <label class="form-check-label" for="allow_review">Allow Review</label>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update Quiz</button>
        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
