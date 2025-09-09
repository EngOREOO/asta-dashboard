@php($title = 'Forgot Password')
@extends('layouts.auth')
@section('content')
<div class="text-center mb-4">
  <h4 class="mb-1">Forgot your password?</h4>
  <p class="text-muted">Enter your email and we'll send you a reset link.</p>
</div>
@if (session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif
<form method="POST" action="{{ route('password.email') }}" novalidate>
  @csrf
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary">Email Password Reset Link</button>
  </div>
</form>
@endsection
