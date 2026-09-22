@extends('layouts.admin')

@section('title', __('admin.edit_admin'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.edit_admin') }}</h2>

    <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
        @include('admin.admins.form')
    </form>
@endsection
