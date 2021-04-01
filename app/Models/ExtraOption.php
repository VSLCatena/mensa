<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExtraOption
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExtraOption wherePrice($value)
 * @mixin \Eloquent
 * @property int $id
 * @property int $mensa_id
 * @property string $description
 * @property string $price
 * @property-read \App\Models\Mensa $mensa
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signup[] $users
 * @property-read int|null $users_count
 */
class ExtraOption extends Model
{

    protected $keyType = 'string';

    public $timestamps = false;

    public function mensa() {
        return $this->belongsTo('App\Models\Mensa');
    }

    public function users() {
        return $this->belongsToMany('App\Models\Signup', 'mensa_user_extra_options', 'mensa_extra_option_id', 'mensa_user_id');
    }
}
