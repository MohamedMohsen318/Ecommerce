@extends('layouts.admin')

@section('title', 'New admin')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">New admin</h2>

    <form method="POST" action="{{ route('admin.admins.store') }}">
        @include('admin.admins.form')
    </form>
@endsection
