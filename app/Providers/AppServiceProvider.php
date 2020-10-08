<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    final public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    final public function boot(): void
    {
        setlocale(LC_TIME, 'ru_RU.utf-8');
        View::composer(['layouts.mail', 'mails.*'], function($view) {
            $view->with(['adminUser' => User::where('user_role_id', 1)->first()]);
        });
    }
}
