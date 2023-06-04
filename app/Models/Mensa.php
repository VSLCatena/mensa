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
 * @property int max_signups
 * @property int $food_options
 * @property int $closed
 * @property float $price
 * @property-read Collection|ExtraOption[] $extraOptions
 * @property-read int|null $extra_options_count
 * @property-read Collection|Log[] $logs
 * @property-read int|null $logs_count
 * @property-read Collection|MenuItem[] $menuItems
 * @property-read int|null $menu_items_count
 * @property-read Collection|Signup[] $orderedSignups
 * @property-read int|null $ordered_signups_count
 * @property-read Collection|Signup[] $signups
 * @property-read int|null $signups_count
 *
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
 * @method static Builder|Mensa whereMaxSignups($value)
 * @method static Builder|Mensa wherePrice($value)
 * @method static Builder|Mensa whereTitle($value)
 * @method static Builder|Mensa whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Mensa extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'title', 'description', 'date', 'closing_time', 'max_signups',
    ];

    public function signups(): HasMany
    {
        return $this->hasMany(Signup::class);
    }

    public function orderedSignups(): HasMany
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

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    public function canSignup(User $user): bool
    {
        return !$this->closed && strtotime($this->closing_time) >= time() || $user->is_admin;
    }
}
