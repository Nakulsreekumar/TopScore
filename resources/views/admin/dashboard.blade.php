@extends('welcome')

@section('content')
<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-shield text-primary me-2"></i> Admin Dashboard</h1>
    </div>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Teachers</h5>
                    <p class="display-4 fw-bold">{{ $teachers->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Students</h5>
                    <p class="display-4 fw-bold">{{ $students->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow h-100">
                <div class="card-body text-center text-dark">
                    <h5 class="card-title">Active Quizzes</h5>
                    <p class="display-4 fw-bold">{{ $quizzes->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👨‍🏫 Registered Teachers</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td class="align-middle fw-bold">{{ $teacher->name }}</td>
                                <td class="align-middle">{{ $teacher->email }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-secondary">{{ $teacher->created_at->format('d M Y') }}</span>
                                    <small class="text-muted d-block">{{ $teacher->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.deleteUser', $teacher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this teacher?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🎓 Registered Students</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined Date</th>
                                <th>Last Quiz Attempt</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td class="align-middle fw-bold">{{ $student->name }}</td>
                                <td class="align-middle">{{ $student->email }}</td>
                                <td class="align-middle">
                                    {{ $student->created_at->format('d M Y') }}
                                </td>
                                <td class="align-middle">
                                    @if($student->results->isNotEmpty())
                                        @php $latest = $student->results->first(); @endphp
                                        
                                        <span class="d-block fw-bold text-primary mb-1">
                                            <i class="fas fa-file-alt me-1"></i> {{ $latest->quiz->title ?? 'Unknown Quiz' }}
                                        </span>
                                        
                                        <span class="text-success fw-bold small">
                                            {{ $latest->created_at->format('d M Y') }}
                                        </span>
                                        <small class="text-muted">
                                            at {{ $latest->created_at->format('h:i A') }}
                                        </small>
                                    @else
                                        <span class="text-muted small">No attempts yet</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.deleteUser', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure? All their quiz results will also be deleted.');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection