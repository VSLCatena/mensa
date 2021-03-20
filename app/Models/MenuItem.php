<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereText($value)
 * @mixin \Eloquent
 * @property int $id
 * @property int $mensa_id
 * @property int $order
 * @property string $text
 * @property-read \App\Models\Mensa $mensa
 */
class MenuItem extends Model
{
    public $timestamps = false;

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }
}
