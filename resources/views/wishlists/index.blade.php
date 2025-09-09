@php($title = 'Wishlists')
@extends('layouts.dash')
@section('content')
<div class="card">
  <div class="card-header d-md-flex align-items-center justify-content-between">
    <h5 class="mb-2 mb-md-0">Wishlist Management</h5>
    <div class="d-flex gap-2">
      <a href="{{ route('wishlists.analytics') }}" class="btn btn-info"><i class="ti ti-chart-bar me-1"></i>Analytics</a>
    </div>
  </div>
  <div class="card-body">
    @if($wishlists->count() > 0)
    <div class="table-responsive">
      <table class="table" id="wishlists-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Course</th>
            <th>Instructor</th>
            <th>Price</th>
            <th>Added Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($wishlists as $wishlist)
          <tr>
            <td>{{ $wishlist->id }}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-2">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ Str::of($wishlist->user_name)->split('/\s+/')->map(fn($s)=>Str::substr($s,0,1))->implode('') }}
                  </span>
                </div>
                <div>
                  <span class="fw-medium">{{ $wishlist->user_name }}</span>
                  <br><small class="text-muted">{{ $wishlist->user_email }}</small>
                </div>
              </div>
            </td>
            <td>
              <div>
                <span class="fw-medium">{{ $wishlist->course_title }}</span>
                @if($wishlist->course_category)
                <br><small class="text-muted">{{ $wishlist->course_category }}</small>
                @endif
              </div>
            </td>
            <td>{{ $wishlist->instructor_name ?? 'No Instructor' }}</td>
            <td>
              @if($wishlist->course_price)
              <span class="fw-medium text-success">
                <img src="{{ asset('riyal.svg') }}" alt="ريال" class="w-3 h-3 inline ml-1">
                {{ number_format($wishlist->course_price, 2) }}
              </span>
              @else
                <span class="badge bg-label-success">Free</span>
              @endif
            </td>
            <td>
              <small class="text-muted">{{ \Carbon\Carbon::parse($wishlist->created_at)->format('M j, Y') }}</small>
            </td>
            <td>
              @if($wishlist->is_enrolled)
                <span class="badge bg-label-success">Enrolled</span>
              @else
                <span class="badge bg-label-warning">Pending</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
              </table>
      </div>
      
      <!-- Pagination -->
      @if($wishlists->hasPages())
        <div class="mt-4">
          {{ $wishlists->links() }}
        </div>
      @endif
    @else
    <div class="text-center py-5">
      <div class="mb-3">
        <i class="ti ti-heart ti-lg text-muted"></i>
      </div>
      <h6 class="mb-2">No Wishlist Items Found</h6>
      <p class="text-muted mb-4">Students haven't added any courses to their wishlists yet.</p>
    </div>
    @endif
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#wishlists-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'desc']]
      });
    }
  });
</script>
@endsection
