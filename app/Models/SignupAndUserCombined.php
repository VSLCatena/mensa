<?php

namespace App\Models;

class SignupAndUserCombined
{
    public function __construct(
        public User $user,
        public Signup $signup
    ) {
    }
}
