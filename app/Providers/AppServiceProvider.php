<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        \URL::forceScheme('https');
        setlocale(LC_TIME, "nl_NL");

        // Register our own @admin blade if to simplify things
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->mensa_admin;
        });
        Blade::if('notadmin', function () {
            return !auth()->check() || !auth()->user()->mensa_admin;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
