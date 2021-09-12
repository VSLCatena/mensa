<?php
namespace App\Http\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialiteExtender
{

    public function handle(SocialiteWasCalled $socialiteWasCalled) {
        $socialiteWasCalled->extendSocialite('azure', AzureProvider::class);
    }
}