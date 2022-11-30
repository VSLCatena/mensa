<?php

namespace App\Models;

use Database\Factories\LogFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log as LogFacade;
/**
 * App\Models\Log
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $text
 * @property string $category
 * @property string $user_id
 * @property-read Loggable $loggable
 * @property-read User $user
 * @method static LogFactory factory(...$parameters)
 * @method static Builder|Log newModelQuery()
 * @method static Builder|Log newQuery()
 * @method static Builder|Log query()
 * @method static Builder|Log whereCreatedAt($value)
 * @method static Builder|Log whereId($value)
 * @method static Builder|Log whereObjectId($value)
 * @method static Builder|Log whereText($value)
 * @method static Builder|Log whereUpdatedAt($value)
 * @method static Builder|Log whereUserId($value)
 * @mixin Eloquent
 */
class Log extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'category', 'text', 'severity','user_id',
    ];  
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($model) {
            $attributes = $model->attributesToArray();
            LogFacade::debug($attributes);
        });
    }   
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    #One To Many (Polymorphic)
    public function Loggable()
    {
        return $this->morphTo();
    }
}
