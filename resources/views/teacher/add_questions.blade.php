@extends('welcome')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit text-primary"></i> Edit Quiz: {{ $quiz->title }}</h2>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add a New Question</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('questions.store', $quiz->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Question Text</label>
                            <textarea name="question_text" class="form-control" rows="2" required placeholder="e.g. What is the capital of France?"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Question Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="mcq">Multiple Choice</option>
                                    <option value="true_false">True / False</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Marks</label>
                                <input type="number" name="marks" class="form-control" value="1" min="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Options (Comma Separated)</label>
                            <input type="text" name="options" class="form-control" placeholder="e.g. Paris,London,Berlin,Madrid" required>
                            <small class="text-muted">For True/False, just type: True,False</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correct Answer</label>
                            <input type="text" name="correct_answer" class="form-control" placeholder="e.g. Paris" required>
                            <small class="text-muted">Must match one of the options exactly.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-circle me-1"></i> Add Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📂 Bulk Import (CSV)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('questions.upload', $quiz->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload CSV File</label>
                            <input type="file" name="csv_file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Upload Questions</button>
                    </form>
                    <div class="mt-3">
                        <small class="text-muted d-block fw-bold">CSV Format Example (Notice the Marks at the end):</small>
                        <code class="d-block bg-light p-2 rounded mt-1 text-muted">
                            Question,mcq,A|B|C,A,5
                        </code>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Existing Questions ({{ $quiz->questions->count() }})</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($quiz->questions as $q)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fw-bold">{{ $q->question_text }}</p>
                                <small class="text-muted">
                                    Answer: <span class="text-success fw-bold">{{ $q->correct_answer }}</span> | 
                                    Marks: <span class="badge bg-secondary">{{ $q->marks }}</span>
                                </small>
                            </div>
                            
                            <button type="button" class="btn btn-sm btn-outline-warning ms-2" data-bs-toggle="modal" data-bs-target="#editQuestionModal{{ $q->id }}" title="Edit Marks">
                                <i class="fas fa-edit"></i>
                            </button>
                        </li>

                        <div class="modal fade" id="editQuestionModal{{ $q->id }}" tabindex="-1" aria-labelledby="editQuestionModalLabel{{ $q->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('question.update', $q->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title fw-bold" id="editQuestionModalLabel{{ $q->id }}">
                                                <i class="fas fa-edit me-2"></i>Edit Question Marks
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <p class="text-muted mb-3"><strong>Question:</strong> {{ $q->question_text }}</p>
                                            
                                            <div class="mb-3">
                                                <label for="marks{{ $q->id }}" class="form-label fw-bold text-dark">Marks for this question</label>
                                                <input type="number" class="form-control form-control-lg" id="marks{{ $q->id }}" name="marks" value="{{ $q->marks }}" required min="1">
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-warning fw-bold shadow-sm">
                                                <i class="fas fa-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <li class="list-group-item text-center text-muted py-4">No questions added yet.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection