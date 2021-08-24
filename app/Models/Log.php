<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Log
 *
 * @property-read \App\Models\Mensa $mensa
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @mixin \Eloquent
 * @method static \Database\Factories\LogFactory factory(...$parameters)
 */
class Log extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    public function mensa(): BelongsTo {
        return $this->belongsTo(Mensa::class, 'mensa_id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
