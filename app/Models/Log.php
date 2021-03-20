<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereLidnummer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $mensa_id
 * @property string|null $lidnummer
 * @property string $description
 * @property-read \App\Models\Mensa $mensa
 * @property-read \App\Models\User|null $user
 */
class Log extends Model
{
    public function mensa(){
        return $this->belongsTo('App\Models\Mensa', 'mensa_id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'lidnummer');
    }
}
