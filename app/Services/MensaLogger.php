<?php
namespace App\Services;

use App\Models\Log;
use App\Models\Mensa;
use Illuminate\Support\Facades\Auth;

class MensaLogger {
    /**
     * Logs a given message to a certain Mensa.
     *
     * Automatically associates the current logged in user to it if any.
     *
     * @param Mensa $mensa
     * @param string $description
     */
    public function log(Mensa $mensa, string $description){
        $log = new Log();
        if(Auth::check()){
            $log->user()->associate(Auth::user());
        }
        $log->mensa()->associate($mensa);
        $log->description = $description;
        $log->save();
    }
}