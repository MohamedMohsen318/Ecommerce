@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <div class="text-center">
        <h1 class="font-display text-4xl font-semibold text-stone-900">
            أهلاً بيك في {{ config('app.name') }}
        </h1>
        <p class="mt-3 text-stone-500">
            المتجر: {{ tenant('id') }}
        </p>
    </div>
@endsection
