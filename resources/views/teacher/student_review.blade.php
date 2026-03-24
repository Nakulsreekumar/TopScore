@extends('welcome')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Reviewing: {{ $student->name }}'s Attempt</h2>
        <a href="{{ url('/teacher/quiz/' . $quiz->id . '/results') }}" class="btn btn-secondary"></a>
            <i class="fas fa-arrow-left"></i> Back to Results
        </a>
    </div>

    @foreach($quiz->questions as $index => $question)
        @php
            // Get the student's answer if it exists
            $studentAnswer = $userAnswers->get($question->id);
            $submitted = $studentAnswer ? $studentAnswer->submitted_answer : null;
            
            // Determine card color based on correctness
            if (!$submitted) {
                $bgClass = 'border-warning'; // Skipped
                $badge = '<span class="badge bg-warning text-dark">Skipped</span>';
            } elseif ($submitted == $question->correct_answer) {
                $bgClass = 'border-success'; // Correct
                $badge = '<span class="badge bg-success">Correct</span>';
            } else {
                $bgClass = 'border-danger'; // Wrong
                $badge = '<span class="badge bg-danger">Incorrect</span>';
            }
        @endphp

        <div class="card mb-3 shadow-sm {{ $bgClass }}" style="border-width: 2px;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Q{{ $index + 1 }}. {{ $question->question_text }}</h5>
                    <div>{!! $badge !!}</div>
                </div>
                
                <hr>
                
                <p class="mb-1 text-muted">Correct Answer:</p>
                <p class="fw-bold text-success"><i class="fas fa-check-circle"></i> {{ $question->correct_answer }}</p>

                <p class="mb-1 text-muted">Student's Answer:</p>
                @if($submitted)
                    <p class="fw-bold {{ $submitted == $question->correct_answer ? 'text-success' : 'text-danger' }}">
                        {{ $submitted == $question->correct_answer ? current(explode(' ', '<i class="fas fa-check-circle"></i>')) : current(explode(' ', '<i class="fas fa-times-circle"></i>')) }} 
                        <i class="fas {{ $submitted == $question->correct_answer ? 'fa-check-circle' : 'fa-times-circle' }}"></i> {{ $submitted }}
                    </p>
                @else
                    <p class="fw-bold text-warning"><i class="fas fa-minus-circle"></i> (Did not answer)</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection