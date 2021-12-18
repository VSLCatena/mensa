<?php

namespace App\Models;

use Database\Factories\LogFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Log
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $text
 * @property string $user_id
 * @property string $mensa_id
 * @property-read Mensa $mensa
 * @property-read User $user
 * @method static LogFactory factory(...$parameters)
 * @method static Builder|Log newModelQuery()
 * @method static Builder|Log newQuery()
 * @method static Builder|Log query()
 * @method static Builder|Log whereCreatedAt($value)
 * @method static Builder|Log whereId($value)
 * @method static Builder|Log whereMensaId($value)
 * @method static Builder|Log whereText($value)
 * @method static Builder|Log whereUpdatedAt($value)
 * @method static Builder|Log whereUserId($value)
 * @mixin Eloquent
 */
class Log extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    public function mensa(): BelongsTo
    {
        return $this->belongsTo(Mensa::class, 'mensa_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
