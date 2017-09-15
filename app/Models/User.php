<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'lidnummer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function mensas()
    {
        return $this->belongsToMany('App\Models\Mensa', 'mensa_users', 'lidnummer', 'mensa_id')->withPivot('cooks', 'dishwasher', 'is_intro', 'allergies', 'wishes', 'confirmed', 'paid');
    }
}
