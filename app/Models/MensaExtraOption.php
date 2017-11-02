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
        return $this->belongsToMany('App\Models\MensaUser', 'mensa_user_extra_options', 'mensa_extra_option_id', 'mensa_user_id');
    }
}
