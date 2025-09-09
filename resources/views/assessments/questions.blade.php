@extends('layouts.dash')

@section('title', 'Assessment Questions')

@section('content')
<div class="card">
  <div class="card-header d-md-flex align-items-center justify-content-between">
    <h5 class="mb-2 mb-md-0">Questions for: {{ $assessment->title }}</h5>
    <div class="d-flex gap-2">
      <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-outline-primary">
        <i class="ti ti-arrow-right me-1"></i>Back to Assessment
      </a>
      <a href="{{ route('assessments.index') }}" class="btn btn-outline-secondary">
        <i class="ti ti-list me-1"></i>All Assessments
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($questions->count() > 0)
      <div class="table-responsive">
        <table class="table" id="questions-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Question</th>
              <th>Type</th>
              <th>Options</th>
              <th>Correct Answer</th>
              <th>Points</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($questions as $question)
              <tr>
                <td>{{ $question->id }}</td>
                <td>
                  <div class="fw-medium">{{ $question->question_text }}</div>
                  @if($question->explanation)
                    <small class="text-muted">{{ Str::limit($question->explanation, 100) }}</small>
                  @endif
                </td>
                <td>
                  <span class="badge bg-label-primary">{{ ucfirst($question->type ?? 'multiple_choice') }}</span>
                </td>
                <td>
                  @if($question->options)
                    @php
                      $options = is_string($question->options) ? json_decode($question->options, true) : $question->options;
                    @endphp
                    @if(is_array($options))
                      <div class="small">
                        @foreach($options as $key => $option)
                          <div class="mb-1">
                            <span class="text-muted">{{ $key }}.</span> {{ Str::limit($option, 50) }}
                          </div>
                        @endforeach
                      </div>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  @if($question->correct_answer)
                    <span class="badge bg-label-success">{{ $question->correct_answer }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-label-info">{{ $question->points ?? 1 }} pt</span>
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="#" class="btn btn-outline-primary" title="View">
                      <i class="ti ti-eye"></i>
                    </a>
                    <a href="#" class="btn btn-outline-secondary" title="Edit">
                      <i class="ti ti-edit"></i>
                    </a>
                    <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" title="Delete">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      @if($questions->hasPages())
        <div class="mt-4">
          {{ $questions->links() }}
        </div>
      @endif
    @else
      <div class="text-center py-5">
        <div class="mb-3">
          <i class="ti ti-help-circle ti-lg text-muted"></i>
        </div>
        <h6 class="mb-2">No Questions Found</h6>
        <p class="text-muted mb-4">This assessment doesn't have any questions yet.</p>
        <a href="#" class="btn btn-primary">
          <i class="ti ti-plus me-2"></i>Add First Question
        </a>
      </div>
    @endif
  </div>
</div>

<script>
  window.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && jQuery.fn.DataTable) {
      jQuery('#questions-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        order: [[0, 'asc']] // Sort by question ID ascending
      });
    }
  });
</script>
@endsection
