@extends('welcome')

@section('content')
<style>
    /* Smooth fade animation for changing questions */
    .fade-in {
        animation: fadeIn 0.4s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Styles for the question navigator boxes */
    .nav-box {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.2s;
    }
    .nav-box:hover {
        transform: scale(1.1);
    }
    .nav-box.active {
        border: 3px solid #0d6efd; 
    }
    .nav-box.answered {
        background-color: #198754; 
        color: white;
    }
    .nav-box.unanswered {
        background-color: #e9ecef;
        color: #495057;
    }
    
    /* ANTI-CHEAT: Disable Text Highlighting */
    .noselect {
        -webkit-user-select: none; /* Safari */
        -ms-user-select: none;     /* IE 10 and IE 11 */
        user-select: none;         /* Standard syntax */
    }
</style>

{{-- ANTI-CHEAT: Added the noselect class to the main wrapper --}}
<div class="container mt-4 position-relative noselect">

    @if($has_ended)
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card shadow border-0 p-5">
                    <i class="fas fa-calendar-times fa-4x text-danger mb-4"></i>
                    <h2 class="fw-bold text-dark">Quiz Closed</h2>
                    <p class="text-muted fs-5">Sorry, the window to take <strong>{{ $quiz->title }}</strong> has ended.</p>
                    <p class="text-secondary mb-4">It closed on {{ $quiz->end_time->format('l, F jS \a\t g:i A') }}.</p>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Return to Dashboard
                    </a>
                </div>
            </div>
        </div>

    @elseif(!$has_started)
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center">
                <div class="card shadow border-0 p-5">
                    <i class="fas fa-hourglass-half fa-4x text-info mb-4"></i>
                    <h2 class="fw-bold text-dark">Waiting Room</h2>
                    <p class="text-muted fs-5">You are early! <strong>{{ $quiz->title }}</strong> has not started yet.</p>
                    <div class="alert alert-info mt-3 mb-4 fs-5 shadow-sm">
                        <i class="fas fa-clock me-2"></i> Opens on: <br>
                        <strong>{{ $quiz->start_time->format('l, F jS \a\t g:i A') }}</strong>
                    </div>
                    <button onclick="window.location.reload();" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-sync-alt me-2"></i> Refresh Page
                    </button>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-link text-muted mt-3 d-block">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

    @elseif($is_active)
        
        <div class="position-fixed top-0 end-0 m-4 z-3">
            <div class="card shadow border-0 bg-dark text-white">
                <div class="card-body p-3 text-center">
                    <h6 class="mb-1 text-uppercase text-muted small fw-bold">Time Remaining</h6>
                    <div id="timerDisplay" class="fs-2 fw-bold text-warning" style="font-variant-numeric: tabular-nums;">
                        --:--
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-edit me-2"></i>{{ $quiz->title }}
                </h2>
                <div class="alert alert-warning py-2 shadow-sm d-inline-block mb-0">
                    <i class="fas fa-exclamation-circle me-1"></i> Closes at {{ $quiz->end_time->format('g:i A') }}
                </div>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <h5 class="text-muted mb-0">
                    Stay Focused! <i class="fas fa-brain text-success fa-bounce ms-2"></i>
                </h5>
            </div>
        </div>

        <form id="quizForm" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            
            <input type="hidden" name="tab_switches" id="tab_switches" value="0">
            
            {{-- UPDATED: Using $questions variable passed from controller to support randomized order --}}
            @if($questions->isEmpty())
                <div class="alert alert-warning shadow-sm">
                    <i class="fas fa-exclamation-triangle me-2"></i> This quiz has no questions yet!
                </div>
            @else
                <div class="row">
                    
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0" style="min-height: 350px;">
                            {{-- UPDATED: Loop over $questions --}}
                            @foreach($questions as $index => $question)
                                <div class="question-slide fade-in p-4" id="step-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-dark fw-bold mb-0">
                                            <span class="badge bg-primary me-2">Question {{ $index + 1 }}</span> 
                                        </h4>
                                        <span class="text-muted small fw-bold">{{ $question->marks }} Marks</span>
                                    </div>
                                    
                                    <h5 class="mb-4">{{ $question->question_text }}</h5>

                                    @php 
                                        // SMART SPLIT: Handle strings vs arrays safely
                                        if (is_array($question->options)) {
                                            $options = $question->options;
                                        } else {
                                            $separator = str_contains($question->options, '|') ? '|' : ',';
                                            $options = explode($separator, $question->options); 
                                        }
                                        
                                        // ANTI-CHEAT: Randomize the options array
                                        shuffle($options);
                                    @endphp

                                    @foreach($options as $option)
                                        @if(trim($option) !== '') 
                                            <div class="form-check custom-radio mb-3 p-3 border rounded shadow-sm option-container" onclick="document.getElementById('q_{{ $question->id }}_{{ $loop->index }}').click();">
                                                <input class="form-check-input ms-2 mt-1 answer-radio" type="radio" 
                                                    name="q_{{ $question->id }}" 
                                                    id="q_{{ $question->id }}_{{ $loop->index }}" 
                                                    value="{{ trim($option) }}" 
                                                    data-step="{{ $index }}">
                                                <label class="form-check-label fs-5 ms-2 cursor-pointer w-100" for="q_{{ $question->id }}_{{ $loop->index }}">
                                                    {{ trim($option) }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="card-footer bg-white border-0 d-flex justify-content-between p-4 pt-0">
                                <button type="button" id="prevBtn" class="btn btn-outline-secondary px-4 fw-bold" onclick="changeStep(-1)" disabled>
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                <button type="button" id="nextBtn" class="btn btn-primary px-4 fw-bold" onclick="changeStep(1)">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 position-sticky" style="top: 20px;">
                            <div class="card-header bg-dark text-white text-center">
                                <h5 class="mb-0"><i class="fas fa-th me-2"></i> Question Navigator</h5>
                            </div>
                            <div class="card-body text-center p-4">
                                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                                    {{-- UPDATED: Loop over $questions --}}
                                    @foreach($questions as $index => $question)
                                        <div class="nav-box unanswered {{ $index == 0 ? 'active' : '' }}" id="nav-box-{{ $index }}" onclick="goToStep({{ $index }})" title="Go to Question {{ $index + 1 }}">
                                            {{ $index + 1 }}
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="d-flex justify-content-center gap-3 mb-4 small text-muted">
                                    <span><i class="fas fa-square text-success me-1"></i> Answered</span>
                                    <span><i class="fas fa-square" style="color:#e9ecef; margin-right: 4px;"></i> Skipped</span>
                                </div>

                                <hr>
                                <button type="button" onclick="confirmSubmit()" class="btn btn-lg btn-success w-100 fw-bold shadow">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Exam
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </form>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let currentStep = 0;
            // UPDATED: Count via $questions instead of $quiz->questions
            const totalSteps = {{ $questions->count() ?? 0 }};
            const form = document.getElementById('quizForm');

            // --- ANTI-CHEAT: TAB SWITCH DETECTION ---
            let switchCount = 0;
            document.addEventListener("visibilitychange", () => {
                if (document.hidden) {
                    // The student switched tabs or minimized the window!
                    switchCount++;
                    document.getElementById("tab_switches").value = switchCount;
                    
                    Swal.fire({
                        title: "⚠️ Warning!",
                        text: "You have left the quiz tab! This violation has been recorded and will be reported to your teacher.",
                        icon: "warning",
                        confirmButtonText: "I Understand",
                        confirmButtonColor: "#d33",
                        allowOutsideClick: false
                    });
                }
            });

            // --- QUIZ WIZARD LOGIC ---
            function showStep(step) {
                document.querySelectorAll('.question-slide').forEach(el => el.style.display = 'none');
                document.getElementById('step-' + step).style.display = 'block';

                document.getElementById('prevBtn').disabled = (step === 0);
                document.getElementById('nextBtn').style.display = (step === totalSteps - 1) ? 'none' : 'inline-block';
                
                document.querySelectorAll('.nav-box').forEach(el => el.classList.remove('active'));
                document.getElementById('nav-box-' + step).classList.add('active');
            }

            function changeStep(n) {
                currentStep += n;
                showStep(currentStep);
            }

            function goToStep(n) {
                currentStep = n;
                showStep(currentStep);
            }

            // Mark boxes green when answered
            document.querySelectorAll('.answer-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    let stepIndex = this.getAttribute('data-step');
                    let navBox = document.getElementById('nav-box-' + stepIndex);
                    navBox.classList.remove('unanswered');
                    navBox.classList.add('answered');
                });
            });

            // Smart Submission Validation
            function confirmSubmit() {
                let answeredCount = document.querySelectorAll('.nav-box.answered').length;
                let unansweredCount = totalSteps - answeredCount;

                if(unansweredCount > 0) {
                    Swal.fire({
                        title: "Incomplete Quiz!",
                        text: `You have ${unansweredCount} unanswered question(s). Are you sure you want to submit?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#198754",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, submit anyway",
                        cancelButtonText: "No, let me finish"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Ready to Submit?",
                        text: "You have answered all questions.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#198754",
                        confirmButtonText: "Submit Exam"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                }
            }

            function submitForm() {
                sessionStorage.removeItem('topscore_quiz_endtime_{{ $quiz->id }}');
                form.submit();
            }

            // --- TIMER LOGIC ---
            document.addEventListener("DOMContentLoaded", function() {
                if (totalSteps === 0) return; // Skip timer if no questions

                let durationMinutes = {{ $quiz->duration_minutes }};
                let quizId = {{ $quiz->id }};
                let storageKey = 'topscore_quiz_endtime_' + quizId;
                
                let endTime = sessionStorage.getItem(storageKey);

                if (!endTime) {
                    endTime = new Date().getTime() + (durationMinutes * 60 * 1000);
                    sessionStorage.setItem(storageKey, endTime);
                } else {
                    endTime = parseInt(endTime);
                }

                let timerDisplay = document.getElementById('timerDisplay');

                let timerInterval = setInterval(function() {
                    let now = new Date().getTime();
                    let distance = endTime - now;

                    if (distance <= 0) {
                        clearInterval(timerInterval);
                        timerDisplay.innerText = "00:00";
                        timerDisplay.classList.replace('text-warning', 'text-danger');
                        
                        Swal.fire({
                            title: "Time's Up!",
                            text: "Your exam is being submitted automatically.",
                            icon: "info",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000
                        }).then(() => {
                            submitForm();
                        });
                        return;
                    }

                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
                    let formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

                    if (hours > 0) {
                        let formattedHours = hours < 10 ? "0" + hours : hours;
                        timerDisplay.innerText = formattedHours + ":" + formattedMinutes + ":" + formattedSeconds;
                    } else {
                        timerDisplay.innerText = formattedMinutes + ":" + formattedSeconds;
                    }

                    if (hours === 0 && minutes === 0 && seconds <= 59) {
                        timerDisplay.classList.replace('text-warning', 'text-danger');
                        timerDisplay.style.opacity = (seconds % 2 === 0) ? '0.5' : '1';
                    }
                }, 1000);

            });

            // --- STRICT ANTI-CHEAT RESTRICTIONS ---

            // 1. Disable Right-Click (Context Menu)
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                fireWarningToast("Right-clicking is disabled during the exam.");
            });

            // 2. Disable Keyboard Shortcuts (Copy, Paste, F12, Inspect Element)
            document.addEventListener('keydown', function(e) {
                // Prevent F12 (Dev Tools)
                if (e.key === 'F12') {
                    e.preventDefault();
                    fireWarningToast("Developer tools are disabled.");
                }
                
                // Prevent Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+U (Inspect/Console/Source)
                if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i' || e.key === 'J' || e.key === 'j')) {
                    e.preventDefault();
                    fireWarningToast("Developer tools are disabled.");
                }

                if (e.ctrlKey && (e.key === 'U' || e.key === 'u')) {
                    e.preventDefault();
                    fireWarningToast("Viewing source code is disabled.");
                }

                // Prevent Ctrl+C, Ctrl+V, Ctrl+X (Copy, Paste, Cut)
                if (e.ctrlKey && (e.key === 'C' || e.key === 'c' || e.key === 'V' || e.key === 'v' || e.key === 'X' || e.key === 'x')) {
                    e.preventDefault();
                    fireWarningToast("Copy and Paste shortcuts are disabled.");
                }
            });

            // Helper function to show a quick toast warning using SweetAlert2
            function fireWarningToast(message) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Action Blocked',
                    text: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        </script>
    @endif
</div>
@endsection