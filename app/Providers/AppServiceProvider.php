<?php

namespace App\Providers;

use App\Contracts\RemoteUserLookup;
use App\Models\PersonalAccessToken;
use App\Services\AzureUserLookup;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        $this->app->bind(RemoteUserLookup::class, AzureUserLookup::class);

        \URL::forceScheme('https');
        setlocale(LC_TIME, "nl_NL");
        Log::shareContext(['invocation-id' => (string) Str::uuid()]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
