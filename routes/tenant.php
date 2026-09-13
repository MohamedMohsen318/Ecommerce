<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Home

    Route::get('/', function () {
        return view('welcome');
    })->name('home');


    // Customer Authentication

    Route::middleware('guest')->group(function () {

        Route::get('/register', [AuthController::class, 'showRegister'])
            ->name('register.create');

        Route::post('/register', [AuthController::class, 'register'])
            ->name('register.store');

        Route::get('/login', [AuthController::class, 'showLogin'])
            ->name('login.create');

        Route::post('/login', [AuthController::class, 'login'])
            ->name('login.store');
    });

    Route::middleware('auth')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');
    });


    // Shop

    Route::get('/shop', [ShopController::class, 'index'])
        ->name('shop.index');


    // Cart

    Route::prefix('cart')
        ->name('cart.')
        ->group(function () {

            Route::get('/', [CartController::class, 'index'])
                ->name('index');

            Route::post('/', [CartController::class, 'store'])
                ->name('store');

            Route::put('/{cartItem}', [CartController::class, 'update'])
                ->name('update');

            Route::delete('/{cartItem}', [CartController::class, 'destroy'])
                ->name('destroy');
        });


    // Admin

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Admin Authentication

            Route::middleware('guest:admins')->group(function () {

                Route::get('/login', [AdminAuthController::class, 'showLogin'])
                    ->name('login.create');

                Route::post('/login', [AdminAuthController::class, 'login'])
                    ->name('login.store');
            });


            // Admin Protected Routes

            Route::middleware('auth:admins')->group(function () {

                // Admin Logout

                Route::post('/logout', [AdminAuthController::class, 'logout'])
                    ->name('logout');


                // Dashboard

                Route::middleware('permission:view_dashboard,admins')
                    ->get('/dashboard', function () {
                        return view('admin.dashboard');
                    })
                    ->name('dashboard.index');


                // Admin Management

                Route::middleware('permission:manage_admins,admins')->group(function () {

                    Route::resource('admins', AdminController::class)
                        ->except(['show']);
                });


                // Category Management

                Route::middleware('permission:manage_categories,admins')->group(function () {

                    Route::resource('categories', CategoryController::class)
                        ->except(['show']);
                });


                // Item Management

                Route::middleware('permission:manage_items,admins')->group(function () {

                    Route::resource('items', ItemController::class)
                        ->except(['show']);
                });
            });
        });
});
