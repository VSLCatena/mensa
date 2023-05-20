<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaResponseModel;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\SignupAndUserCombined;
use App\Models\User;

trait MensaMapper
{
    use ExtraOptionsMapper, UserMapper, MenuItemMapper, FoodOptionsMapper, SignupMapper;

    /**
     * @param  SignupAndUserCombined[]  $userAndSignups
     * @param  MenuItem[]  $menu
     * @param  ExtraOption[]  $options
     */
    public function mapMensa(
        Mensa $mensa,
        array $userAndSignups,
        array $menu,
        array $options,
        ?User $currentUser
    ): MensaResponseModel {
        $dishwashers = array_filter($userAndSignups, function ($user) {
            return $user->signup->dishwasher;
        });
        $cooks = array_filter($userAndSignups, function ($user) {
            return $user->signup->cooks;
        });

        $simpleUserMapper = function ($userAndSignup) {
            return self::mapUser($userAndSignup->user, null);
        };

        $signups = count($userAndSignups);
        if ($currentUser) {
            $isAdmin = $currentUser->mensa_admin;
            $signups = array_values(array_map(
                function ($userAndSignup) use ($isAdmin) {
                    return self::mapUser(
                        $userAndSignup->user,
                        $isAdmin ? self::mapSignup($userAndSignup->signup) : null
                    );
                }, $userAndSignups
            ));
        }

        return new MensaResponseModel(
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
            cooks: array_values(array_map($simpleUserMapper, $cooks)),
            foodOptions: self::mapFoodOptionsFromIntToNames($mensa->food_options),
            menu: self::mapMenuItems($menu),
            extraOptions: self::mapExtraOptions($options),
        );
    }
}
