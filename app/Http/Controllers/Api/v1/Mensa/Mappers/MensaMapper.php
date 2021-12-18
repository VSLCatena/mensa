<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaDetailItem;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaItem;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;

trait MensaMapper
{
    use ExtraOptionsMapper, UserMapper, MenuItemMapper, FoodOptionsMapper;

    /**
     * @param Mensa $mensa
     * @param Signup[] $users
     * @param MenuItem[] $menu
     * @param ExtraOption[] $options
     * @return MensaItem
     */
    function mapMensa(
        Mensa $mensa,
        array $users,
        array $menu,
        array $options,
        bool $isLoggedIn
    ): MensaItem
    {
        $dishwashers = array_filter($users, function ($user) {
            return $user->dishwasher;
        });
        $cooks = array_filter($users, function ($user) {
            return $user->cooks;
        });

        $userSignupMapper = function ($signup) {
            return self::mapUserFromSignup($signup);
        };

        $signups = count($users);
        if ($isLoggedIn) {
            $signups = array_map($userSignupMapper, $users);
        }

        return new MensaItem(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->isClosed(),
            maxSignups: $mensa->max_users,
            signups: $signups,
            price: $mensa->price,
            dishwashers: count($dishwashers),

            cooks: array_values(array_map($userSignupMapper, $cooks)),
            foodOptions: self::mapFoodOptionsFromIntToNames($mensa->food_options),
            menu: self::mapMenuItems($menu),
            extraOptions: self::mapExtraOptions($options),
        );
    }
}
