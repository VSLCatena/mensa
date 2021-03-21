<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaUserItem {

    /**
     * SimpleUserItem constructor.
     * @param SimpleUserItem $user
     * @param string $email
     * @param string $allergies
     * @param string $extraInfo
     * @param bool $vegetarian
     * @param bool $cooks
     * @param bool $dishwasher
     */
    public function __construct(
        public SimpleUserItem $user,
        public string $email,
        public string $allergies,
        public string $extraInfo,
        public bool $vegetarian,
        public bool $cooks,
        public bool $dishwasher
    ) {
    }
}