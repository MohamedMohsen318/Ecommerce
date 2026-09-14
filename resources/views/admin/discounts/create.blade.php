@extends('layouts.admin')

@section('title', 'New discount')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">New discount</h2>

    <form method="POST" action="{{ route('admin.discounts.store') }}">
        @include('admin.discounts.form')
    </form>
@endsection
