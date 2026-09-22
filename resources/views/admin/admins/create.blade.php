@extends('layouts.admin')

@section('title', __('admin.new_admin'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.new_admin') }}</h2>

    <form method="POST" action="{{ route('admin.admins.store') }}">
        @include('admin.admins.form')
    </form>
@endsection
