@extends('layouts.admin')

@section('title', 'New item')

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">New item</h2>

    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
        @include('admin.items.form')
    </form>
@endsection
