@php($title = 'Analytics Overview')
@extends('layouts.dash')
@section('content')
<!-- Statistics Cards -->
<div class="row g-6 mb-6">
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h3 class="card-title mb-2">{{ number_format($totalUsers) }}</h3>
            <p class="text-muted mb-0">Total Users</p>
          </div>
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-lg"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h3 class="card-title mb-2">{{ number_format($totalCourses) }}</h3>
            <p class="text-muted mb-0">Total Courses</p>
          </div>
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success"><i class="ti ti-book-2 ti-lg"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h3 class="card-title mb-2">{{ number_format($totalReviews) }}</h3>
            <p class="text-muted mb-0">Total Reviews</p>
          </div>
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-star ti-lg"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h3 class="card-title mb-2">{{ number_format($totalCertificates) }}</h3>
            <p class="text-muted mb-0">Certificates Issued</p>
          </div>
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-certificate ti-lg"></i></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-6">
  <!-- User Growth Chart -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">User Growth (Last 12 Months)</h5>
      </div>
      <div class="card-body">
        <canvas id="userGrowthChart" height="300"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Rating Distribution -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Rating Distribution</h5>
      </div>
      <div class="card-body">
        @foreach($ratingDistribution as $rating)
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center">
            @for($i = 1; $i <= $rating->rating; $i++)
              <i class="ti ti-star-filled text-warning me-1"></i>
            @endfor
            <span class="ms-2">{{ $rating->rating }} Star{{ $rating->rating > 1 ? 's' : '' }}</span>
          </div>
          <span class="badge bg-label-primary">{{ $rating->count }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mt-4">
  <!-- Recent Users -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Users</h5>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body">
        @foreach($recentUsers as $user)
        <div class="d-flex align-items-center mb-3">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ Str::of($user->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
            </span>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">{{ $user->name }}</h6>
            <small class="text-muted">{{ $user->email }}</small>
          </div>
          <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  
  <!-- Recent Courses -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Courses</h5>
        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body">
        @foreach($recentCourses as $course)
        <div class="d-flex align-items-center mb-3">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded-circle bg-label-success">
              <i class="ti ti-book-2"></i>
            </span>
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-0">{{ Str::limit($course->title, 30) }}</h6>
            <small class="text-muted">by {{ optional($course->instructor)->name ?? 'Unknown' }}</small>
          </div>
          <span class="badge bg-label-{{ $course->status === 'approved' ? 'success' : ($course->status === 'pending' ? 'warning' : 'secondary') }}">
            {{ ucfirst($course->status ?? 'draft') }}
          </span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  
  <!-- Recent Reviews -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Reviews</h5>
        <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="card-body">
        @foreach($recentReviews as $review)
        <div class="d-flex align-items-start mb-3">
          <div class="avatar avatar-sm me-3">
            <span class="avatar-initial rounded-circle bg-label-info">
              {{ Str::of($review->user->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
            </span>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center mb-1">
              @for($i = 1; $i <= 5; $i++)
                <i class="ti ti-star{{ $i <= $review->rating ? '-filled text-warning' : ' text-muted' }} ti-sm"></i>
              @endfor
              <span class="ms-2 small">{{ $review->rating }}/5</span>
            </div>
            <p class="mb-1 small">{{ Str::limit($review->message ?? 'No comment', 50) }}</p>
            <small class="text-muted">{{ optional($review->course)->title ?? 'Unknown Course' }}</small>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // User Growth Chart
  const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
  const userGrowthChart = new Chart(userGrowthCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($userGrowth->pluck('month')) !!},
      datasets: [{
        label: 'New Users',
        data: {!! json_encode($userGrowth->pluck('count')) !!},
        borderColor: 'rgb(115, 103, 240)',
        backgroundColor: 'rgba(115, 103, 240, 0.1)',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endsection
