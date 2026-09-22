@extends('layouts.admin')

@section('title', __('admin.categories'))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">{{ __('admin.categories') }}</h2>
        <a href="{{ route('admin.categories.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            {{ __('admin.new_category') }}
        </a>
    </div>

    @error('category') <p class="mb-4 text-sm text-red-600">{{ $message }}</p> @enderror

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">{{ __('admin.name') }}</th>
                <th class="px-4 py-3">{{ __('admin.parent') }}</th>
                <th class="px-4 py-3">{{ __('admin.status') }}</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($categories as $category)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $category->translate()?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $category->parent?->translate()?->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="rounded-full px-2 py-0.5 text-xs {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                            {{ $category->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-600 hover:underline">{{ __('admin.edit') }}</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                              onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">{{ __('admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-stone-500">{{ __('admin.no_results') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
