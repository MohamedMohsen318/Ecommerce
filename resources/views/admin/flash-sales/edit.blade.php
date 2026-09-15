@extends('layouts.admin')

@section('title', 'Edit flash sale')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Edit flash sale</h2>

    <form method="POST" action="{{ route('admin.flash-sales.update', $flashSale) }}">
        @include('admin.flash-sales.form')
    </form>
@endsection
