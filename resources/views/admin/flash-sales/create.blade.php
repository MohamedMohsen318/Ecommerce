@extends('layouts.admin')

@section('title', 'New flash sale')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">New flash sale</h2>

    <form method="POST" action="{{ route('admin.flash-sales.store') }}">
        @include('admin.flash-sales.form')
    </form>
@endsection
