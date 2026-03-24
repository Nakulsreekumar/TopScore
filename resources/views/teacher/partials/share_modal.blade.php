<div class="modal fade" id="shareModal{{ $quiz->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('quiz.share', $quiz->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-share-alt me-2"></i>Share Quiz: {{ $quiz->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Type the teacher's email to send them a clone of this quiz.</p>
                    <input type="email" name="email" class="form-control" placeholder="teacher@example.com" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">Share Now</button>
                </div>
            </div>
        </form>
    </div>
</div>