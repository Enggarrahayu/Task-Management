<x-filament::page>

<style>
  .comment-container {
      max-width: 300px;
      padding: 1rem;
      border-radius: 1rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 1rem;
      font-family: Arial, sans-serif;
      font-size: 14px;
  }
  .comment-user {
      background-color: #3b82f6;
      color: white;
      text-align: right;
  }
  .comment-other {
      background-color: #e5e7eb;
      color: #374151;
      text-align: left;
  }
  .comment-meta {
      font-size: 12px;
      opacity: 0.7;
      margin-top: 0.5rem;
  }
  .custom-textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.75rem;
      background-color: white;
      color: #374151;
      font-size: 14px;
      font-family: Arial, sans-serif;
      resize: vertical;
  }
  .custom-textarea::placeholder {
      color: #9ca3af;
  }

  .card {
    padding: 1.5rem;
    background-color: white;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    font-family: Arial, sans-serif;
  }
  .card-title {
    font-size: 28px;
    font-weight: bold;
    color: #1f2937;
    margin-bottom: 1rem;
  }
  .card-content {
    margin-top: 0.5rem;
    color: #4b5563; 
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  .card-content p {
    margin: 0;
  }
  .font-semibold {
    font-weight: 600;
  }
</style>

<div class="space-y-6">

    {{-- Task Info --}}
    <div class="card">
      <h2 class="card-title">{{ $record->title }}</h2>
      <div class="card-content">
          <p><span class="font-semibold">Project:</span> {{ $record->project->name }}</p>
          <p><span class="font-semibold">Status:</span> {{ ucfirst(str_replace('_', ' ', $record->status)) }}</p>
          <p><span class="font-semibold">Assigned to:</span> {{ $record->user->name }}</p>
          <p><span class="font-semibold">Deadline:</span> {{ \Carbon\Carbon::parse($record->deadline)->format('d M Y') }}</p>
      </div>
    </div>

    {{-- Add New Comment --}}
    <div class="card p-6 bg-white shadow rounded-2xl">
      <h3 class="text-xl font-bold text-gray-700 mb-4">Add a Comment</h3>

      <form method="POST" action="{{ route('tasks.addComment', $record) }}" class="space-y-4">
          @csrf
          <textarea
              name="comment"
              class="custom-textarea"
              rows="4"
              placeholder="Write your comment..."
              required
          ></textarea>

          <x-filament::button type="submit" class="mt-4">
              Add Comment
          </x-filament::button>
      </form>
    </div>

    {{-- List of Comments --}}
    <div class="card p-6 bg-white shadow rounded-2xl">
        <h3 class="text-xl font-bold text-gray-700 mb-6">Comments</h3>

        @forelse ($record->taskComments as $comment)
            <div class="flex {{ $comment->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="comment-container {{ $comment->user_id === auth()->id() ? 'comment-user' : 'comment-other' }}">
                    <p>{{ $comment->comment }}</p>
                    <p class="comment-meta">
                        — {{ $comment->user->name }} at {{ $comment->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <p style="color: #6b7280;">No comments yet.</p>
        @endforelse
    </div>

</div>

</x-filament::page>
