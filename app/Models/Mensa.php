<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensa extends Model
{
    private $dishwashers = null;
    private $cooks = null;

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'mensa_users', 'mensa_id', 'lidnummer')
            ->using('App\Models\MensaUser');
    }

    public function dishwashers(){
        if($this->dishwashers === null)
            $this->dishwashers = $this->users()->where('dishwasher', '1')->count();

        return $this->dishwashers;
    }

    public function prices(){
        return $this->hasMany('App\Models\MensaPrice');
    }

    public function cooks(){
        // If we cached it before, show that instead
        if($this->cooks !== null)
            return $this->cooks;

        $cooks = $this->users()->wherePivot('cooks', '1')->get();
        $ret = '';

        // If there is noone that will cook, we just return an empty string
        if(count($cooks) < 1){
            $this->cooks = ''; // Make sure to update 'cooks' for in the future!
            return '';
        }

        // Now we loop over all cooks and append the name to the return variable, seperated by commas.
        foreach($cooks as $cook){
            $ret .= $cook->name . ', ';
        }

        // Now we strip the last comma from the return variabele
        $ret = substr($ret, 0, -2);

        // Now we look for the latest comma
        $pos = strrpos($ret, ',');
        if($pos !== false) {
            // If we can find one we replace it with 'en'
            $ret = substr_replace($ret, ' en', $pos, 1);
        }

        // Update the cache
        $this->cooks = $ret;

        // And return
        return $ret;
    }

    public function closingTime($addEnter = false){
        $closingTime = strtotime($this->closing_time);
        $date = date('Y-m-d', strtotime($this->date));
        $cDate = date('Y-m-d', $closingTime);

        // If closing_date is the same as date, we just show the time.
        if($date == $cDate){
            return date('H:i', $closingTime);
        }

        return formatDate($this->closing_time, $addEnter);
    }
}
