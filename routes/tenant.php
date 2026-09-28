<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PasswordResetController as AdminPasswordResetController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Home
    Route::view('/', 'welcome')->name('home');

    // Locale
    Route::post('/locale', function (Request $request) {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:ar,en'],
        ]);

        // admin pages keep their own locale in the session
        $refererPath = parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH) ?? '';
        $sessionKey = str_contains($refererPath, '/admin') ? 'admin_locale' : 'locale';

        session([$sessionKey => $validated['locale']]);
        app()->setLocale($validated['locale']);

        return back();
    })->name('locale.update');

    // Guest only: login, register, password reset
    Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register.create');
        Route::post('/register', [AuthController::class, 'register'])->name('register.store');

        Route::get('/login', [AuthController::class, 'showLogin'])->name('login.create');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');

        Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });

    // Email verification
    Route::middleware('auth')->group(function () {
        Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');

        Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
            ->middleware('throttle:1,1')
            ->name('verification.send');
    });

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['auth', 'signed'])
        ->name('verification.verify');

    // Shop
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{item}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/deals', [DealsController::class, 'index'])->name('deals.index');

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/', [CartController::class, 'store'])->name('store');
        Route::put('/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cartItem}', [CartController::class, 'destroy'])->name('destroy');
    });

    // Logged in customers
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('index');
            Route::post('/', [AddressController::class, 'store'])->name('store');
            Route::patch('/{address}', [AddressController::class, 'update'])->name('update');
            Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/{item}/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        });

        Route::post('/items/{item}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

        Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');
        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::patch('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        });
    });

    // Logged in and verified customers
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware('guest:admins')->group(function () {
            Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login.create');
            Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');

            Route::get('/forgot-password', [AdminPasswordResetController::class, 'showRequestForm'])->name('password.request');
            Route::post('/forgot-password', [AdminPasswordResetController::class, 'sendResetLink'])->name('password.email');
            Route::get('/reset-password/{token}', [AdminPasswordResetController::class, 'showResetForm'])->name('password.reset');
            Route::post('/reset-password', [AdminPasswordResetController::class, 'reset'])->name('password.update');
        });

        Route::middleware('auth:admins')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->middleware('permission:view_dashboard,admins')
                ->name('dashboard.index');

            Route::middleware('permission:manage_admins,admins')->group(function () {
                Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
                Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
                Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
                Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
                Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
                Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
            });

            Route::middleware('permission:manage_categories,admins')->group(function () {
                Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
                Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
                Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
                Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
                Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
                Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            });

            Route::middleware('permission:manage_items,admins')->group(function () {
                Route::get('/items/{item}/variants', [ItemController::class, 'editVariants'])->name('items.variants.edit');
                Route::put('/items/{item}/variants', [ItemController::class, 'updateVariants'])->name('items.variants.update');

                Route::get('/items', [ItemController::class, 'index'])->name('items.index');
                Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
                Route::post('/items', [ItemController::class, 'store'])->name('items.store');
                Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
                Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
                Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
            });

            Route::middleware('permission:manage_discounts,admins')->group(function () {
                Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
                Route::get('/discounts/create', [DiscountController::class, 'create'])->name('discounts.create');
                Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store');
                Route::get('/discounts/{discount}/edit', [DiscountController::class, 'edit'])->name('discounts.edit');
                Route::put('/discounts/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
                Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
            });

            Route::middleware('permission:manage_flash_sales,admins')->group(function () {
                Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
                Route::get('/flash-sales/create', [FlashSaleController::class, 'create'])->name('flash-sales.create');
                Route::post('/flash-sales', [FlashSaleController::class, 'store'])->name('flash-sales.store');
                Route::get('/flash-sales/{flashSale}/edit', [FlashSaleController::class, 'edit'])->name('flash-sales.edit');
                Route::put('/flash-sales/{flashSale}', [FlashSaleController::class, 'update'])->name('flash-sales.update');
                Route::delete('/flash-sales/{flashSale}', [FlashSaleController::class, 'destroy'])->name('flash-sales.destroy');
            });

            Route::middleware('permission:manage_orders,admins')->group(function () {
                Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
                Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
                Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
            });

            Route::middleware('permission:manage_reviews,admins')->group(function () {
                Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
                Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
                Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
            });

            Route::middleware('permission:manage_comments,admins')->group(function () {
                Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
                Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
            });
        });
    });
});
