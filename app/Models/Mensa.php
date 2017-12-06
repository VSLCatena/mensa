<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Mensa extends Model
{
    private $staff = null;
    private $dishwashers = null;
    private $cooks = null;
    private $cooksFormatted = null;
    private $defaultBudget = [null, null];
    private $budget = [null, null];
    private $payingUsers = null;

    public function users($order = false)
    {
        if($order) {
            return $this->hasMany('App\Models\MensaUser')
                ->select(DB::raw('*, mensa_users.extra_info as extra_info, mensa_users.allergies as allergies, mensa_users.vegetarian as vegetarian'))
                ->join('users', 'users.lidnummer', '=', 'mensa_users.lidnummer')
                ->orderBy('cooks', 'DESC')
                ->orderBy('dishwasher', 'DESC')
                ->orderBy('users.name')
                ->orderBy('mensa_users.is_intro');
        } else {
            return $this->hasMany('App\Models\MensaUser');
        }
    }

    public function dishwashers(){
        if($this->dishwashers === null) {
            $this->dishwashers = $this->users()->where('dishwasher', '1')->get();
        }
        return $this->dishwashers;
    }

    public function cooks($order = false){
        if($this->cooks === null) {
            $this->cooks = $this->users($order)->where('cooks', '1')->get();
        }
        return $this->cooks;
    }

    public function extraOptions(){
        return $this->hasMany('App\Models\MensaExtraOption');
    }

    public function budget($raw = false){
        if($this->budget[$raw?0:1] === null){

            // We start with the default budget
            $budget = $this->defaultBudget($raw);

            // Then we grab all the users extra options and summarise the price of it
            $extra_options = DB::select('SELECT SUM(extra.price) as budget FROM mensa_users AS m_users
LEFT JOIN mensa_user_extra_options AS users_extra ON users_extra.mensa_user_id=m_users.id
LEFT JOIN mensa_extra_options AS extra ON extra.id=users_extra.mensa_extra_option_id
WHERE m_users.mensa_id=? AND extra.mensa_id=? AND m_users.deleted_at IS NULL', [$this->id, $this->id]);
            $budget += $extra_options[0]->budget;

            // Then we subtract the extra options for each staff member
            foreach($this->staff() as $staff){
                $budget -= $staff->extraOptions->sum('price');
            }

            // Save it for possible later use
            $this->budget[$raw?0:1] = $budget;
        }

        return $this->budget[$raw?0:1];
    }

    public function maxDishwashers(){
        return $this->users()->count()-1 < env('MENSA_SECOND_DISHWASHER') ? 1 : 2;
    }

    public function maxCooks(){
        return $this->users()->count()-1 < env('MENSA_SECOND_COOK') ? 1 : 2;
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

    public function consumptions($isCook, $isDishwasher, $noExtraDishwasher = false){
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
            // But we do split it over all the dishwashers
            $dishwasherConsumptions = floor($dishwasherConsumptions / ($noExtraDishwasher?count($this->dishwashers()):$this->maxDishwashers()));
            // Dishwashers do get a base consumption amount
            $dishwasherConsumptions += env('MENSA_CONSUMPTIONS_DISHWASHER_BASE');
            // But we are limited to a maximum though
            $consumptions += min($dishwasherConsumptions, env('MENSA_CONSUMPTIONS_DISHWASHER_MAX'));
        }

        return $consumptions;
    }

    public function payingUsers(){
        if($this->payingUsers === null) {
            // We grab the amount of users
            $payingUsers = $this->users()->count();

            // And subtract the amount of staff
            $payingUsers -= $this->countStaff();
            $this->payingUsers = $payingUsers;
        }

        return $this->payingUsers;
    }

    public function countStaff(){
        $staff = $this->users()->where(function($query) {
            $query->where('cooks', '1')
                ->orWhere('dishwasher', '1');
        })->orderBy('cooks', 'DESC')
            ->orderBy('dishwasher', 'DESC')
            ->get();
        $cooks = 0;
        $dishwashers = 0;

        $maxCooks = $this->maxCooks();
        $maxDishwashers = $this->maxDishwashers();

        $actualStaff = 0;

        foreach($staff as $user){
            $hasSubtracted = false;
            if($user->cooks){
                $cooks++;
                if($cooks <= $maxCooks){
                    $actualStaff++;
                    $hasSubtracted = true;
                }
            }
            if($user->dishwasher) {
                $dishwashers++;
                if ($dishwashers <= $maxDishwashers && !$hasSubtracted) {
                    $actualStaff++;
                }
            }
        }

        // We subtract the amount of staff spots we haven't filled in yet.
        $actualStaff += ($cooks < 1) ? 1 : 0;
        $actualStaff += $maxDishwashers-$dishwashers;

        return $actualStaff;
    }

    public function staff(){
        if($this->staff === null) {
            $staff = $this->users()->where(function($query) {
                $query->where('cooks', '1')
                    ->orWhere('dishwasher', '1');
            })->orderBy('cooks', 'DESC')
                ->orderBy('dishwasher', 'DESC')
            ->get();

            $cooks = 0;
            $dishwashers = 0;

            $maxCooks = $this->maxCooks();
            $maxDishwashers = $this->maxDishwashers();

            $actualStaff = [];

            foreach($staff as $user){
                $hasSubtracted = false;
                if($user->cooks){
                    $cooks++;
                    if($cooks <= $maxCooks){
                        $actualStaff[] = $user;
                        $hasSubtracted = true;
                    }
                }
                if($user->dishwasher) {
                    $dishwashers++;
                    if ($dishwashers <= $maxDishwashers && !$hasSubtracted) {
                        $actualStaff[] = $user;
                    }
                }
            }

            $this->staff = Collection::make($actualStaff);
        }

        return $this->staff;
    }

    public function jsonPrices(){
        $prices = [['description' => '', 'price' => $this->price]];
        return array_merge($prices, $this->extraOptions()->get()->toArray());
    }

    public function cooksFormatted(){
        // If we cached it before, show that instead
        if($this->cooksFormatted !== null)
            return $this->cooksFormatted;

        $cooks = $this->cooks(true);
        $ret = '';

        // If there is noone that will cook, we just return an empty string
        if(count($cooks) < 1){
            $this->cooksFormatted = ''; // Make sure to update 'cooks' for in the future!
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
        $this->cooksFormatted = $ret;

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
