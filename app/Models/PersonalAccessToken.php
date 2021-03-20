<?php


namespace App\Models;


class PersonalAccessToken extends \Laravel\Sanctum\PersonalAccessToken {
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'id' => 'string'
    ];
}