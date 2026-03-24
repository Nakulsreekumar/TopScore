@extends('welcome')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-graduate text-primary me-2"></i> Student Dashboard</h2>
        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-sign-in-alt me-1"></i> Join a Quiz</h5>
                </div>
                <div class="card-body text-center p-4">
                    <p class="text-muted">Enter the 6-character code provided by your teacher.</p>
                    <form action="{{ route('quiz.join') }}" method="POST">
                        @csrf
                        <input type="text" name="code" class="form-control form-control-lg text-center mb-3 text-uppercase" placeholder="e.g. A1B2C3" required maxlength="6">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Enter Waiting Room</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow border-0 h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history me-1"></i> My Completed Quizzes</h5>
                </div>
                <div class="card-body p-0">
                    @if($results->isEmpty())
                        <div class="p-5 text-center text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 text-light"></i>
                            <h5>No quizzes taken yet</h5>
                            <p>Join a quiz using the form on the left to get started!</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Quiz Name</th>
                                        <th class="text-center">Score</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-end pe-4">Date Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                    <tr>
                                        <td class="align-middle fw-bold text-primary">
                                            {{ $result->quiz->title ?? 'Unknown Quiz' }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-secondary fs-6">{{ $result->score }} / {{ $result->total_marks }}</span>
                                        </td>
                                        <td class="align-middle text-center fw-bold">{{ $result->grade }}</td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('student.quiz_review', $result->quiz_id) }}" class="btn btn-sm btn-outline-info shadow-sm">
                                                <i class="fas fa-eye me-1"></i> Review
                                            </a>
                                        </td>
                                        <td class="align-middle text-end pe-4 text-muted small">
                                            {{ $result->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection