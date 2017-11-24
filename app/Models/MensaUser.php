<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensaUser extends Model
{
    public function extraOptions(){
        return $this->belongsToMany('App\Models\MensaExtraOption', 'mensa_user_extra_options', 'mensa_user_id', 'mensa_extra_option_id');
    }

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }

    public function intros(){
        return $this->hasMany('App\Models\MensaUser', 'lidnummer', 'lidnummer')->where('is_intro', '1');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'lidnummer');
    }

    public function price(){
        return $this->extraOptions->sum('price') + $this->mensa->price;
    }
}
