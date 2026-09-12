@extends('layouts.admin')

@section('title', 'Items')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">Items</h2>
        <a href="{{ route('admin.items.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            New item
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($items as $item)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $item->translate('en')?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $item->category?->translate('en')?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ number_format($item->price, 2) }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $item->stock }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.items.edit', $item) }}" class="text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}" class="inline"
                              onsubmit="return confirm('Delete this item?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-stone-500">No items yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
