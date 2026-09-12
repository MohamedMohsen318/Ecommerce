@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-xl font-semibold text-stone-900">Welcome, {{ auth('admins')->user()->name }}</h2>
    <p class="mt-2 text-stone-500">Role: {{ auth('admins')->user()->getRoleNames()->implode(', ') }}</p>
@endsection
