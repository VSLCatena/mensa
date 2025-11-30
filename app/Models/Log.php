<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function mensa(){
        return $this->belongsTo('App\Models\Mensa', 'mensa_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
