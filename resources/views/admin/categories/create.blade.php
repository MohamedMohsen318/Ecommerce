@extends('layouts.admin')

@section('title', 'New category')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">New category</h2>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
        @include('admin.categories.form')
    </form>
@endsection
