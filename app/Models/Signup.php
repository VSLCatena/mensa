<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Signup
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $cooks
 * @property int $dishwasher
 * @property int $vegetarian
 * @property int $is_intro
 * @property string $allergies
 * @property string $extra_info
 * @property int $confirmed
 * @property float $paid
 * @property string $confirmation_code
 * @property string $user_id
 * @property string $mensa_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExtraOption[] $extraOptions
 * @property-read int|null $extra_options_count
 * @property-read \App\Models\Mensa $mensa
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Signup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Signup newQuery()
 * @method static \Illuminate\Database\Query\Builder|Signup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Signup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereCooks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereDishwasher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereIsIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Signup whereVegetarian($value)
 * @method static \Illuminate\Database\Query\Builder|Signup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Signup withoutTrashed()
 * @mixin \Eloquent
 * @method static \Database\Factories\SignupFactory factory(...$parameters)
 */
class Signup extends Model
{
    use SoftDeletes, HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'cooks', 'dishwasher', 'vegetarian', 'is_intro', 'allergies', 'extra_info', 'confirmed', 'confirmation_code', 'user_id', 'mensa_id'];

    protected $dates = ['deleted_at'];

    public function extraOptions(): BelongsToMany {
        return $this->belongsToMany(ExtraOption::class, 'signup_extra_options', 'signup_id', 'extra_option_id');
    }

    public function mensa(): BelongsTo {
        return $this->belongsTo(Mensa::class);
    }

//    public function intros(){
//        return $this->hasMany('App\Models\MensaUserItem', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '1');
//    }
//
//    public function mainUser(){
//        return $this->hasOne('App\Models\MensaUserItem', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '0');
//    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function price(): float {
        return $this->extraOptions->sum('price') + $this->mensa->price;
    }

//    public function consumptions() {
//        return $this->mensa->consumptions($this->cooks, $this->dishwasher);
//    }

//    public function isStaff(){
//        return $this->mensa->staff()->contains('id', $this->id);
//    }
}
