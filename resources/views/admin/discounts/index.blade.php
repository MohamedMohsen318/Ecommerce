@extends('layouts.admin')

@section('title', 'Discounts')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">Discounts</h2>
        <a href="{{ route('admin.discounts.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            New discount
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Code</th>
                <th class="px-4 py-3">Value</th>
                <th class="px-4 py-3">Used</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($discounts as $discount)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $discount->code }}</td>
                    <td class="px-4 py-3 text-stone-600">
                        {{ $discount->type->value === 'percentage' ? $discount->value . '%' : number_format($discount->value, 2) }}
                    </td>
                    <td class="px-4 py-3 text-stone-600">{{ $discount->used_count }}{{ $discount->max_uses ? ' / ' . $discount->max_uses : '' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $discount->isValid() ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                            {{ $discount->isValid() ? 'Valid' : 'Inactive/Expired' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" class="inline"
                              onsubmit="return confirm('Delete this discount?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-stone-500">No discounts yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $discounts->links() }}</div>
@endsection
