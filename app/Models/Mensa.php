<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mensa extends Model
{
    private $dishwashers = null;
    private $cooks = null;
    private $budget = null;

    public function users()
    {
        return $this->hasMany('App\Models\MensaUser');
    }

    public function dishwashers(){
        if($this->dishwashers === null)
            $this->dishwashers = $this->users()->where('dishwasher', '1')->count();

        return $this->dishwashers;
    }

    public function extraOptions(){
        return $this->hasMany('App\Models\MensaExtraOption');
    }

    public function budget(){
        if($this->budget === null){
            // This query grabs all the users' extra options that are not cooking and are not washing dishes
            // and then summarize it
            $extra_options = DB::select('SELECT SUM(extra.price) as budget FROM mensa_users AS m_users
LEFT JOIN mensa_user_extra_options AS users_extra ON users_extra.mensa_user_id=m_users.id
LEFT JOIN mensa_extra_options AS extra ON extra.id=users_extra.mensa_extra_option_id
WHERE m_users.mensa_id=? AND m_users.cooks=0 AND m_users.dishwasher=0', [$this->id]);
            $budget = $extra_options[0]->budget;

            // Then we grab the amount of users that actually has to pay the normal price
            $paying_users = $this->users->where('cooks', '0')->where('dishwasher', '0')->count();
            // We add the amount that users have to pay for the mensa
            // But we subtract the amount that goes to the kitchen (which is 30 cents)
            // And we subtract the amount that goes to the dishwashers (which is 50 cents)
            $budget += $paying_users * ($this->price - (0.30 + 0.50));

            // Save it for possible later use
            $this->budget = $budget;
        }

        return $this->budget;
    }

    public function jsonPrices(){
        $prices = [['description' => '', 'price' => 3.50]];
        return array_merge($prices, $this->extraOptions()->get()->toArray());
    }

    public function cooks(){
        // If we cached it before, show that instead
        if($this->cooks !== null)
            return $this->cooks;

        $cooks = $this->users()->where('cooks', '1')->get();
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
