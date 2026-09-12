@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div>
        <h1 class="font-display text-3xl font-semibold text-stone-900">
            Welcome, {{ auth('admins')->user()->name }}
        </h1>
        <p class="mt-2 text-stone-500">Role: {{ auth('admins')->user()->getRoleNames()->implode(', ') }}</p>
    </div>
@endsection
