<?php

namespace App\Models;

use Database\Factories\MenuItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
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
 * @property-read Mensa $mensa
 *
 * @method static MenuItemFactory factory(...$parameters)
 * @method static Builder|MenuItem newModelQuery()
 * @method static Builder|MenuItem newQuery()
 * @method static Builder|MenuItem query()
 * @method static Builder|MenuItem whereCreatedAt($value)
 * @method static Builder|MenuItem whereId($value)
 * @method static Builder|MenuItem whereMensaId($value)
 * @method static Builder|MenuItem whereOrder($value)
 * @method static Builder|MenuItem whereText($value)
 * @method static Builder|MenuItem whereUpdatedAt($value)
 *
 * @mixin Eloquent
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
