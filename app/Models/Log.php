<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property-read \App\Models\Mensa $mensa
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @mixin \Eloquent
 */
class Log extends Model
{

    protected $keyType = 'string';

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa', 'mensa_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'lidnummer');
    }
}
