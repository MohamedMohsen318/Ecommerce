@extends('layouts.admin')

@section('title', __('admin.reviews'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.reviews') }}</h2>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">{{ __('admin.item') }}</th>
                <th class="px-4 py-3">{{ __('admin.user') }}</th>
                <th class="px-4 py-3">{{ __('admin.rating') }}</th>
                <th class="px-4 py-3">{{ __('admin.status') }}</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($reviews as $review)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $review->item->translate()?->name }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $review->user->name }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $review->rating }}/5</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $review->is_approved ? __('admin.approved') : __('admin.pending') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @unless ($review->is_approved)
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-brand-600 hover:underline">{{ __('admin.approve') }}</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline"
                              onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">{{ __('admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-stone-500">{{ __('admin.no_results') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
@endsection
