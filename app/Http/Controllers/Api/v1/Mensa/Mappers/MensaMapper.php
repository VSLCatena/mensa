<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MensaDetailItem;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaItem;
use App\Models\Mensa;
use App\Models\ExtraOption;
use App\Models\Signup;

trait MensaMapper {
    use ExtraOptionsMapper, UserMapper;

    /**
     * @param Mensa $mensa
     * @param Signup[] $users
     * @param ExtraOption[] $options
     * @return MensaItem
     */
    function mapMensa(Mensa $mensa, array $users, array $options): MensaItem {
        $dishwashers = array_filter($users, function ($user) { return $user->dishwasher; });
        $cooks = array_filter($users, function($user) { return $user->cooks; });

        $userMapper = function($user) { return self::mapUser($user); };

        return new MensaItem(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->isClosed(),
            maxSignups: $mensa->max_users,
            signups: count($users),
            dishwashers: count($dishwashers),
            cooks: array_map($userMapper, $cooks),
            extraOptions: array_map(function ($option) { return self::mapExtraOptions($option); }, $options),
        );
    }


    /**
     * @param Mensa $mensa
     * @param array $users
     * @param array $options
     * @return MensaDetailItem
     */
    function mapMensaDetails(Mensa $mensa, array $users, array $options): MensaDetailItem {
        $userMapper = function($user) { return self::mapUser($user); };

        return new MensaDetailItem(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->isClosed(),
            maxSignups: $mensa->max_users,
            signups: array_map($userMapper, $users),
            extraOptions: array_map(function ($option) { return self::mapExtraOptions($option); }, $options),
        );
    }
}
