<?php

namespace App\Listeners;

use App\Traits\LdapHelpers;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class CookieLoginListener
{
    use LdapHelpers;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Grab the ldap info by description
        // We don't want a service user to login with a cookie so we block that
        if(($event->remember || !$event->user->service_user) && !$this->getLdapUserBy('description', $event->user->lidnummer)){
            // If for some reason the user couldn't be found, we log out.
            Auth::logout();
        }
    }
}
