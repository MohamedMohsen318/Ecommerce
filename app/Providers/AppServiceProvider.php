<?php

namespace App\Providers;

use App\Events\OrderStatusChanged;
use App\Listeners\NotifyCustomerOfStatusChange;
use App\Listeners\SendOrderStatusChangedNotification;
use App\Models\Category;
use App\Models\ProductComment;
use App\Policies\CommentPolicy;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );

        Event::listen(
            OrderStatusChanged::class,
            NotifyCustomerOfStatusChange::class
        );

        Event::listen(
            OrderStatusChanged::class,
            SendOrderStatusChangedNotification::class
        );

        Gate::policy(
            ProductComment::class,
            CommentPolicy::class
        );

        View::composer('partials.user-nav', function ($view) {
            $view->with('categoryTree', Category::tree());
        });

        VerifyEmail::createUrlUsing(function ($notifiable): string {
            if (!app()->runningInConsole() && request()) {
                URL::forceRootUrl(
                    request()->getSchemeAndHttpHost()
                );
            }

            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(
                    Config::get('auth.verification.expire', 60)
                ),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1(
                        $notifiable->getEmailForVerification()
                    ),
                ]
            );
        });
    }
}
