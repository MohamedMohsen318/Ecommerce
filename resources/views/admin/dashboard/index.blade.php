@extends('layouts.admin')

@section('title', __('admin.dashboard'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.dashboard') }}</h2>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">{{ __('admin.customers') }}</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['customers_count'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">{{ __('admin.admins') }}</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['admins_count'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">{{ __('admin.super_admins') }}</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['super_admins_count'] }}</p>
        </div>
        <div class="rounded-xl border border-stone-200 bg-white p-5">
            <p class="text-sm text-stone-500">{{ __('admin.support') }}</p>
            <p class="mt-1 text-3xl font-semibold text-stone-900">{{ $stats['support_count'] }}</p>
        </div>
    </div>
@endsection
