<?php

namespace App\Http\Controllers\Api\v1\Utils\User;

use App\Models\User;

class UserCache
{
    /**
     * @var array[string]User
     */
    private static array $cache = [];

    public static function getCachedUser(string $userId)
    {
        if (! array_key_exists($userId, self::$cache)) {
            self::$cache[$userId] = User::find($userId);
        }

        return self::$cache[$userId];
    }
}
