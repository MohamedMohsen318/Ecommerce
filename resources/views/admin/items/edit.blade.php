@extends('layouts.admin')

@section('title', 'Edit item')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Edit item</h2>

    <form method="POST" action="{{ route('admin.items.update', $item) }}" enctype="multipart/form-data">
        @include('admin.items.form')
    </form>
@endsection
