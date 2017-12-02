<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MensaUser extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function extraOptions(){
        return $this->belongsToMany('App\Models\MensaExtraOption', 'mensa_user_extra_options', 'mensa_user_id', 'mensa_extra_option_id');
    }

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }

    public function intros(){
        return $this->hasMany('App\Models\MensaUser', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '1');
    }

    public function mainUser(){
        return $this->hasOne('App\Models\MensaUser', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '0');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'lidnummer');
    }

    public function price(){
        return $this->extraOptions->sum('price') + $this->mensa->price;
    }

    public function consumptions(){
        return $this->mensa->consumptions($this->cooks, $this->dishwasher);
    }
}
