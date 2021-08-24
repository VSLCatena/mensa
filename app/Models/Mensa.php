<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Models\Mensa
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $title
 * @property string $date
 * @property string $closing_time
 * @property int $max_users
 * @property int $closed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExtraOption[] $extraOptions
 * @property-read int|null $extra_options_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Log[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MenuItem[] $menuItems
 * @property-read int|null $menu_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereClosingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereMaxUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signup[] $orderedUsers
 * @property-read int|null $ordered_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signup[] $users
 * @property-read int|null $users_count
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|Mensa whereDescription($value)
 * @method static \Database\Factories\MensaFactory factory(...$parameters)
 */
class Mensa extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'title', 'description', 'date', 'closing_time', 'max_users',
    ];

    public function users(): HasMany {
        return $this->hasMany(Signup::class);
    }

    public function orderedUsers(): HasMany {
        return $this->hasMany(Signup::class)
            ->select(DB::raw('*, mensa_users.extra_info as extra_info, mensa_users.allergies as allergies, mensa_users.vegetarian as vegetarian, mensa_users.created_at as created_at, mensa_users.updated_at as updated_at'))
            ->join('users', 'users.id', '=', 'signups.id')
            ->orderBy('cooks', 'DESC')
            ->orderBy('dishwasher', 'DESC')
            ->orderBy('users.name')
            ->orderBy('signups.is_intro');
    }

    public function extraOptions(): HasMany {
        return $this->hasMany(ExtraOption::class);
    }

    public function logs(): HasMany {
        return $this->hasMany(Log::class);
    }

    public function menuItems(): HasMany {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function isClosed(): bool {
        return $this->closed || strtotime($this->closing_time) < time();
    }

    protected static function boot() {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }
}
