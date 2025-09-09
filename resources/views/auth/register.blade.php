@php($title = 'Register')
@extends('layouts.auth')
@section('content')
<div class="text-center mb-4">
  <h4 class="mb-1">Create your account</h4>
  <p class="text-muted">Join us to get started</p>
</div>
<form method="POST" action="{{ route('register') }}" novalidate>
  @csrf
  <div class="d-grid gap-2 mb-3">
    <a class="btn btn-outline-danger" href="{{ route('socialite.redirect', ['provider' => 'google']) }}">Sign up with Google</a>
  </div>
  <div class="text-center my-3 text-muted">or sign up with email</div>
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="username">
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
    <button type="submit" class="btn btn-primary">Register</button>
  </div>
  <div class="text-center mt-3">
    <small>Already registered? <a href="{{ route('login') }}">Log in</a></small>
  </div>
</form>
@endsection
