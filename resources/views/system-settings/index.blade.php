@php($title = 'System Settings')
@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">System Settings</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('system-settings.update') }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="row">
            <div class="col-md-12 mb-4">
              <h6 class="text-muted">Application Settings</h6>
              <hr>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="app_name" class="form-label">Application Name</label>
              <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                     id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
              @error('app_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="app_url" class="form-label">Application URL</label>
              <input type="url" class="form-control @error('app_url') is-invalid @enderror" 
                     id="app_url" name="app_url" value="{{ old('app_url', $settings['app_url']) }}" required>
              @error('app_url')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="timezone" class="form-label">Timezone</label>
              <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                <option value="UTC" {{ old('timezone', $settings['timezone']) == 'UTC' ? 'selected' : '' }}>UTC</option>
                <option value="America/New_York" {{ old('timezone', $settings['timezone']) == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                <option value="America/Chicago" {{ old('timezone', $settings['timezone']) == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                <option value="America/Denver" {{ old('timezone', $settings['timezone']) == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                <option value="America/Los_Angeles" {{ old('timezone', $settings['timezone']) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                <option value="Asia/Riyadh" {{ old('timezone', $settings['timezone']) == 'Asia/Riyadh' ? 'selected' : '' }}>Riyadh</option>
                <option value="Asia/Dubai" {{ old('timezone', $settings['timezone']) == 'Asia/Dubai' ? 'selected' : '' }}>Dubai</option>
              </select>
              @error('timezone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="locale" class="form-label">Default Language</label>
              <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale" required>
                <option value="en" {{ old('locale', $settings['locale']) == 'en' ? 'selected' : '' }}>English</option>
                <option value="ar" {{ old('locale', $settings['locale']) == 'ar' ? 'selected' : '' }}>Arabic</option>
              </select>
              @error('locale')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12 mb-4 mt-4">
              <h6 class="text-muted">Email Settings</h6>
              <hr>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="mail_driver" class="form-label">Mail Driver</label>
              <select class="form-select @error('mail_driver') is-invalid @enderror" id="mail_driver" name="mail_driver" required>
                <option value="smtp" {{ old('mail_driver', $settings['mail_driver']) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                <option value="mail" {{ old('mail_driver', $settings['mail_driver']) == 'mail' ? 'selected' : '' }}>PHP Mail</option>
                <option value="sendmail" {{ old('mail_driver', $settings['mail_driver']) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
              </select>
              @error('mail_driver')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4 mb-3">
              <label for="mail_host" class="form-label">Mail Host</label>
              <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                     id="mail_host" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}">
              @error('mail_host')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-4 mb-3">
              <label for="mail_port" class="form-label">Mail Port</label>
              <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                     id="mail_port" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}">
              @error('mail_port')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="mail_from_address" class="form-label">From Email Address</label>
              <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" 
                     id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}">
              @error('mail_from_address')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="mail_from_name" class="form-label">From Name</label>
              <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" 
                     id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}">
              @error('mail_from_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12 mb-4 mt-4">
              <h6 class="text-muted">System Preferences</h6>
              <hr>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="registration_enabled" name="registration_enabled" 
                       value="1" {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }}>
                <label class="form-check-label" for="registration_enabled">Enable User Registration</label>
              </div>
            </div>
            
            <div class="col-md-6 mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="email_verification" name="email_verification" 
                       value="1" {{ old('email_verification', $settings['email_verification']) ? 'checked' : '' }}>
                <label class="form-check-label" for="email_verification">Require Email Verification</label>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="max_file_upload" class="form-label">Max File Upload Size</label>
              <input type="text" class="form-control @error('max_file_upload') is-invalid @enderror" 
                     id="max_file_upload" name="max_file_upload" value="{{ old('max_file_upload', $settings['max_file_upload']) }}" required>
              @error('max_file_upload')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="session_lifetime" class="form-label">Session Lifetime (minutes)</label>
              <input type="number" class="form-control @error('session_lifetime') is-invalid @enderror" 
                     id="session_lifetime" name="session_lifetime" value="{{ old('session_lifetime', $settings['session_lifetime']) }}" required>
              @error('session_lifetime')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save Settings</button>
            <button type="reset" class="btn btn-outline-secondary">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">System Actions</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <form action="{{ route('system-settings.clear-cache') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-info w-100">
              <i class="ti ti-refresh me-1"></i>Clear All Caches
            </button>
          </form>
          
          <form action="{{ route('system-settings.export') }}" method="GET" class="d-inline">
            <button type="submit" class="btn btn-outline-success w-100">
              <i class="ti ti-download me-1"></i>Export Settings
            </button>
          </form>
          
          <a href="{{ route('system-settings.info') }}" class="btn btn-outline-secondary">
            <i class="ti ti-info-circle me-1"></i>System Information
          </a>
          
          <hr>
          
          <form action="{{ route('system-settings.maintenance.enable') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Enable maintenance mode?')">
              <i class="ti ti-tool me-1"></i>Enable Maintenance Mode
            </button>
          </form>
          
          <form action="{{ route('system-settings.maintenance.disable') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-success w-100">
              <i class="ti ti-check me-1"></i>Disable Maintenance Mode
            </button>
          </form>
        </div>
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Quick Stats</h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="border-end">
              <h4 class="mb-1 text-primary">{{ \App\Models\User::count() }}</h4>
              <small class="text-muted">Total Users</small>
            </div>
          </div>
          <div class="col-6">
            <h4 class="mb-1 text-success">{{ \App\Models\Course::count() }}</h4>
            <small class="text-muted">Total Courses</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
