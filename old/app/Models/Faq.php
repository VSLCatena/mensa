<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public function lastEditedBy(){
        return $this->belongsTo('App\Models\User', 'last_edited_by');
    }
}
