<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensa extends Model
{
    private $dishwashers = null;
    private $cooks = null;

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'mensa_users', 'mensa_id', 'lidnummer')->withTimestamps()->withPivot('cooks', 'dishwasher', 'is_intro', 'allergies', 'wishes', 'confirmed', 'paid');
    }

    public function dishwashers(){
        if($this->dishwashers === null)
            $this->dishwashers = $this->users()->wherePivot('dishwasher', '1')->count();

        return $this->dishwashers;
    }

    public function cooks(){
        if($this->cooks !== null){
            return $this->cooks;
        }

        $cooks = $this->users()->wherePivot('cooks', '1')->get();
        $ret = '';

        if(count($cooks) < 1){
            return $ret;
        }

        foreach($cooks as $cook){
            $ret .= $cook->name . ', ';
        }

        $ret = substr($ret, 0, -2);

        $pos = strrpos($ret, ',');
        if($pos !== false) {
            $ret = substr_replace($ret, ' en', $pos, 1);
        }

        $this->cooks = $ret;

        return $ret;
    }

    public function closingTime($addEnter = false){
        $closingTime = strtotime($this->closing_time);
        $date = date('Y-m-d', strtotime($this->date));
        $cDate = date('Y-m-d', $closingTime);
        if($date == $cDate){
            return date('H:i', $closingTime);
        }

        return formatDate($this->closing_time, $addEnter);
    }
}
