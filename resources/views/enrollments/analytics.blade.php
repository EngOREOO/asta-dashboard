@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-lg"></i></span>
        </div>
        @php
          $totalEnrollments = \DB::table('course_user')->count();
        @endphp
        <h3 class="card-title mb-1">{{ number_format($totalEnrollments) }}</h3>
        <p class="text-muted mb-0">Total Enrollments</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-success"><i class="ti ti-user-check ti-lg"></i></span>
        </div>
        @php
          $activeEnrollments = \DB::table('course_user')->whereNull('completed_at')->count();
        @endphp
        <h3 class="card-title mb-1">{{ number_format($activeEnrollments) }}</h3>
        <p class="text-muted mb-0">Active Enrollments</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-info"><i class="ti ti-certificate ti-lg"></i></span>
        </div>
        @php
          $completedEnrollments = \DB::table('course_user')->whereNotNull('completed_at')->count();
        @endphp
        <h3 class="card-title mb-1">{{ number_format($completedEnrollments) }}</h3>
        <p class="text-muted mb-0">Completed Courses</p>
      </div>
    </div>
  </div>
  
  <div class="col-md-3">
    <div class="card">
      <div class="card-body text-center">
        <div class="avatar mx-auto mb-2">
          <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-percentage ti-lg"></i></span>
        </div>
        @php
          $completionRate = $totalEnrollments > 0 ? ($completedEnrollments / $totalEnrollments) * 100 : 0;
        @endphp
        <h3 class="card-title mb-1">{{ number_format($completionRate, 1) }}%</h3>
        <p class="text-muted mb-0">Completion Rate</p>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Popular Courses</h5>
      </div>
      <div class="card-body">
        @php
          $popularCourses = \DB::table('course_user')
            ->select(
              'courses.id',
              'courses.title',
              'users.name as instructor_name',
              \DB::raw('COUNT(course_user.user_id) as enrollment_count'),
              \DB::raw('AVG(course_user.progress) as avg_progress'),
              \DB::raw('COUNT(CASE WHEN course_user.completed_at IS NOT NULL THEN 1 END) as completed_count')
            )
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->leftJoin('users', 'courses.instructor_id', '=', 'users.id')
            ->groupBy('courses.id', 'courses.title', 'users.name')
            ->orderBy('enrollment_count', 'desc')
            ->limit(10)
            ->get();
        @endphp
        
        @if($popularCourses->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Course</th>
                <th>Instructor</th>
                <th>Enrollments</th>
                <th>Avg Progress</th>
                <th>Completed</th>
                <th>Completion Rate</th>
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
                  <span class="badge bg-label-primary">{{ $course->enrollment_count }}</span>
                </td>
                <td>
                  <span class="fw-medium">{{ number_format($course->avg_progress ?? 0, 1) }}%</span>
                </td>
                <td>
                  <span class="badge bg-label-success">{{ $course->completed_count }}</span>
                </td>
                <td>
                  @php
                    $courseCompletionRate = $course->enrollment_count > 0 ? ($course->completed_count / $course->enrollment_count) * 100 : 0;
                  @endphp
                  <span class="badge bg-label-{{ $courseCompletionRate >= 70 ? 'success' : ($courseCompletionRate >= 40 ? 'warning' : 'danger') }}">
                    {{ number_format($courseCompletionRate, 1) }}%
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="text-center py-4">
          <i class="ti ti-users ti-lg text-muted mb-2"></i>
          <p class="text-muted mb-0">No enrollment data available</p>
        </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Enrollment Trends</h5>
      </div>
      <div class="card-body">
        @php
          $enrollmentsByMonth = \DB::table('course_user')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        @endphp
        
        @if($enrollmentsByMonth->count() > 0)
          @foreach($enrollmentsByMonth as $monthly)
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <span class="fw-medium">{{ \Carbon\Carbon::parse($monthly->month . '-01')->format('M Y') }}</span>
            </div>
            <div>
              <span class="badge bg-label-primary">{{ $monthly->count }}</span>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-chart-line ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No trend data</p>
          </div>
        @endif
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Recent Enrollments</h5>
      </div>
      <div class="card-body">
        @php
          $recentEnrollments = \DB::table('course_user')
            ->select(
              'users.name as user_name',
              'courses.title as course_title',
              'course_user.created_at'
            )
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->orderBy('course_user.created_at', 'desc')
            ->limit(10)
            ->get();
        @endphp
        
        @if($recentEnrollments->count() > 0)
          @foreach($recentEnrollments as $enrollment)
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-sm me-2">
              <span class="avatar-initial rounded-circle bg-label-primary">
                {{ Str::of($enrollment->user_name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
              </span>
            </div>
            <div class="flex-grow-1">
              <span class="fw-medium">{{ $enrollment->user_name }}</span>
              <br><small class="text-muted">{{ Str::limit($enrollment->course_title, 30) }}</small>
              <br><small class="text-muted">{{ \Carbon\Carbon::parse($enrollment->created_at)->diffForHumans() }}</small>
            </div>
          </div>
          @endforeach
        @else
          <div class="text-center py-4">
            <i class="ti ti-activity ti-lg text-muted mb-2"></i>
            <p class="text-muted mb-0">No recent activity</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
