<div class="modal fade" id="usageModal{{ $quiz->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-users me-2"></i> Shared With</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($quiz->clones as $clone)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <span class="fw-bold text-dark">{{ $clone->teacher->name ?? $clone->user->name ?? 'Shared Teacher' }}</span><br>
                                <small class="text-muted">Join Code: <code class="fs-6 text-info">{{ $clone->unique_code ?? $clone->code ?? 'N/A' }}</code></small>
                            </div>
                            <span class="badge bg-primary rounded-pill py-2 px-3 shadow-sm">
                                {{ optional($clone->results)->count() ?? 0 }} Attempts
                            </span>
                        </li>
                    @empty
                        <div class="text-center p-5 text-muted">
                            <i class="fas fa-share-alt fa-3x mb-3 text-light"></i>
                            <h6 class="fw-bold">Not shared yet</h6>
                            <p class="mb-0 small">Share this quiz with other teachers to see them listed here.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
            
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>