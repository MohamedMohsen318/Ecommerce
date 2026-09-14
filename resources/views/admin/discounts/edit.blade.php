@extends('layouts.admin')

@section('title', 'Edit discount')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Edit discount</h2>

    <form method="POST" action="{{ route('admin.discounts.update', $discount) }}">
        @include('admin.discounts.form')
    </form>
@endsection
