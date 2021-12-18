<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\CookieLoginListener',
        ],
        'Illuminate\Auth\Events\Authenticated' => [
            'App\Listeners\ServiceUserIPWhitelistListener',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            // ... other providers
            'App\\Http\\Socialite\\SocialiteExtender@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
