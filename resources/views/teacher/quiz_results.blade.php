@extends('welcome')

@section('content')
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold"><i class="fas fa-chart-line text-primary me-2"></i> Results for: {{ $quiz->title }}</h2>
            <p class="text-muted mb-0">Join Code: <span class="badge bg-info-subtle text-info border border-info">{{ $quiz->unique_code ?? $quiz->code }}</span></p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>

    @if($results->isEmpty())
        <div class="alert alert-warning shadow-sm fs-5 border-0">
            <i class="fas fa-info-circle me-2"></i> No students have taken this quiz yet. Check back later!
        </div>
    @else
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-chart-bar me-2"></i> Class Performance Chart</h5>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i> Detailed Student Scores</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Rank</th>
                                <th class="text-start py-3">Student Name</th>
                                <th class="py-3">Score</th>
                                <th class="py-3">Grade</th>
                                <th class="py-3">Violations</th>
                                <th class="py-3">Date Taken</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $result)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <i class="fas fa-trophy fs-4" style="color: #FFD700;" title="1st Place"></i>
                                        @elseif($index == 1)
                                            <i class="fas fa-medal text-secondary fs-4" title="2nd Place"></i>
                                        @elseif($index == 2)
                                            <i class="fas fa-medal fs-4" style="color: #CD7F32;" title="3rd Place"></i>
                                        @else
                                            <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-start fw-bold text-dark">
                                        {{ $result->user->name ?? 'Deleted Student' }}
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $result->score }} / {{ $result->total_marks }}</span>
                                    </td>
                                    
                                    <td class="fw-bold {{ str_contains($result->grade, 'Fail') ? 'text-danger' : 'text-success' }}">
                                        {{ $result->grade }}
                                    </td>
                                    
                                    <td>
                                        @if($result->tab_switches > 0)
                                            <span class="badge bg-danger fs-6" title="Student left the tab {{ $result->tab_switches }} time(s)">
                                                <i class="fas fa-exclamation-triangle me-1"></i> {{ $result->tab_switches }} Switches
                                            </span>
                                        @else
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check-circle me-1"></i> Clean
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-muted small">
                                        {{ $result->created_at->format('M d, Y') }}<br>
                                        {{ $result->created_at->format('g:i A') }}
                                    </td>

                                    <td>
                                        @if($result->user)
                                            <a href="{{ route('teacher.student_review', ['quiz_id' => $quiz->id, 'user_id' => $result->user->id]) }}" class="btn btn-sm btn-outline-info shadow-sm px-3">
                                                <i class="fas fa-search me-1"></i> Review
                                            </a>
                                        @else
                                            <span class="badge bg-secondary">User Deleted</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Extract the data for the chart from the existing $results collection --}}
@php
    $studentNames = [];
    $studentScores = [];
    if($results->isNotEmpty()){
        foreach($results as $result) {
            $studentNames[] = $result->user->name ?? 'Deleted Student';
            $studentScores[] = $result->score;
        }
    }
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if($results->isNotEmpty())
            const ctx = document.getElementById('performanceChart');
            
            // Check if context exists to prevent errors
            if (ctx) {
                const labels = @json($studentNames);
                const dataPoints = @json($studentScores);

                if(labels.length > 0 && dataPoints.length > 0) {
                    new Chart(ctx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Marks Scored',
                                data: dataPoints,
                                backgroundColor: 'rgba(13, 110, 253, 0.7)', 
                                borderColor: 'rgba(13, 110, 253, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Allows chart to fit the container height beautifully
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 1 },
                                    title: { display: true, text: 'Total Score', font: { weight: 'bold' } }
                                },
                                x: {
                                    title: { display: true, text: 'Student Names', font: { weight: 'bold' } }
                                }
                            },
                            plugins: {
                                legend: { display: false } 
                            }
                        }
                    });
                }
            }
        @endif
    });
</script>
@endsection