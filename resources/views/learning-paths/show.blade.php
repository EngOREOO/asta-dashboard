@php($title = 'Learning Path Details')
@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $learning_path->name }}</h5>
        <div class="d-flex gap-2">
          <a href="{{ route('learning-paths.edit', $learning_path) }}" class="btn btn-outline-primary">Edit</a>
          <a href="{{ route('learning-paths.index') }}" class="btn btn-text-secondary"><i class="ti ti-arrow-left me-2"></i>Back</a>
        </div>
      </div>
      <div class="card-body">
        <p class="text-muted">{{ $learning_path->description }}</p>
        
        <!-- Learning Path Statistics -->
        <div class="row mt-4 mb-4">
          <div class="col-md-3">
            <div class="card bg-light">
              <div class="card-body text-center">
                <h6 class="card-title text-muted">Total Courses</h6>
                <h4 class="mb-0">{{ $learning_path->courses->count() }}</h4>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-light">
              <div class="card-body text-center">
                <h6 class="card-title text-muted">Average Rating</h6>
                <h4 class="mb-0">
                  @if($learning_path->average_rating > 0)
                    <span class="text-warning">
                      <i class="ti ti-star-filled"></i>
                    </span>
                    {{ $learning_path->average_rating }}/5
                  @else
                    <span class="text-muted">No ratings</span>
                  @endif
                </h4>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-light">
              <div class="card-body text-center">
                <h6 class="card-title text-muted">Total Price</h6>
                <h4 class="mb-0">
                  <span class="badge bg-{{ $learning_path->total_price == 0 ? 'success' : 'primary' }} fs-6">
                    {{ $learning_path->formatted_price }}
                  </span>
                </h4>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-light">
              <div class="card-body text-center">
                <h6 class="card-title text-muted">Status</h6>
                <h4 class="mb-0">
                  <span class="badge bg-{{ $learning_path->is_active ? 'success' : 'secondary' }}">
                    {{ $learning_path->is_active ? 'Active' : 'Inactive' }}
                  </span>
                </h4>
              </div>
            </div>
          </div>
        </div>

        <h6 class="mt-4">Courses in this Learning Path</h6>
        <div class="row">
          @forelse($learning_path->courses as $course)
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="card-title mb-0">{{ $course->title }}</h6>
                    <span class="badge bg-secondary">#{{ $loop->iteration }}</span>
                  </div>
                  
                  @if($course->description)
                    <p class="card-text text-muted small">{{ Str::limit($course->description, 100) }}</p>
                  @endif
                  
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="d-flex align-items-center">
                      @if($course->average_rating > 0)
                        <span class="text-warning me-1">
                          <i class="ti ti-star-filled"></i>
                        </span>
                        <small>{{ $course->average_rating }}/5</small>
                      @else
                        <small class="text-muted">No ratings</small>
                      @endif
                    </div>
                    <div>
                      @if($course->price == 0)
                        <span class="badge bg-success">مجاني</span>
                      @else
                        <span class="badge bg-primary">{{ number_format($course->price, 2) }} ر.س</span>
                      @endif
                    </div>
                  </div>
                  
                  @if($course->instructor)
                    <div class="mt-2">
                      <small class="text-muted">
                        <i class="ti ti-user me-1"></i>
                        {{ $course->instructor->name }}
                      </small>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                No courses assigned to this learning path.
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
