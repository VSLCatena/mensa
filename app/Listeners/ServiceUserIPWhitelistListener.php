<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ServiceUserIPWhitelistListener
{
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
        // If the user is a service user, and isn't in the list of whitelisted IPs,
        // then we just want to immediately log out
        if($event->user->service_user && !in_array(Request::ip(), config('mensa.service_users.whitelisted_ips'))){
            Auth::logout();
        }
    }
}
