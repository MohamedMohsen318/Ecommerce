@extends('layouts.admin')

@section('title', __('admin.comments'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">
        {{ __('admin.comments') }}
    </h2>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">
                    {{ __('admin.item') }}
                </th>

                <th class="px-4 py-3">
                    {{ __('admin.user') }}
                </th>

                <th class="px-4 py-3">
                    {{ __('admin.comments') }}
                </th>

                <th class="px-4 py-3">
                    {{ __('admin.actions') }}
                </th>
            </tr>
            </thead>

            <tbody class="divide-y divide-stone-100">
            @forelse ($comments as $comment)
                <tr>
                    {{-- Item --}}
                    <td class="px-4 py-3 text-stone-600">
                        {{ $comment->item?->translate()?->name ?? __('admin.unknown_item') }}
                    </td>

                    {{-- User --}}
                    <td class="px-4 py-3 text-stone-600">
                        {{ $comment->user?->name ?? __('admin.unknown_user') }}
                    </td>

                    {{-- Comment --}}
                    <td class="px-4 py-3 text-stone-900">
                        @if ($comment->parent)
                            <span class="mr-1 text-xs text-stone-400">
                                    {{ __('admin.reply') }}
                                </span>
                        @endif

                        {{ \Illuminate\Support\Str::limit($comment->body, 80) }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-right">
                        <form
                            method="POST"
                            action="{{ route('admin.comments.destroy', $comment) }}"
                            class="inline"
                            onsubmit="return confirm('{{ __('admin.confirm_delete') }}')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-red-600 hover:underline"
                            >
                                {{ __('admin.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td
                        colspan="4"
                        class="px-4 py-6 text-center text-stone-500"
                    >
                        {{ __('admin.no_results') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
@endsection
