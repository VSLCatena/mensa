<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @method static \Database\Factories\ExtraOptionFactory factory(...$parameters)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signup[] $signups
 * @property-read int|null $signups_count
 */
class ExtraOption extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    public function mensa(): BelongsTo {
        return $this->belongsTo(Mensa::class);
    }

    public function signups(): BelongsToMany {
        return $this->belongsToMany(Signup::class, 'signup_extra_options', 'extra_option_id', 'signup_id');
    }
}
