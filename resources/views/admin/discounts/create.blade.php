@extends('layouts.admin')

@section('title', __('admin.new_discount'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.new_discount') }}</h2>

    <form method="POST" action="{{ route('admin.discounts.store') }}">
        @include('admin.discounts.form')
    </form>
@endsection
