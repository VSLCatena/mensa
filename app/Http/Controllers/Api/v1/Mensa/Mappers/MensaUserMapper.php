<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserItem;
use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserItem;
use App\Models\MensaUser;
use App\Models\User;

trait MensaUserMapper {
    use UserMapper;
    /**
     * @param User $user
     * @return SimpleUserItem
     */
    function mapMensaUser(User $user, MensaUser $mensaUser): MensaUserItem {
        return new MensaUserItem(
            user: self::mapUser($user),
            email: $user->email,
            allergies: $mensaUser->allergies,
            extraInfo: $mensaUser->extra_info,
            vegetarian: $mensaUser->vegetarian,
            cooks: $mensaUser->cooks,
            dishwasher: $mensaUser->dishwasher
        );
    }
}
