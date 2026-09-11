@extends('layouts.app')

@section('title', 'إنشاء حساب')

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="font-display text-3xl font-semibold text-stone-900">إنشاء حساب جديد</h1>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-stone-700">الاسم</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700">كلمة المرور</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-stone-700">تأكيد كلمة المرور</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full rounded-lg border-stone-300 focus:border-brand-500 focus:ring-brand-500">
            </div>

            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 font-medium text-white hover:bg-brand-700">
                إنشاء الحساب
            </button>
        </form>

        <p class="mt-6 text-sm text-stone-500">
            عندك حساب بالفعل؟
            <a href="{{ route('login.create') }}" class="font-medium text-brand-600 hover:underline">سجل دخول</a>
        </p>
    </div>
@endsection
