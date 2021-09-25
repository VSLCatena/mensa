<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\FoodOptions;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaDetailItem;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaItem;
use App\Models\Mensa;
use App\Models\ExtraOption;
use App\Models\MenuItem;
use App\Models\Signup;

trait MensaMapper {
    use ExtraOptionsMapper, UserMapper, MenuItemMapper, FoodOptionsMapper;

    /**
     * @param Mensa $mensa
     * @param Signup[] $users
     * @param MenuItem[] $menu
     * @param ExtraOption[] $options
     * @return MensaItem
     */
    function mapMensa(Mensa $mensa, array $users, array $menu, array $options): MensaItem {
        $dishwashers = array_filter($users, function ($user) { return $user->dishwasher; });
        $cooks = array_filter($users, function($user) { return $user->cooks; });

        $userSignupMapper = function($signup) { return self::mapUserFromSignup($signup); };

        return new MensaItem(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->isClosed(),
            maxSignups: $mensa->max_users,
            signups: count($users),
            price: $mensa->price,
            dishwashers: count($dishwashers),

            cooks: array_values(array_map($userSignupMapper, $cooks)),
            foodOptions: self::mapFoodOptions($mensa->food_options),
            menu: array_map(function($item) { return self::mapMenuItem($item); }, $menu),
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
        $userSignupMapper = function($signup) { return self::mapUserFromSignup($signup); };

        return new MensaDetailItem(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->isClosed(),
            maxSignups: $mensa->max_users,
            signups: array_map($userSignupMapper, $users),
            extraOptions: array_map(function ($option) { return self::mapExtraOptions($option); }, $options),
        );
    }
}
