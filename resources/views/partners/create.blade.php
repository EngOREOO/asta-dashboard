@php($title = 'Add Partner')
@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Add New Partner</h5>
    <a href="{{ route('partners.index') }}" class="btn btn-outline-secondary">
      <i class="ti ti-arrow-left me-1"></i>Back to Partners
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('partners.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-md-8">
          <div class="mb-3">
            <label for="name" class="form-label">Partner Name *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="website" class="form-label">Website URL</label>
            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                   id="website" name="website" value="{{ old('website') }}" 
                   placeholder="https://example.com">
            @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="sort_order" class="form-label">Sort Order</label>
                <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                @error('sort_order')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <div class="form-check form-switch mt-4">
                  <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                         {{ old('is_active', true) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">Active</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-3">
            <label for="image" class="form-label">Partner Logo</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                   id="image" name="image" accept="image/*">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</small>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('partners.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-device-floppy me-1"></i>Save Partner
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
