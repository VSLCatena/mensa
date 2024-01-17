<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public $timestamps = false;

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }
}
