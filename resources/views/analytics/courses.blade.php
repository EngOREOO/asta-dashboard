@php($title = 'Course Analytics')
@extends('layouts.dash')
@section('content')
<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Course Analytics</h5>
        <a href="{{ route('analytics.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Back to Overview
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row g-6">
  <!-- Courses by Category -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Courses by Category</h5>
      </div>
      <div class="card-body">
        @if($coursesByCategory->count() > 0)
        <canvas id="coursesByCategoryChart" height="300"></canvas>
        @else
        <div class="text-center py-4">
          <i class="ti ti-category-2 ti-lg text-muted mb-2"></i>
          <p class="text-muted">No category data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Courses by Status -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Courses by Status</h5>
      </div>
      <div class="card-body">
        @if($coursesByStatus->count() > 0)
        <canvas id="coursesByStatusChart" height="300"></canvas>
        @else
        <div class="text-center py-4">
          <i class="ti ti-chart-pie ti-lg text-muted mb-2"></i>
          <p class="text-muted">No status data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mt-4">
  <!-- Top Instructors -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Top Instructors by Course Count</h5>
      </div>
      <div class="card-body">
        @if($topInstructors->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Instructor</th>
                <th>Email</th>
                <th>Course Count</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($topInstructors as $instructor)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-3">
                      <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ Str::of($instructor->name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                      </span>
                    </div>
                    <span class="fw-medium">{{ $instructor->name }}</span>
                  </div>
                </td>
                <td>{{ $instructor->email }}</td>
                <td>
                  <span class="badge bg-label-success">{{ $instructor->instructor_courses_count ?? 0 }}</span>
                </td>
                <td>
                  <a href="{{ route('instructors.show', $instructor) }}" class="btn btn-sm btn-outline-primary">
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
          <i class="ti ti-chalkboard-teacher ti-lg text-muted mb-2"></i>
          <p class="text-muted">No instructor data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  @if($coursesByCategory->count() > 0)
  // Courses by Category Chart
  const coursesByCategoryCtx = document.getElementById('coursesByCategoryChart').getContext('2d');
  const coursesByCategoryChart = new Chart(coursesByCategoryCtx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($coursesByCategory->pluck('category')) !!},
      datasets: [{
        label: 'Courses',
        data: {!! json_encode($coursesByCategory->pluck('count')) !!},
        backgroundColor: 'rgba(115, 103, 240, 0.8)'
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
  @endif

  @if($coursesByStatus->count() > 0)
  // Courses by Status Chart
  const coursesByStatusCtx = document.getElementById('coursesByStatusChart').getContext('2d');
  const coursesByStatusChart = new Chart(coursesByStatusCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($coursesByStatus->pluck('status')->map(fn($status) => ucfirst($status ?? 'Draft'))) !!},
      datasets: [{
        data: {!! json_encode($coursesByStatus->pluck('count')) !!},
        backgroundColor: [
          'rgba(40, 199, 111, 0.8)', // approved - green
          'rgba(255, 171, 0, 0.8)',  // pending - yellow
          'rgba(234, 84, 85, 0.8)',  // rejected - red
          'rgba(108, 117, 125, 0.8)' // draft - gray
        ]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
  @endif
</script>
@endsection
