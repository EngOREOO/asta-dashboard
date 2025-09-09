@php($title = 'Review Analytics')
@extends('layouts.dash')
@section('content')
<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Review Analytics</h5>
        <a href="{{ route('analytics.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Back to Overview
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Overview Statistics -->
<div class="row g-6 mb-6">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-3">
          <span class="avatar-initial rounded bg-label-success">
            <i class="ti ti-star ti-lg"></i>
          </span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($averageRating, 1) }}/5.0</h3>
        <p class="text-muted mb-0">Average Rating</p>
      </div>
    </div>
  </div>
  
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-3">
          <span class="avatar-initial rounded bg-label-info">
            <i class="ti ti-message ti-lg"></i>
          </span>
        </div>
        <h3 class="card-title mb-1">{{ number_format($totalReviews) }}</h3>
        <p class="text-muted mb-0">Total Reviews</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-6">
  <!-- Reviews by Month -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Reviews & Ratings Over Time</h5>
      </div>
      <div class="card-body">
        @if($reviewsByMonth->count() > 0)
        <canvas id="reviewsByMonthChart" height="300"></canvas>
        @else
        <div class="text-center py-4">
          <i class="ti ti-chart-line ti-lg text-muted mb-2"></i>
          <p class="text-muted">No monthly review data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Rating Distribution -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Rating Breakdown</h5>
      </div>
      <div class="card-body">
        @for($i = 5; $i >= 1; $i--)
        @php($ratingCount = \App\Models\Review::where('rating', $i)->count())
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center">
            @for($j = 1; $j <= $i; $j++)
              <i class="ti ti-star-filled text-warning me-1"></i>
            @endfor
            <span class="ms-2">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</span>
          </div>
          <span class="badge bg-label-primary">{{ $ratingCount }}</span>
        </div>
        @endfor
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mt-4">
  <!-- Top Rated Courses -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Top Rated Courses</h5>
      </div>
      <div class="card-body">
        @if($topRatedCourses->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Course</th>
                <th>Instructor</th>
                <th>Average Rating</th>
                <th>Review Count</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topRatedCourses as $course)
              <tr>
                <td>
                  <div>
                    <span class="fw-medium">{{ $course->title }}</span>
                    <br><small class="text-muted">{{ $course->category ?? '—' }}</small>
                  </div>
                </td>
                <td>{{ optional($course->instructor)->name ?? '—' }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    @for($i = 1; $i <= 5; $i++)
                      <i class="ti ti-star{{ $i <= round($course->avg_rating) ? '-filled text-warning' : ' text-muted' }} ti-sm"></i>
                    @endfor
                    <span class="ms-2">{{ number_format($course->avg_rating, 1) }}</span>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-info">{{ $course->review_count }}</span>
                </td>
                <td>
                  <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye"></i>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-award ti-lg text-muted mb-2"></i>
          <p class="text-muted">No course rating data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  @if($reviewsByMonth->count() > 0)
  // Reviews by Month Chart
  const reviewsByMonthCtx = document.getElementById('reviewsByMonthChart').getContext('2d');
  const reviewsByMonthChart = new Chart(reviewsByMonthCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($reviewsByMonth->pluck('month')) !!},
      datasets: [
        {
          label: 'Review Count',
          data: {!! json_encode($reviewsByMonth->pluck('count')) !!},
          borderColor: 'rgb(115, 103, 240)',
          backgroundColor: 'rgba(115, 103, 240, 0.1)',
          tension: 0.4,
          yAxisID: 'y'
        },
        {
          label: 'Average Rating',
          data: {!! json_encode($reviewsByMonth->pluck('avg_rating')) !!},
          borderColor: 'rgb(255, 159, 67)',
          backgroundColor: 'rgba(255, 159, 67, 0.1)',
          tension: 0.4,
          yAxisID: 'y1'
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          beginAtZero: true,
          title: {
            display: true,
            text: 'Review Count'
          }
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          min: 0,
          max: 5,
          title: {
            display: true,
            text: 'Average Rating'
          },
          grid: {
            drawOnChartArea: false,
          },
        }
      }
    }
  });
  @endif
</script>
@endsection
