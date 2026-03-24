@extends('welcome')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-history text-primary"></i> Review: {{ $quiz->title }}</h2>
        <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            @foreach($quiz->questions as $index => $question)
                @php
                    // Get the student's answer for this question
                    $myAnswer = $userAnswers[$question->id] ?? null;
                    $isCorrect = $myAnswer ? $myAnswer->is_correct : false;
                    $cardClass = $isCorrect ? 'border-success' : 'border-danger';
                    $textClass = $isCorrect ? 'text-success' : 'text-danger';
                @endphp

                <div class="card shadow-sm mb-4 {{ $cardClass }}" style="border-width: 2px;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">
                            <span class="{{ $textClass }}">Q{{ $index + 1 }}.</span> 
                            {{ $question->question_text }}
                        </h5>

                        <div class="mt-3 ps-3 border-start border-3 {{ $isCorrect ? 'border-success' : 'border-danger' }}">
                            <p class="mb-1">
                                <strong>Your Answer:</strong> 
                                <span class="{{ $textClass }} fw-bold">
                                    {{ $myAnswer->submitted_answer ?? 'Not Answered' }}
                                </span>
                                @if($isCorrect)
                                    <i class="fas fa-check-circle text-success ms-2"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger ms-2"></i>
                                @endif
                            </p>
                            
                            @if(!$isCorrect)
                                <p class="mb-0 text-success">
                                    <strong>Correct Answer:</strong> {{ $question->correct_answer }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection