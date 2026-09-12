@extends('layouts.admin')

@section('title', 'Admins')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-stone-900">Admins</h2>
        <a href="{{ route('admin.admins.create') }}"
           class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
            New admin
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wide text-stone-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Roles</th>
                <th class="px-4 py-3"></th>
            </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @forelse ($admins as $admin)
                <tr>
                    <td class="px-4 py-3 font-medium text-stone-900">{{ $admin->name }}</td>
                    <td class="px-4 py-3 text-stone-600">{{ $admin->email }}</td>
                    <td class="px-4 py-3">
                        @foreach ($admin->roles as $role)
                            <span class="mr-1 inline-block rounded-full bg-stone-100 px-2 py-0.5 text-xs text-stone-600">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" class="inline"
                              onsubmit="return confirm('Delete this admin?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-stone-500">No admins yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $admins->links() }}</div>
@endsection
