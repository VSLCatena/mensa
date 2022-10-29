<?php

namespace App\Models;

use Database\Factories\MensaFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Traits\Observable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * App\Models\Mensa
 *
 * @property string $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $title
 * @property string $description
 * @property int $date
 * @property int $closing_time
 * @property int $max_users
 * @property int $food_options
 * @property int $closed
 * @property float $price
 * @property-read Collection|ExtraOption[] $extraOptions
 * @property-read int|null $extra_options_count
 * @property-read Collection|Log[] $logs
 * @property-read int|null $logs_count
 * @property-read Collection|MenuItem[] $menuItems
 * @property-read int|null $menu_items_count
 * @property-read Collection|Signup[] $orderedUsers
 * @property-read int|null $ordered_users_count
 * @property-read Collection|Signup[] $users
 * @property-read int|null $users_count
 * @method static MensaFactory factory(...$parameters)
 * @method static Builder|Mensa newModelQuery()
 * @method static Builder|Mensa newQuery()
 * @method static Builder|Mensa query()
 * @method static Builder|Mensa whereClosed($value)
 * @method static Builder|Mensa whereClosingTime($value)
 * @method static Builder|Mensa whereCreatedAt($value)
 * @method static Builder|Mensa whereDate($value)
 * @method static Builder|Mensa whereDeletedAt($value)
 * @method static Builder|Mensa whereDescription($value)
 * @method static Builder|Mensa whereFoodOptions($value)
 * @method static Builder|Mensa whereId($value)
 * @method static Builder|Mensa whereMaxUsers($value)
 * @method static Builder|Mensa wherePrice($value)
 * @method static Builder|Mensa whereTitle($value)
 * @method static Builder|Mensa whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Mensa extends Model
{
    use HasFactory, HasUuids, Observable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'title', 'description', 'date', 'closing_time', 'max_users',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(Signup::class);
    }

    public function orderedUsers(): HasMany
    {
        return $this->hasMany(Signup::class)
            ->select(DB::raw('*, mensa_users.extra_info as extra_info, mensa_users.allergies as allergies, mensa_users.food_option as food_option, mensa_users.created_at as created_at, mensa_users.updated_at as updated_at'))
            ->join('users', 'users.id', '=', 'signups.id')
            ->orderBy('cooks', 'DESC')
            ->orderBy('dishwasher', 'DESC')
            ->orderBy('users.name')
            ->orderBy('signups.is_intro');
    }

    public function extraOptions(): HasMany
    {
        return $this->hasMany(ExtraOption::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function isClosed(): bool
    {
        return $this->closed || strtotime($this->closing_time) < time();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
    
    public function log()
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
