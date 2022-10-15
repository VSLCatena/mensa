<?php

namespace App\Models;

use Database\Factories\LogFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Log
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $category
 * @property string $text
 * @property string $user_id
 * @property string $object_id
 * @property-read Mensa $mensa
 * @property-read User $user
 * @method static LogFactory factory(...$parameters)
 * @method static Builder|Log newModelQuery()
 * @method static Builder|Log newQuery()
 * @method static Builder|Log query()
 * @method static Builder|Log whereCreatedAt($value)
 * @method static Builder|Log whereCategory($value)
 * @method static Builder|Log whereId($value)
 * @method static Builder|Log whereObjectId($value)
 * @method static Builder|Log whereText($value)
 * @method static Builder|Log whereUpdatedAt($value)
 * @method static Builder|Log whereUserId($value)
 * @mixin Eloquent
 */
class Log extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'object_id', 'user_id', 'category', 'text',
    ];    
    
    public function mensa(): BelongsTo
    {
        return $this->belongsTo(Mensa::class, 'object_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
