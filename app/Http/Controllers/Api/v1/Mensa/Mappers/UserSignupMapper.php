<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaUserWithSignupDto;
use App\Models\Signup;
use App\Models\User;

class UserSignupMapper
{

    public function __construct(
        public SignupMapper $signupMapper
    )
    {
    }

    public function map(User $user, Signup $signup, bool $shouldIncludeSignup): MensaUserDto|MensaUserWithSignupDto
    {
        if ($shouldIncludeSignup) {
            return new MensaUserWithSignupDto(
                id: $user->id,
                name: $user->name,
                isIntro: $signup->is_intro,
                signup: $this->signupMapper->map($signup)
            );
        } else {
            return new MensaUserDto(
                id: $user->id,
                name: $user->name,
                isIntro: $signup->is_intro
            );
        }
    }
}
