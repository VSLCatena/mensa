<?php

namespace App\Http\Controllers\Api\Mensa\Mappers;

use App\Http\Controllers\Api\Mensa\Models\MensaItem;
use App\Http\Controllers\Api\Shared\Mappers\ExtraOptionsMapper;
use App\Http\Controllers\Api\Shared\Mappers\UserMapper;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;

trait MensaMapper {
    use ExtraOptionsMapper, UserMapper;

    /**
     * @param Mensa $mensa
     * @param MensaUser[] $users
     * @param MensaExtraOption[] $options
     * @param bool $usersAsObject
     * @param bool $dishwashersAsObject
     * @return MensaItem
     */
    function mapMensa(Mensa $mensa, array $users, array $options, bool $usersAsObject = false, bool $dishwashersAsObject = false) {
        $dishwashers = array_filter($users, function ($user) { return $user->dishwasher; });
        $cooks = array_filter($users, function($user) { return $user->cooks; });

        $userMapper = function($user) { return self::mapUser($user); };

        $usersVal = $usersAsObject ? array_map($userMapper, $users) : count($users);
        $dishwashersVal = $dishwashersAsObject ? array_map($userMapper, $dishwashers) : count($dishwashers);

        return new MensaItem(
            id: $mensa->id,
            title: $mensa->title,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            users: $usersVal,
            maxUsers: $mensa->max_users,
            isClosed: $mensa->isClosed(),
            dishwashers: $dishwashersVal,
            cooks: array_map($userMapper, $cooks),
            extraOptions: array_map(function ($option) { return self::mapExtraOptions($option); }, $options),
        );
    }
}
