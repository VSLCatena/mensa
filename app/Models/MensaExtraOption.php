<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensaExtraOption extends Model
{
    public $timestamps = false;

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }

    public function users(){
        return $this->hasManyThrough('App\Models\User','App\Models\MensaUser');
    }
}
