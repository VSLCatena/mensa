<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensa extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'mensa_users', 'mensa_id', 'lidnummer')->withPivot('cooks', 'dishwasher', 'is_intro', 'allergies', 'wishes', 'confirmed', 'paid');
    }
}
