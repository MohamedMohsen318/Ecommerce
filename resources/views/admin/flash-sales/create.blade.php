@extends('layouts.admin')

@section('title', __('admin.new_flash_sale'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.new_flash_sale') }}</h2>

    <form method="POST" action="{{ route('admin.flash-sales.store') }}">
        @include('admin.flash-sales.form')
    </form>
@endsection
