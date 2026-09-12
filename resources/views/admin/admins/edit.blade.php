@extends('layouts.admin')

@section('title', 'Edit admin')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Edit admin</h2>

    <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
        @include('admin.admins.form')
    </form>
@endsection
