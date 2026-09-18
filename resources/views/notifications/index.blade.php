@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-stone-900">Notifications</h1>

    <div class="mt-6 space-y-3">
        @forelse ($notifications as $notification)
            <div class="flex items-center justify-between rounded-xl border p-4 {{ $notification->read_at ? 'border-stone-200 bg-white' : 'border-blue-200 bg-blue-50' }}">
                <div>
                    <p class="text-sm text-stone-800">
                        Order #{{ $notification->data['order_id'] }} is now
                        <span class="font-medium">{{ ucfirst($notification->data['status']) }}</span>
                    </p>
                    <p class="mt-1 text-xs text-stone-400">{{ $notification->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="text-sm font-semibold text-blue-700 hover:underline">View order</a>
                    @unless ($notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-stone-400 hover:underline">Mark as read</button>
                        </form>
                    @endunless
                </div>
            </div>
        @empty
            <p class="text-stone-500">No notifications yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
