@extends('layouts.admin')

@section('title', __('admin.items'))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">{{ __('admin.items') }}</h2>
        <a href="{{ route('admin.items.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            {{ __('admin.new_item') }}
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">{{ __('admin.name') }}</th>
                <th class="px-4 py-3">{{ __('admin.category') }}</th>
                <th class="px-4 py-3">{{ __('admin.price') }}</th>
                <th class="px-4 py-3">{{ __('admin.stock') }}</th>
                <th class="px-4 py-3">{{ __('admin.status') }}</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($items as $item)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $item->translate()?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $item->category?->translate()?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ number_format($item->price, 2) }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $item->stock }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                            {{ $item->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.items.edit', $item) }}" class="text-brand-600 hover:underline">{{ __('admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.items.destroy', $item) }}" class="inline"
                              onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">{{ __('admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-stone-500">{{ __('admin.no_results') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection
