<?php

namespace App\Traits;


use App\Models\Log;
use App\Models\Mensa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait Logger
{
    /**
     * @param $mensa Mensa
     * @param $description String
     */
    public function log($mensa, $description){
        $log = new Log();
        if(Auth::check()){
            $log->user()->associate(Auth::user());
        }
        $log->mensa()->associate($mensa);
        $log->description = $description;
        $log->save();
    }
}