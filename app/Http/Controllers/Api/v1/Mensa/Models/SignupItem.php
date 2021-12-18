<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SignupItem
{

    /**
     * SimpleUserItem constructor.
     * @param string $id
     * @param SimpleUserItem $user
     * @param string $allergies
     * @param string $extraInfo
     * @param int $food_option
     * @param bool $cooks
     * @param bool $dishwasher
     */
    public function __construct(
        public string $id,
        public SimpleUserItem $user,
        public string $allergies,
        public string $extraInfo,
        public int $food_option,
        public bool $cooks,
        public bool $dishwasher
    )
    {
    }
}