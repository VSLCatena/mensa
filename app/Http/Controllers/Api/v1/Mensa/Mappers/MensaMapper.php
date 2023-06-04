<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Common\Mappers\SimpleUserMapper;
use App\Http\Controllers\Api\v1\Common\Models\SimpleUserDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserDto;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\SignupAndUserCombined;
use App\Models\User;

class MensaMapper
{
    public function __construct(
        private readonly ExtraOptionsMapper $extraOptionsMapper,
        private readonly UserSignupMapper   $userMapper,
        private readonly MenuItemMapper     $menuItemMapper,
        private readonly FoodOptionsMapper  $foodOptionsMapper,
        private readonly SimpleUserMapper   $simpleUserMapper
    ) {
    }

    /**
     * @param  SignupAndUserCombined[]  $userAndSignups
     * @param  MenuItem[]  $menu
     * @param  ExtraOption[]  $options
     */
    public function map(
        Mensa $mensa,
        array $userAndSignups,
        array $menu,
        array $options,
        ?User $currentUser
    ): MensaDto {
        return new MensaDto(
            id: $mensa->id,
            title: $mensa->title,
            description: $mensa->description,
            date: $mensa->date,
            closingTime: $mensa->closing_time,
            isClosed: $mensa->closed,
            maxSignups: $mensa->max_signups,
            signups: $this->mapSignups($userAndSignups, $currentUser),
            price: $mensa->price,
            dishwashers: $this->countDishwashers($userAndSignups),
            cooks: $this->getMappedCooks($userAndSignups),
            foodOptions: $this->foodOptionsMapper->fromIntToNames($mensa->food_options),
            menu: $this->menuItemMapper->mapArray($menu),
            extraOptions: $this->extraOptionsMapper->mapArray($options),
        );
    }

    /**
     * @param SignupAndUserCombined[] $users
     * @return int
     */
    private function countDishwashers(array $users): int
    {
        return count(array_filter($users, function ($user) {
            return $user->signup->dishwasher;
        }));
    }

    /**
     * @param SignupAndUserCombined[] $users
     * @return SimpleUserDto[]
     */
    private function getMappedCooks(array $users): array
    {
        $cooks = array_filter($users, function ($user) {
            return $user->signup->cooks;
        });

        return array_values(
            array_map(function ($userAndSignup) {
                return $this->simpleUserMapper->map($userAndSignup->user);
            }, $cooks)
        );
    }

    /**
     * @param SignupAndUserCombined[] $signups
     * @return int|MensaUserDto[]
     */
    private function mapSignups(array $signups, ?User $currentUser): int|array
    {
        if (!$currentUser) {
            return $this->mapSignupsAsAnonymous($signups);
        } else if($currentUser->mensa_admin) {
            return $this->mapSignupsAsAdmin($signups);
        } else {
            return $this->mapSignupsAsUser($signups);
        }
    }

    /**
     * @param SignupAndUserCombined[] $signups
     * @return int
     */
    private function mapSignupsAsAnonymous(array $signups): int
    {
        return count($signups);
    }

    /**
     * @param SignupAndUserCombined[] $signups
     * @return MensaUserDto[]
     */
    private function mapSignupsAsUser(array $signups): array
    {
        return array_values(array_map(function ($userAndSignup) {
            return $this->userMapper->map($userAndSignup->user, $userAndSignup->signup, false);
        }, $signups));
    }

    /**
     * @param SignupAndUserCombined[] $signups
     * @return MensaUserDto[]
     */
    private function mapSignupsAsAdmin(array $signups): array
    {
        return array_values(array_map(function ($userAndSignup) {
            return $this->userMapper->map($userAndSignup->user, $userAndSignup->signup, true);
        }, $signups));
    }
}
