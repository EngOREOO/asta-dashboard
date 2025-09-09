@extends('layouts.dash')
@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Questions for: {{ $quiz->title }}</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
          <i class="ti ti-square-plus me-1"></i>Add Question
        </button>
      </div>
      <div class="card-body">
        @if($quiz->questions->count() > 0)
          <div class="accordion" id="questionsAccordion">
            @foreach($quiz->questions as $index => $question)
            <div class="accordion-item">
              <h2 class="accordion-header" id="heading{{ $question->id }}">
                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $question->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                  <div class="d-flex align-items-center w-100">
                    <div class="me-2">
                      <span class="badge bg-label-primary">Q{{ $question->order }}</span>
                    </div>
                    <div class="flex-grow-1">
                      {{ Str::limit($question->question, 80) }}
                    </div>
                    <div class="me-2">
                      <span class="badge bg-label-info">{{ $question->points }} pts</span>
                      <span class="badge bg-label-secondary">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                    </div>
                  </div>
                </button>
              </h2>
              <div id="collapse{{ $question->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#questionsAccordion">
                <div class="accordion-body">
                  <div class="question-content">
                    <div class="mb-3">
                      <strong>Question:</strong>
                      <p>{{ $question->question }}</p>
                      
                      @if($question->image)
                      <div class="mb-2">
                        <img src="{{ asset('storage/' . $question->image) }}" alt="Question Image" class="img-fluid rounded" style="max-height: 200px;">
                      </div>
                      @endif
                    </div>
                    
                    <div class="mb-3">
                      <strong>Answers:</strong>
                      @if($question->type === 'multiple_choice')
                        <ul class="list-group list-group-flush">
                          @foreach($question->answers as $answer)
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                              @if($answer->is_correct)
                                <i class="ti ti-check text-success me-2"></i>
                              @else
                                <i class="ti ti-x text-muted me-2"></i>
                              @endif
                              {{ $answer->answer_text }}
                            </div>
                            @if($answer->is_correct)
                              <span class="badge bg-label-success">Correct</span>
                            @endif
                          </li>
                          @endforeach
                        </ul>
                      @elseif($question->type === 'true_false')
                        <p>
                          <span class="badge bg-label-{{ $question->correctAnswers->first()->answer_text === 'true' ? 'success' : 'danger' }}">
                            {{ $question->correctAnswers->first()->answer_text === 'true' ? 'True' : 'False' }}
                          </span>
                        </p>
                      @else
                        <p>{{ $question->correctAnswers->first()->answer_text ?? 'No answer provided' }}</p>
                      @endif
                      
                      @if($question->answers->where('feedback', '!=', null)->count() > 0)
                      <div class="mt-2">
                        <small class="text-muted">
                          <strong>Feedback provided for answers</strong>
                        </small>
                      </div>
                      @endif
                    </div>
                    
                    @if($question->explanation)
                    <div class="mb-3">
                      <strong>Explanation:</strong>
                      <p class="text-muted">{{ $question->explanation }}</p>
                    </div>
                    @endif
                    
                    <div class="d-flex gap-2">
                      <a href="{{ route('quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-edit"></i> Edit
                      </a>
                      <form action="{{ route('quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="ti ti-trash"></i> Delete
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-5">
            <div class="mb-3">
              <i class="ti ti-help-circle ti-lg text-muted"></i>
            </div>
            <h6 class="mb-2">No Questions Added Yet</h6>
            <p class="text-muted mb-4">Start building your quiz by adding questions.</p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
              <i class="ti ti-square-plus me-2"></i>Add First Question
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Quiz Summary</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <div class="d-flex justify-content-between">
            <span>Total Questions:</span>
            <span class="fw-medium">{{ $quiz->questions->count() }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Total Points:</span>
            <span class="fw-medium">{{ $quiz->questions->sum('points') }}</span>
          </div>
        </div>
        
        @if($quiz->questions->count() > 0)
        <div class="mb-3">
          <h6>Question Types:</h6>
          @php
            $types = $quiz->questions->groupBy('type');
          @endphp
          @foreach($types as $type => $questions)
          <div class="d-flex justify-content-between">
            <span>{{ ucfirst(str_replace('_', ' ', $type)) }}:</span>
            <span>{{ $questions->count() }}</span>
          </div>
          @endforeach
        </div>
        @endif
        
        <div class="d-flex gap-2">
          <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-outline-primary btn-sm">
            <i class="ti ti-eye me-1"></i>View Quiz
          </a>
          <a href="{{ route('quizzes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i>Back
          </a>
        </div>
      </div>
    </div>
    
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="mb-0">Quick Actions</h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="ti ti-square-plus me-1"></i>Add Question
          </button>
          @if($quiz->questions->count() > 0)
          <a href="{{ route('quizzes.attempts', $quiz) }}" class="btn btn-outline-success btn-sm">
            <i class="ti ti-users me-1"></i>View Attempts
          </a>
          <a href="{{ route('quizzes.analytics', $quiz) }}" class="btn btn-outline-warning btn-sm">
            <i class="ti ti-chart-bar me-1"></i>Analytics
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('quizzes.questions.store', $quiz) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Question</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="question" class="form-label">Question *</label>
            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="type" class="form-label">Question Type *</label>
              <select class="form-select" id="type" name="type" required onchange="toggleAnswerOptions()">
                <option value="">Select Type</option>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="true_false">True/False</option>
                <option value="short_answer">Short Answer</option>
                <option value="essay">Essay</option>
              </select>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="points" class="form-label">Points *</label>
              <input type="number" class="form-control" id="points" name="points" value="1" min="1" required>
            </div>
          </div>
          
          <div class="mb-3">
            <label for="image" class="form-label">Question Image (Optional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
          </div>
          
          <div class="mb-3">
            <label for="explanation" class="form-label">Explanation (Optional)</label>
            <textarea class="form-control" id="explanation" name="explanation" rows="2"></textarea>
          </div>
          
          <div id="answersContainer">
            <label class="form-label">Answers *</label>
            <div id="multipleChoiceAnswers" style="display: none;">
              <div class="answer-option mb-2">
                <div class="input-group">
                  <div class="input-group-text">
                    <input class="form-check-input" type="checkbox" name="answers[0][is_correct]" value="1">
                  </div>
                  <input type="text" class="form-control" name="answers[0][text]" placeholder="Answer option">
                  <input type="text" class="form-control" name="answers[0][feedback]" placeholder="Feedback (optional)">
                </div>
              </div>
              <div class="answer-option mb-2">
                <div class="input-group">
                  <div class="input-group-text">
                    <input class="form-check-input" type="checkbox" name="answers[1][is_correct]" value="1">
                  </div>
                  <input type="text" class="form-control" name="answers[1][text]" placeholder="Answer option">
                  <input type="text" class="form-control" name="answers[1][feedback]" placeholder="Feedback (optional)">
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAnswerOption()">
                <i class="ti ti-plus"></i> Add Option
              </button>
            </div>
            
            <div id="trueFalseAnswers" style="display: none;">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="answers[0][text]" value="true" id="trueOption">
                <label class="form-check-label" for="trueOption">True</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="answers[0][text]" value="false" id="falseOption">
                <label class="form-check-label" for="falseOption">False</label>
              </div>
              <input type="hidden" name="answers[0][is_correct]" value="1">
            </div>
            
            <div id="shortAnswerField" style="display: none;">
              <input type="text" class="form-control" name="answers[0][text]" placeholder="Correct answer">
              <input type="hidden" name="answers[0][is_correct]" value="1">
            </div>
            
            <div id="essayField" style="display: none;">
              <textarea class="form-control" name="answers[0][text]" rows="3" placeholder="Sample answer or grading rubric"></textarea>
              <input type="hidden" name="answers[0][is_correct]" value="1">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
let answerIndex = 2;

function toggleAnswerOptions() {
  const type = document.getElementById('type').value;
  const containers = ['multipleChoiceAnswers', 'trueFalseAnswers', 'shortAnswerField', 'essayField'];
  
  containers.forEach(id => {
    document.getElementById(id).style.display = 'none';
  });
  
  switch(type) {
    case 'multiple_choice':
      document.getElementById('multipleChoiceAnswers').style.display = 'block';
      break;
    case 'true_false':
      document.getElementById('trueFalseAnswers').style.display = 'block';
      break;
    case 'short_answer':
      document.getElementById('shortAnswerField').style.display = 'block';
      break;
    case 'essay':
      document.getElementById('essayField').style.display = 'block';
      break;
  }
}

function addAnswerOption() {
  const container = document.getElementById('multipleChoiceAnswers');
  const button = container.querySelector('button');
  
  const newOption = document.createElement('div');
  newOption.className = 'answer-option mb-2';
  newOption.innerHTML = `
    <div class="input-group">
      <div class="input-group-text">
        <input class="form-check-input" type="checkbox" name="answers[${answerIndex}][is_correct]" value="1">
      </div>
      <input type="text" class="form-control" name="answers[${answerIndex}][text]" placeholder="Answer option">
      <input type="text" class="form-control" name="answers[${answerIndex}][feedback]" placeholder="Feedback (optional)">
      <button type="button" class="btn btn-outline-danger" onclick="removeAnswerOption(this)">
        <i class="ti ti-trash"></i>
      </button>
    </div>
  `;
  
  container.insertBefore(newOption, button);
  answerIndex++;
}

function removeAnswerOption(button) {
  button.closest('.answer-option').remove();
}

// Handle True/False selection
document.addEventListener('change', function(e) {
  if (e.target.name === 'answers[0][text]' && e.target.type === 'radio') {
    // Set the correct answer for true/false
    const hiddenInput = document.querySelector('input[name="answers[0][is_correct]"]');
    if (hiddenInput) {
      hiddenInput.value = '1';
    }
  }
});
</script>
@endsection
