ذ@extends('layouts.dash')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-1">System Information</h4>
    <p class="text-muted mb-0">Application and server information</p>
  </div>
  <a href="{{ route('system-settings.index') }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>Back to Settings
  </a>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Application Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Application Name</label>
              <p class="mb-0 fw-medium">{{ config('app.name') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Environment</label>
              <span class="badge bg-label-{{ config('app.env') === 'production' ? 'success' : (config('app.env') === 'staging' ? 'warning' : 'info') }}">
                {{ ucfirst(config('app.env')) }}
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Debug Mode</label>
              <span class="badge bg-label-{{ config('app.debug') ? 'warning' : 'success' }}">
                {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
              </span>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">URL</label>
              <p class="mb-0 fw-medium">{{ config('app.url') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Timezone</label>
              <p class="mb-0 fw-medium">{{ config('app.timezone') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Locale</label>
              <p class="mb-0 fw-medium">{{ config('app.locale') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Server Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">PHP Version</label>
              <p class="mb-0 fw-medium">{{ PHP_VERSION }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Laravel Version</label>
              <p class="mb-0 fw-medium">{{ app()->version() }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Server Software</label>
              <p class="mb-0 fw-medium">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">HTTP Host</label>
              <p class="mb-0 fw-medium">{{ $_SERVER['HTTP_HOST'] ?? 'Unknown' }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Document Root</label>
              <p class="mb-0 fw-medium small">{{ $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Server IP</label>
              <p class="mb-0 fw-medium">{{ $_SERVER['SERVER_ADDR'] ?? 'Unknown' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Database Information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Driver</label>
              <p class="mb-0 fw-medium">{{ config('database.default') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Host</label>
              <p class="mb-0 fw-medium">{{ config('database.connections.' . config('database.default') . '.host') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Port</label>
              <p class="mb-0 fw-medium">{{ config('database.connections.' . config('database.default') . '.port') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Database</label>
              <p class="mb-0 fw-medium">{{ config('database.connections.' . config('database.default') . '.database') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Cache & Session</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Cache Driver</label>
              <p class="mb-0 fw-medium">{{ config('cache.default') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Session Driver</label>
              <p class="mb-0 fw-medium">{{ config('session.driver') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Queue Driver</label>
              <p class="mb-0 fw-medium">{{ config('queue.default') }}</p>
            </div>
          </div>
          <div class="col-6">
            <div class="mb-3">
              <label class="form-label text-muted">Mail Driver</label>
              <p class="mb-0 fw-medium">{{ config('mail.default') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">PHP Extensions</h5>
      </div>
      <div class="card-body">
        @php
          $extensions = get_loaded_extensions();
          sort($extensions);
          $importantExtensions = ['mysqli', 'pdo', 'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'curl', 'zip', 'gd', 'json', 'bcmath'];
        @endphp
        
        <div class="row">
          @foreach($importantExtensions as $ext)
          <div class="col-md-3 col-sm-6 mb-2">
            <div class="d-flex align-items-center">
              @if(in_array($ext, $extensions))
                <i class="ti ti-check text-success me-2"></i>
                <span class="fw-medium">{{ $ext }}</span>
              @else
                <i class="ti ti-x text-danger me-2"></i>
                <span class="text-danger">{{ $ext }}</span>
              @endif
            </div>
          </div>
          @endforeach
        </div>
        
        <div class="mt-3">
          <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#allExtensions">
            Show All Extensions ({{ count($extensions) }})
          </button>
        </div>
        
        <div class="collapse mt-3" id="allExtensions">
          <div class="row">
            @foreach($extensions as $extension)
            <div class="col-md-3 col-sm-6 mb-1">
              <small class="text-muted">{{ $extension }}</small>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">System Statistics</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="text-center">
              <h6 class="text-muted">Total Users</h6>
              <h4 class="mb-0">{{ \App\Models\User::count() }}</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="text-center">
              <h6 class="text-muted">Total Courses</h6>
              <h4 class="mb-0">{{ \App\Models\Course::count() }}</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="text-center">
              <h6 class="text-muted">Total Enrollments</h6>
              <h4 class="mb-0">{{ \DB::table('course_user')->count() }}</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="text-center">
              <h6 class="text-muted">Storage Used</h6>
              @php
                $storageSize = 0;
                if (function_exists('disk_free_space')) {
                  $totalSpace = disk_total_space('/');
                  $freeSpace = disk_free_space('/');
                  $usedSpace = $totalSpace - $freeSpace;
                  $storageSize = round($usedSpace / 1024 / 1024 / 1024, 2);
                }
              @endphp
              <h4 class="mb-0">{{ $storageSize ? $storageSize . ' GB' : 'Unknown' }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
