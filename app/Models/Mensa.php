<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mensa extends Model
{
    private $dishwashers = null;
    private $cooks = null;
    private $defaultBudget = [null, null];
    private $budget = [null, null];
    private $payingUsers = null;

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

    public function budget($raw = false){
        if($this->budget[$raw?0:1] === null){
            // This query grabs all the users' extra options that are not cooking and are not washing dishes
            // and then summarize it
            $extra_options = DB::select('SELECT SUM(extra.price) as budget FROM mensa_users AS m_users
LEFT JOIN mensa_user_extra_options AS users_extra ON users_extra.mensa_user_id=m_users.id
LEFT JOIN mensa_extra_options AS extra ON extra.id=users_extra.mensa_extra_option_id
WHERE m_users.mensa_id=? AND extra.mensa_id=? AND m_users.cooks=0 AND m_users.dishwasher=0 AND m_users.deleted_at IS NULL', [$this->id, $this->id]);
            $budget = $extra_options[0]->budget;

            // We add the default budget that you
            $budget += $this->defaultBudget($raw);

            // Save it for possible later use
            $this->budget[$raw?0:1] = $budget;
        }

        return $this->budget[$raw?0:1];
    }

    public function maxDishwashers(){
        return $this->payingUsers() < env('MENSA_SECOND_DISHWASHER') ? 1 : 2;

    }

    public function defaultBudget($raw = false){
        if($this->defaultBudget[$raw?0:1] === null) {


            if(!$raw){
                $this->defaultBudget[1] = $this->payingUsers() * $this->defaultBudgetPerPayingUser();
            } else {
                $this->defaultBudget[0] = $this->payingUsers() * $this->price;
            }

        }

        return $this->defaultBudget[$raw?0:1];
    }

    public function defaultBudgetPerPayingUser(){
        // We subtract the amount that goes to the kitchen
        // And we subtract the amount that goes to the dishwashers
        // Both can be defined in the .env file
        return $this->price - (env('MENSA_SUBTRACT_KITCHEN', 0.30) + env('MENSA_SUBTRACT_DISHWASHER', 0.50));
    }

    public function consumptions($isCook, $isDishwasher){
        $consumptions = 0;
        if($isCook){
            // Cooks get a base consumption amount
            $cookConsumptions = env('MENSA_CONSUMPTIONS_COOK_BASE');
            // And then per X users you get an extra one
            $cookConsumptions += floor($this->payingUsers() / env('MENSA_CONSUMPTIONS_COOK_1_PER_X_GUESTS'));
            // But we are limited to a maximum though
            $consumptions += min($cookConsumptions, env('MENSA_CONSUMPTIONS_COOK_MAX'));
        }

        if($isDishwasher){
            // Per X users you get an extra consumption
            $dishwasherConsumptions = floor($this->payingUsers() / env('MENSA_CONSUMPTIONS_DISHWASHER_SPLIT_1_PER_X_GUESTS'));
            // But we do split it over all dishwashers
            $dishwasherConsumptions = floor($dishwasherConsumptions / max($this->dishwashers(), $this->maxDishwashers()));
            // Dishwashers do get a base consumption amount
            $dishwasherConsumptions += env('MENSA_CONSUMPTIONS_DISHWASHER_BASE');
            // But we are limited to a maximum though
            $consumptions += min($dishwasherConsumptions, env('MENSA_CONSUMPTIONS_DISHWASHER_MAX'));
        }

        return $consumptions;
    }

    public function payingUsers(){
        if($this->payingUsers === null) {
            // We grab the amount of users that actually has to pay the normal price
            $payingUsers = $this->users()->where('cooks', '0')->where('dishwasher', '0')->count();

            // We want to take into account that if there are no cooks, we won't have the budget for it
            if ($this->users()->where('cooks', '1')->count() < 1) {
                $payingUsers--;
            }

            // And same with dishwashers, if we don't have any dishwashers yet, we won't have the budget for it
            if ($this->dishwashers() < 1) {
                $payingUsers--;
            }

            if($this->dishwashers() < 2 && $this->users()->count() >= env("MENSA_SECOND_DISHWASHER")){
                $payingUsers--;
            }

            $this->payingUsers = max(0, $payingUsers);
        }

        return $this->payingUsers;
    }

    public function jsonPrices(){
        $prices = [['description' => '', 'price' => $this->price]];
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
