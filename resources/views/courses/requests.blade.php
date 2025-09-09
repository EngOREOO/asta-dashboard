@php($title = 'Course Requests')
@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header d-md-flex align-items-center justify-content-between">
    <h5 class="mb-2 mb-md-0">Course Requests</h5>
    <div class="d-flex gap-2">
      <a href="{{ route('courses.index') }}" class="btn btn-outline-primary"><i class="ti ti-list me-1"></i>All Courses</a>
      <a href="{{ route('courses.create') }}" class="btn btn-primary"><i class="ti ti-square-plus me-1"></i>Add Course</a>
    </div>
  </div>
  <div class="card-body">
    @if($courseRequests->count() > 0)
    <div class="table-responsive">
      <table class="table" id="course-requests-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Instructor</th>
            <th>Status</th>
            <th>Price</th>
            <th>Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($courseRequests as $course)
            <tr>
              <td>{{ $course->id }}</td>
              <td class="fw-medium">{{ $course->title }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                      {{ Str::of($course->instructor->name ?? 'U')->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                    </span>
                  </div>
                  <div>
                    <span class="fw-medium">{{ optional($course->instructor)->name ?? 'Unknown' }}</span>
                    <br><small class="text-muted">{{ optional($course->instructor)->email ?? '—' }}</small>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge bg-label-{{ $course->status === 'pending' ? 'warning' : ($course->status === 'rejected' ? 'danger' : 'secondary') }} text-capitalize">
                  {{ $course->status ?? 'draft' }}
                </span>
                @if($course->status === 'rejected' && $course->rejection_reason)
                  <i class="ti ti-info-circle text-danger ms-1" data-bs-toggle="tooltip" title="{{ $course->rejection_reason }}"></i>
                @endif
              </td>
              <td>
                <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-4 h-4 inline ml-1">
                {{ number_format($course->price ?? 0, 2) }}
              </td>
              <td>
                <small class="text-muted">{{ $course->created_at ? $course->created_at->format('M j, Y') : '—' }}</small>
                @if($course->updated_at && $course->updated_at != $course->created_at)
                  <br><small class="text-muted">Updated: {{ $course->updated_at->format('M j, Y') }}</small>
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-sm">
                  <a class="btn btn-outline-primary" href="{{ route('courses.show', $course) }}">
                    <i class="ti ti-eye"></i>
                  </a>
                  @can('update', $course)
                  <a class="btn btn-outline-secondary" href="{{ route('courses.edit', $course) }}">
                    <i class="ti ti-edit"></i>
                  </a>
                  @endcan
                  
                  @if(auth()->user()->hasRole('admin') && $course->status === 'pending')
                  <div class="btn-group btn-group-sm">
                    <form action="{{ route('courses.approve', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this course?')">
                      @csrf
                      <button class="btn btn-outline-success" type="submit" title="Approve">
                        <i class="ti ti-check"></i>
                      </button>
                    </form>
                    <button class="btn btn-outline-danger" onclick="showRejectModal({{ $course->id }}, '{{ $course->title }}')" title="Reject">
                      <i class="ti ti-x"></i>
                    </button>
                  </div>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
              </table>
      </div>
      
      <!-- Pagination -->
      @if($courseRequests->hasPages())
        <div class="mt-4">
          {{ $courseRequests->links() }}
        </div>
      @endif
    @else
    <div class="text-center py-5">
      <div class="mb-3">
        <i class="ti ti-book-2 ti-lg text-muted"></i>
      </div>
      <h6 class="mb-2">No Course Requests Found</h6>
      <p class="text-muted mb-4">There are currently no pending, draft, or rejected courses to review.</p>
      <a href="{{ route('courses.create') }}" class="btn btn-primary">
        <i class="ti ti-square-plus me-2"></i>Create New Course
      </a>
    </div>
    @endif
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="rejectForm" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Reject Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to reject the course "<span id="courseTitle"></span>"?</p>
          <div class="mb-3">
            <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required placeholder="Please provide a clear reason for rejecting this course..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Reject Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    // Initialize DataTable
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#course-requests-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[5, 'desc']] // Sort by submitted date descending
      });
    }

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
      new bootstrap.Tooltip(tooltip);
    });
  });

  function showRejectModal(courseId, courseTitle) {
    document.getElementById('courseTitle').textContent = courseTitle;
    document.getElementById('rejectForm').action = `/courses/${courseId}/reject`;
    document.getElementById('rejection_reason').value = '';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
  }
</script>
@endsection
