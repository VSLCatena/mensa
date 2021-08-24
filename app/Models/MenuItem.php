<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @method static \Database\Factories\MenuItemFactory factory(...$parameters)
 */
class MenuItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $keyType = 'string';
    public $incrementing = false;

    public function mensa(): BelongsTo {
        return $this->belongsTo(Mensa::class);
    }
}
