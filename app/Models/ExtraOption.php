<?php

namespace App\Models;

use Database\Factories\ExtraOptionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\ExtraOption
 *
 * @property string $id
 * @property string $mensa_id
 * @property int $order
 * @property string $description
 * @property string $price
 * @property-read Mensa $mensa
 * @property-read Collection|Signup[] $signups
 * @property-read int|null $signups_count
 *
 * @method static ExtraOptionFactory factory(...$parameters)
 * @method static Builder|ExtraOption newModelQuery()
 * @method static Builder|ExtraOption newQuery()
 * @method static Builder|ExtraOption query()
 * @method static Builder|ExtraOption whereDescription($value)
 * @method static Builder|ExtraOption whereId($value)
 * @method static Builder|ExtraOption whereMensaId($value)
 * @method static Builder|ExtraOption whereOrder($value)
 * @method static Builder|ExtraOption wherePrice($value)
 *
 * @mixin Eloquent
 */
class ExtraOption extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function mensa(): BelongsTo
    {
        return $this->belongsTo(Mensa::class);
    }

    public function signups(): BelongsToMany
    {
        return $this->belongsToMany(Signup::class, 'signup_extra_options', 'extra_option_id', 'signup_id');
    }
}
