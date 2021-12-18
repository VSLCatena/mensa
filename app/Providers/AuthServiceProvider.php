<?php

namespace App\Providers;

use App\Models\Mensa;
use App\Models\Signup;
use App\Policies\MensaPolicy;
use App\Policies\SignupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Mensa::class => MensaPolicy::class,
        Signup::class => SignupPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
