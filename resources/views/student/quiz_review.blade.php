@extends('welcome')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-check text-success me-2"></i> Review: {{ $quiz->title }}</h2>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body text-center">
            <h4 class="text-muted mb-3">Your Final Result</h4>
            <div class="row text-center">
                <div class="col-md-4 mb-2">
                    <h5 class="fw-bold text-dark">Score</h5>
                    <span class="badge bg-primary fs-5">{{ $result->score }} / {{ $result->total_marks }}</span>
                </div>
                <div class="col-md-4 mb-2">
                    <h5 class="fw-bold text-dark">Grade</h5>
                    <span class="badge {{ str_contains($result->grade, 'Fail') ? 'bg-danger' : 'bg-success' }} fs-5">
                        {{ $result->grade }}
                    </span>
                </div>
                <div class="col-md-4 mb-2">
                    <h5 class="fw-bold text-dark">Submitted On</h5>
                    <span class="text-muted">{{ $result->created_at->format('M d, Y - g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3"><i class="fas fa-list text-primary me-2"></i> Question Breakdown</h4>

    @foreach($quiz->questions as $index => $question)
        @php
            // Look up the student's answer for this specific question
            $studentAnswer = $studentAnswers->get($question->id);
            $options = explode(',', $question->options);
            
            // Determine border color based on correctness
            $borderColor = 'border-secondary'; // Default if unattempted somehow
            if ($studentAnswer) {
                $borderColor = $studentAnswer->is_correct ? 'border-success' : 'border-danger';
            }
        @endphp

        <div class="card shadow-sm mb-4 border-2 {{ $borderColor }}">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    Question {{ $index + 1 }} 
                    <span class="text-muted fs-6 fw-normal ms-2">({{ $question->marks }} Marks)</span>
                </h5>
                <div>
                    @if($studentAnswer && $studentAnswer->is_correct)
                        <span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i> Correct</span>
                    @else
                        <span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i> Incorrect</span>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-4">
                <p class="fs-5 mb-4">{{ $question->question_text }}</p>

                <div class="list-group">
                    @foreach($options as $option)
                        @php
                            $option = trim($option);
                            $isStudentsAnswer = $studentAnswer && $studentAnswer->submitted_answer === $option;
                            $isCorrectAnswer = $question->correct_answer === $option;
                            
                            $itemClass = '';
                            $icon = '';

                            if ($isCorrectAnswer) {
                                // The actual correct answer is always highlighted in green
                                $itemClass = 'list-group-item-success fw-bold';
                                $icon = '<i class="fas fa-check-circle text-success me-2"></i>';
                            } elseif ($isStudentsAnswer && !$isCorrectAnswer) {
                                // The student's wrong answer is highlighted in red
                                $itemClass = 'list-group-item-danger text-decoration-line-through';
                                $icon = '<i class="fas fa-times-circle text-danger me-2"></i>';
                            } else {
                                // Regular unselected option
                                $itemClass = 'text-muted';
                                $icon = '<i class="far fa-circle text-secondary me-2"></i>';
                            }
                        @endphp
                        
                        <div class="list-group-item {{ $itemClass }} d-flex align-items-center fs-6 py-3">
                            {!! $icon !!} 
                            {{ $option }}
                            
                            @if($isStudentsAnswer)
                                <span class="badge bg-secondary ms-auto small">Your Answer</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection