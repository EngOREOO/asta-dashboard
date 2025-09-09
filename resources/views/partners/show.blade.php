@php($title = $partner->name)
@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Partner Details</h5>
    <div class="d-flex gap-2">
      <a href="{{ route('partners.edit', $partner) }}" class="btn btn-primary">
        <i class="ti ti-edit me-1"></i>Edit
      </a>
      <a href="{{ route('partners.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-arrow-left me-1"></i>Back
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-8">
        <div class="mb-4">
          <h6 class="text-muted mb-2">Partner Name</h6>
          <h4>{{ $partner->name }}</h4>
        </div>

        @if($partner->description)
        <div class="mb-4">
          <h6 class="text-muted mb-2">Description</h6>
          <p>{{ $partner->description }}</p>
        </div>
        @endif

        @if($partner->website)
        <div class="mb-4">
          <h6 class="text-muted mb-2">Website</h6>
          <a href="{{ $partner->website }}" target="_blank" class="text-primary">
            <i class="ti ti-external-link me-1"></i>{{ $partner->website }}
          </a>
        </div>
        @endif

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <h6 class="text-muted mb-2">Status</h6>
              @if($partner->is_active)
                <span class="badge bg-label-success">Active</span>
              @else
                <span class="badge bg-label-secondary">Inactive</span>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <h6 class="text-muted mb-2">Sort Order</h6>
              <span class="badge bg-label-info">{{ $partner->sort_order ?? 0 }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        @if($partner->image)
        <div class="text-center">
          <h6 class="text-muted mb-3">Logo</h6>
          <div class="card">
            <div class="card-body p-3">
              <img src="{{ asset($partner->image) }}" 
                   alt="{{ $partner->name }}" 
                   class="img-fluid rounded">
            </div>
          </div>
        </div>
        @else
        <div class="text-center">
          <h6 class="text-muted mb-3">Logo</h6>
          <div class="card">
            <div class="card-body p-5 text-center">
              <i class="ti ti-building ti-lg text-muted mb-2"></i>
              <p class="text-muted mb-0">No logo uploaded</p>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>

    <hr>
    
    <div class="row text-muted small">
      <div class="col-md-6">
        <strong>Created:</strong> {{ $partner->created_at ? $partner->created_at->format('M j, Y \a\t H:i') : '—' }}
      </div>
      <div class="col-md-6">
        <strong>Last Updated:</strong> {{ $partner->updated_at ? $partner->updated_at->format('M j, Y \a\t H:i') : '—' }}
      </div>
    </div>
  </div>
</div>
@endsection
