@php($title = 'Reset Password')
@extends('layouts.auth')
@section('content')
<div class="text-center mb-4">
  <h4 class="mb-1">Reset your password</h4>
  <p class="text-muted">Enter your new password below</p>
</div>
<form method="POST" action="{{ route('password.store') }}" novalidate>
  @csrf
  <input type="hidden" name="token" value="{{ request('token') }}">
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', request('email')) }}" required autofocus autocomplete="username">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
  </div>
  <div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary">Reset Password</button>
  </div>
</form>
@endsection
