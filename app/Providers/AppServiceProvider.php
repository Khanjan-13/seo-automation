<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;

use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();

        // Provide $chats to the normal sidebar component so it never errors
        View::composer('components.normal.sidebar', function ($view) {
            $chats = collect();
            $user = Auth::guard('normaluser')->user();
            if ($user) {
                $chats = Chat::where('user_id', $user->id)->latest()->get();
            }
            $view->with('chats', $chats);
        });
    }
}
