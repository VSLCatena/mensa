<?php

namespace App\Http\Helpers;

trait UserHelper {
    function hasUserPermission($user, $permission): bool {
        if ($user == null) return false;
        return $user->tokenCan($permission);
    }

}