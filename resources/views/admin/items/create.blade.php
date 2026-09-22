@extends('layouts.admin')

@section('title', __('admin.new_item'))

@section('content')
    <h2 class="mb-6 text-xl font-semibold text-stone-900">{{ __('admin.new_item') }}</h2>

    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
        @include('admin.items.form')
    </form>
@endsection
