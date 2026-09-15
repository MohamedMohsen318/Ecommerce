@extends('layouts.admin')

@section('title', 'Flash Sales')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">Flash Sales</h2>
        <a href="{{ route('admin.flash-sales.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            New flash sale
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Window</th>
                <th class="px-4 py-3">Items</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($flashSales as $flashSale)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $flashSale->name }}</td>
                    <td class="px-4 py-3 text-stone-600">
                        {{ $flashSale->starts_at->format('M j, H:i') }} - {{ $flashSale->ends_at->format('M j, H:i') }}
                    </td>
                    <td class="px-4 py-3 text-stone-600">{{ $flashSale->items_count }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $flashSale->isRunning() ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                            {{ $flashSale->isRunning() ? 'Running' : 'Not running' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.flash-sales.destroy', $flashSale) }}" class="inline"
                              onsubmit="return confirm('Delete this flash sale?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-stone-500">No flash sales yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $flashSales->links() }}</div>
@endsection
