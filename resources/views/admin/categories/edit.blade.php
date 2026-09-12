@extends('layouts.admin')

@section('title', 'Edit category')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">Edit category</h2>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @include('admin.categories.form')
    </form>
@endsection
