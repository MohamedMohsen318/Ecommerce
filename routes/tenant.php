<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminController;



Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register.create');
        Route::post('/register', [AuthController::class, 'register'])->name('register.store');

        Route::get('/login', [AuthController::class, 'showLogin'])->name('login.create');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware('guest:admins')->group(function () {
            Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login.create');
            Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
        });

        Route::middleware('auth:admins')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
        });

    });

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware('guest:admins')->group(function () {
            Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login.create');
            Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
        });

        Route::middleware('auth:admins')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

            Route::middleware('permission:view_dashboard,admins')
                ->get('/dashboard', function () {
                    return view('admin.dashboard');
                })->name('dashboard.index');

            Route::middleware('permission:manage_admins,admins')->group(function () {
                Route::resource('admins', AdminController::class)->except(['show']);
            });
        });

    });

});
