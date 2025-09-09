@php($title = 'User Analytics')
@extends('layouts.dash')
@section('content')
<div class="row g-6 mb-6">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">User Analytics</h5>
        <a href="{{ route('analytics.index') }}" class="btn btn-sm btn-outline-secondary">
          <i class="ti ti-arrow-left me-1"></i>Back to Overview
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row g-6">
  <!-- Users by Role -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Users by Role</h5>
      </div>
      <div class="card-body">
        @if($usersByRole->count() > 0)
        <canvas id="usersByRoleChart" height="300"></canvas>
        @else
        <div class="text-center py-4">
          <i class="ti ti-users ti-lg text-muted mb-2"></i>
          <p class="text-muted">No role data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- User Registrations -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Daily Registrations (Last 30 Days)</h5>
      </div>
      <div class="card-body">
        @if($userRegistrations->count() > 0)
        <canvas id="userRegistrationsChart" height="300"></canvas>
        @else
        <div class="text-center py-4">
          <i class="ti ti-chart-line ti-lg text-muted mb-2"></i>
          <p class="text-muted">No registration data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="row g-6 mt-4">
  <!-- User Statistics Table -->
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">User Role Distribution</h5>
      </div>
      <div class="card-body">
        @if($usersByRole->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Role</th>
                <th>Count</th>
                <th>Percentage</th>
              </tr>
            </thead>
            <tbody>
              @php($total = $usersByRole->sum('count'))
              @foreach($usersByRole as $role)
              <tr>
                <td>
                  <span class="badge bg-label-primary">{{ ucfirst($role->role ?? 'User') }}</span>
                </td>
                <td>{{ number_format($role->count) }}</td>
                <td>
                  @if($total > 0)
                  {{ number_format(($role->count / $total) * 100, 1) }}%
                  @else
                  0%
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-database ti-lg text-muted mb-2"></i>
          <p class="text-muted">No user role data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  @if($usersByRole->count() > 0)
  // Users by Role Chart
  const usersByRoleCtx = document.getElementById('usersByRoleChart').getContext('2d');
  const usersByRoleChart = new Chart(usersByRoleCtx, {
    type: 'doughnut',
    data: {
      labels: {!! json_encode($usersByRole->pluck('role')->map(fn($role) => ucfirst($role ?? 'User'))) !!},
      datasets: [{
        data: {!! json_encode($usersByRole->pluck('count')) !!},
        backgroundColor: [
          'rgba(115, 103, 240, 0.8)',
          'rgba(255, 159, 67, 0.8)',
          'rgba(54, 162, 235, 0.8)',
          'rgba(255, 99, 132, 0.8)',
          'rgba(75, 192, 192, 0.8)'
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

  @if($userRegistrations->count() > 0)
  // User Registrations Chart
  const userRegistrationsCtx = document.getElementById('userRegistrationsChart').getContext('2d');
  const userRegistrationsChart = new Chart(userRegistrationsCtx, {
    type: 'line',
    data: {
      labels: {!! json_encode($userRegistrations->pluck('date')) !!},
      datasets: [{
        label: 'New Registrations',
        data: {!! json_encode($userRegistrations->pluck('count')) !!},
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
  @endif
</script>
@endsection
