@extends('welcome')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Dashboard</h2>
        <a href="{{ route('quiz.create.form') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-1"></i> Create New Quiz
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">My Quizzes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Quiz Title</th>
                            <th class="text-center py-3">Join Code</th>
                            <th class="text-center py-3">Questions</th>
                            <th class="text-center py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizzes as $quiz)
                            <tr>
                                <td class="px-4 fw-bold text-dark fs-5">{{ $quiz->title }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                        {{ $quiz->unique_code }}
                                    </span>
                                </td>
                                <td class="text-center fs-5">{{ $quiz->questions->count() ?? 0 }}</td>
                                
                                <td class="text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        
                                        <a href="{{ route('teacher.add_questions', $quiz->id) }}" class="btn btn-outline-success btn-sm px-3" title="Add Questions">
                                            <i class="fas fa-plus"></i> Questions
                                        </a>

                                        <a href="{{ route('quiz.results', $quiz->id) }}" class="btn btn-outline-primary btn-sm px-3" title="View Results">
                                            <i class="fas fa-poll"></i> Results
                                        </a>

                                        <button type="button" class="btn btn-outline-warning btn-sm px-3" data-bs-toggle="modal" data-bs-target="#editDurationModal{{ $quiz->id }}" title="Edit Duration">
                                            <i class="fas fa-clock"></i> Edit Time
                                        </button>

                                        <button type="button" class="btn btn-outline-info btn-sm px-3" data-bs-toggle="modal" data-bs-target="#shareModal{{ $quiz->id }}" title="Share Quiz">
                                            <i class="fas fa-share-alt"></i> Share
                                        </button>

                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#usageModal{{ $quiz->id }}" title="View Usage">
                                            <i class="fas fa-users"></i> Usage
                                        </button>

                                        <form action="{{ route('quiz.destroy', $quiz->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quiz and all its data?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3" title="Delete Quiz">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                        
                                    </div>
                                </td>
                            </tr>

                            @includeIf('teacher.partials.share_modal', ['quiz' => $quiz])
                            @includeIf('teacher.partials.usage_modal', ['quiz' => $quiz])

                            <div class="modal fade" id="editDurationModal{{ $quiz->id }}" tabindex="-1" aria-labelledby="editDurationModalLabel{{ $quiz->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('quiz.update_duration', $quiz->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title fw-bold" id="editDurationModalLabel{{ $quiz->id }}">
                                                    <i class="fas fa-clock me-2"></i>Edit Quiz Duration
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <p class="text-muted mb-3">Updating time for: <strong class="text-dark">{{ $quiz->title }}</strong></p>
                                                
                                                <div class="mb-3">
                                                    <label for="duration{{ $quiz->id }}" class="form-label fw-bold text-dark">New Duration (in minutes)</label>
                                                    <input type="number" class="form-control form-control-lg" id="duration{{ $quiz->id }}" name="duration" value="{{ $quiz->duration_minutes }}" required min="1">
                                                    <small class="text-muted mt-1 d-block">Current duration is {{ $quiz->duration_minutes }} minutes.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning fw-bold shadow-sm">
                                                    <i class="fas fa-save me-1"></i> Update Time
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p class="fs-5">No quizzes created yet. Start by clicking "Create New Quiz"!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection