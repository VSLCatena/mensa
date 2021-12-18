<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MenuItem
 *
 * @property string $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $mensa_id
 * @property string $text
 * @property int $order
 * @property-read \App\Models\Mensa $mensa
 * @method static \Database\Factories\MenuItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $keyType = 'string';
    public $incrementing = false;

    public function mensa(): BelongsTo
    {
        return $this->belongsTo(Mensa::class);
    }
}
