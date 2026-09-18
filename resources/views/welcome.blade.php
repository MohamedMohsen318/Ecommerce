@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    <section class="overflow-hidden rounded-2xl bg-slate-950 text-white shadow-xl shadow-slate-200">
        <div class="grid items-center gap-10 px-6 py-12 sm:px-10 lg:grid-cols-[1.2fr_0.8fr] lg:px-14 lg:py-16">
            <div>
                <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-sm font-medium text-teal-100">
                    المتجر: {{ tenant('id') }}
                </span>

                <h1 class="mt-6 max-w-3xl font-display text-4xl font-bold leading-tight sm:text-5xl">
                    أهلاً بيك في {{ config('app.name') }}
                </h1>

                <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-300">
                    تسوق منتجاتك بسهولة، تابع العروض، واحفظ المفضلة في تجربة أوضح وأسرع.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        href="{{ route('shop.index') }}"
                        class="rounded-lg bg-amber-400 px-5 py-3 text-sm font-bold text-slate-950 shadow-lg shadow-amber-950/20 transition hover:bg-amber-300"
                    >
                        تصفح المنتجات
                    </a>

                    <a
                        href="{{ route('deals.index') }}"
                        class="rounded-lg border border-white/15 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10"
                    >
                        شوف العروض
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <p class="text-sm text-slate-300">تجربة شراء</p>
                    <p class="mt-2 text-2xl font-bold">سهلة وواضحة</p>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <p class="text-sm text-slate-300">عروض وخصومات</p>
                    <p class="mt-2 text-2xl font-bold">متجددة باستمرار</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-10 grid gap-4 md:grid-cols-3">
        <a href="{{ route('shop.index') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm font-semibold text-teal-700">Shop</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950">كل المنتجات</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">استكشف المنتجات المتاحة واختر الأنسب لك.</p>
        </a>

        <a href="{{ route('deals.index') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm font-semibold text-rose-700">Deals</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950">أفضل العروض</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">تابع الخصومات والفرص قبل ما تخلص.</p>
        </a>

        <a href="{{ route('cart.index') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <p class="text-sm font-semibold text-amber-700">Cart</p>
            <h2 class="mt-2 text-xl font-bold text-slate-950">سلة الشراء</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">راجع اختياراتك وأكمل الطلب بسهولة.</p>
        </a>
    </section>
@endsection
