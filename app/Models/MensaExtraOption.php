<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MensaExtraOption
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaExtraOption wherePrice($value)
 * @mixin \Eloquent
 * @property int $id
 * @property int $mensa_id
 * @property string $description
 * @property string $price
 * @property-read \App\Models\Mensa $mensa
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MensaUser[] $users
 * @property-read int|null $users_count
 */
class MensaExtraOption extends Model
{
    public $timestamps = false;

    public function mensa() {
        return $this->belongsTo('App\Models\Mensa');
    }

    public function users() {
        return $this->belongsToMany('App\Models\MensaUser', 'mensa_user_extra_options', 'mensa_extra_option_id', 'mensa_user_id');
    }
}
