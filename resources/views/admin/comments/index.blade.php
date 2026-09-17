@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Comments</h2>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Item</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Comment</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($comments as $comment)
                <tr>
                    <td class="px-4 py-3 text-stone-600">{{ $comment->item->translate('en')?->name }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $comment->user->name }}</td>
                    <td class="px-4 py-3 text-stone-900">
                        @if ($comment->parent) <span class="text-xs text-stone-400">(reply)</span> @endif
                        {{ \Illuminate\Support\Str::limit($comment->body, 80) }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline"
                              onsubmit="return confirm('Delete this comment?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-stone-500">No comments yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $comments->links() }}</div>
@endsection
