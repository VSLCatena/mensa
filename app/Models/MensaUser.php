<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MensaUser extends Pivot
{
    public function extraOptions(){
        return $this->hasMany('App\Models\MensaPrice');
    }
}
