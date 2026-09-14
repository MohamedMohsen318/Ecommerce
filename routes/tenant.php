<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
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


    // Shop

    Route::get('/shop', [ShopController::class, 'index'])
        ->name('shop.index');

    Route::get('/shop/{item}', [ShopController::class, 'show'])
        ->name('shop.show');


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


    // Customer Protected Routes

    Route::middleware('auth')->group(function () {

        // Logout

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');


        // Wishlist

        Route::prefix('wishlist')
            ->name('wishlist.')
            ->group(function () {

                Route::get('/', [WishlistController::class, 'index'])
                    ->name('index');

                Route::post('/{item}/toggle', [WishlistController::class, 'toggle'])
                    ->name('toggle');
            });


        // Reviews

        Route::post('/items/{item}/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');

        Route::put('/reviews/{review}', [ReviewController::class, 'update'])
            ->name('reviews.update');

        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
            ->name('reviews.destroy');


        // Comments

        Route::post('/items/{item}/comments', [CommentController::class, 'store'])
            ->name('comments.store');


        // Checkout

        Route::get('/checkout', [CheckoutController::class, 'create'])
            ->name('checkout.create');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');


        // Orders

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
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


                // Discount Management

                Route::middleware('permission:manage_discounts,admins')->group(function () {

                    Route::resource('discounts', DiscountController::class)
                        ->except(['show']);
                });


                // Review Management

                Route::middleware('permission:manage_reviews,admins')->group(function () {

                    Route::get('/reviews', [AdminReviewController::class, 'index'])
                        ->name('reviews.index');

                    Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])
                        ->name('reviews.approve');

                    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])
                        ->name('reviews.destroy');
                });
            });
        });
});
