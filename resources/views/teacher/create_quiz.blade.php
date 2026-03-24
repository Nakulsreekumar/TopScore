@extends('welcome')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold"><i class="fas fa-plus-circle text-success me-2"></i>Create New Quiz</h2>
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('quiz.create') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Quiz Title</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g., Midterm Mathematics Exam">
                        </div>

                        <div class="mb-4">
                            <label for="duration" class="form-label fw-bold">Duration (in minutes)</label>
                            <input type="number" class="form-control form-control-lg" id="duration" name="duration" value="{{ old('duration') }}" required placeholder="e.g., 30" min="1">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label fw-bold">Start Time</label>
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="end_time" class="form-label fw-bold">End Time</label>
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Quiz & Generate Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection