<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\MensaUserItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|MensaUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereConfirmationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereCooks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereDishwasher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereExtraInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereIsIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereLidnummer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereMensaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereVegetarian($value)
 * @method static \Illuminate\Database\Query\Builder|MensaUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MensaUser withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $lidnummer
 * @property int $mensa_id
 * @property int $cooks
 * @property int $dishwasher
 * @property int $is_intro
 * @property string|null $allergies
 * @property string|null $extra_info
 * @property int $confirmed
 * @property string $paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $confirmation_code
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $vegetarian
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MensaExtraOption[] $extraOptions
 * @property-read int|null $extra_options_count
 * @property-read \App\Models\Mensa $mensa
 * @property-read \App\Models\User $user
 * @property string $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|MensaUser whereUserId($value)
 */
class MensaUser extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function extraOptions(){
        return $this->belongsToMany('App\Models\MensaExtraOption', 'mensa_user_extra_options', 'mensa_user_id', 'mensa_extra_option_id');
    }

    public function mensa(){
        return $this->belongsTo('App\Models\Mensa');
    }

//    public function intros(){
//        return $this->hasMany('App\Models\MensaUserItem', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '1');
//    }
//
//    public function mainUser(){
//        return $this->hasOne('App\Models\MensaUserItem', 'lidnummer', 'lidnummer')->where('mensa_id', $this->mensa->id)->where('is_intro', '0');
//    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'lidnummer');
    }

    public function price(){
        return $this->extraOptions->sum('price') + $this->mensa->price;
    }

    public function consumptions(){
        return $this->mensa->consumptions($this->cooks, $this->dishwasher);
    }

    public function isStaff(){
        return $this->mensa->staff()->contains('id', $this->id);
    }
}
