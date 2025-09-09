@php($title = 'Wishlist Analytics')
@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-heart ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($totalWishlists) }}</h3>
        <p class="text-muted mb-0">Total Wishlists</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-success"><i class="ti ti-trending-up ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ $conversionRate }}%</h3>
        <p class="text-muted mb-0">Conversion Rate</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-info"><i class="ti ti-book-2 ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ $popularCourses->count() }}</h3>
        <p class="text-muted mb-0">Popular Courses</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-users ti-lg"></i></span>
        </div>
        <h3 class="card-title mb-1">{{ $topWishlisters->count() }}</h3>
        <p class="text-muted mb-0">Active Wishlisters</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Most Wishlisted Courses</h5>
      </div>
      <div class="card-body">
        @if($popularCourses->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Course</th>
                <th>Instructor</th>
                <th>Price</th>
                <th>Wishlist Count</th>
              </tr>
            </thead>
            <tbody>
              @foreach($popularCourses as $course)
              <tr>
                <td>
                  <span class="fw-medium">{{ $course->title }}</span>
                </td>
                <td>{{ $course->instructor_name ?? 'No Instructor' }}</td>
                <td>
                  @if($course->price)
                    <span class="fw-medium text-success">
                      <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-3 h-3 inline ml-1">
                      {{ number_format($course->price, 2) }}
                    </span>
                  @else
                    <span class="badge bg-label-success">Free</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-label-primary">{{ $course->wishlist_count }}</span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-heart ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">No wishlist data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Top Wishlisters</h5>
      </div>
      <div class="card-body">
        @if($topWishlisters->count() > 0)
          @foreach($topWishlisters as $user)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              <span class="avatar-initial rounded-circle bg-label-primary">
                {{ Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
              </span>
            </div>
            <div class="flex-grow-1">
              <span class="fw-medium">{{ $user->name }}</span>
              <br><small class="text-muted">{{ $user->email }}</small>
            </div>
            <div>
              <span class="badge bg-label-info">{{ $user->wishlist_count }}</span>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-users ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No wishlist users</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@if($wishlistsByMonth->count() > 0)
<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Wishlist Trends (Last 12 Months)</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Month</th>
                <th>Wishlist Additions</th>
                <th>Trend</th>
              </tr>
            </thead>
            <tbody>
              @foreach($wishlistsByMonth as $month)
              <tr>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}</td>
                <td>
                  <span class="badge bg-label-primary">{{ $month->count }}</span>
                </td>
                <td>
                  <div class="progress" style="height: 6px; width: 100px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ ($month->count / $wishlistsByMonth->max('count')) * 100 }}%"></div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

<div class="mt-3">
  <a href="{{ route('wishlists.index') }}" class="btn btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i>Back to Wishlists
  </a>
</div>
@endsection
