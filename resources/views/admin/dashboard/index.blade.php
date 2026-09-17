@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Dashboard</h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Customers</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['customers_count'] }}</p>
        </div>

        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Admins</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['admins_count'] }}</p>
        </div>

        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Super Admins</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['super_admins_count'] }}</p>
        </div>

        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">Support</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['support_count'] }}</p>
        </div>
    </div>
@endsection
