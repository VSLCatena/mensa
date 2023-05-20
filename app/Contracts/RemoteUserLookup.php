<?php

namespace App\Contracts;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Psr\Http\Client\ClientExceptionInterface;

abstract class RemoteUserLookup
{
    /**
     * Gets an user from the remote repository
     *
     * @throws ClientExceptionInterface
     */
    abstract public function getUser(string $userReference): ?User;

    /**
     * Returns a list of groups the user is in
     *
     * @return array an array of groups the user is in
     *
     * @throws ClientExceptionInterface
     */
    abstract public function getUserGroups(string $userId): array;

    /**
     * First grab the local user, if none was found try getting it from remote.
     * This also automatically checks if the user needs any updating.
     *
     * @throws ClientExceptionInterface
     */
    public function lookLocalFirst(string $userReference): ?User
    {
        $emailSuffix = config('mensa.remote_user.email_suffix');
        $emailedUser = ends_with($userReference, $emailSuffix) ? $userReference : "$userReference$emailSuffix";

        $user = User::where('email', '=', $userReference)
            ->orWhere('remote_principal_name', '=', $emailedUser)
            ->first();

        if ($user == null) {
            $user = $this->getUser($userReference);
            if ($user != null) {
                $user->save();
            }

            return $user;
        }

        return $this->getUpdatedUserIfNecessary($user, $userReference);
    }

    /**
     * Get the current user. And update it if necessary.
     *
     * @throws ClientExceptionInterface
     */
    public function currentUpdatedIfNecessary(): ?User
    {
        $user = Auth::guard('sanctum')->user() ?? Auth::getUser();
        if ($user == null) {
            return null;
        }

        return $this->getUpdatedUserIfNecessary($user);
    }

    /**
     * Checks if the given user needs to be updated and if so, updates it from the remote source
     *
     * @throws ClientExceptionInterface
     */
    public function getUpdatedUserIfNecessary(User $user, ?string $userReference = null): ?User
    {
        $userReference = $userReference ?? $user->remote_principal_name;

        $timeToUpdate = strtotime($user->remote_last_check) + config('mensa.remote_user.update_time');

        if ($timeToUpdate < time()) {
            return $this->getUpdatedUser($user, $userReference);
        }

        return $user;
    }

    /**
     * Update the given user from the remote source
     *
     * @throws ClientExceptionInterface
     */
    public function getUpdatedUser(User $user, ?string $userReference = null): ?User
    {
        $userReference = $userReference ?? $user->remote_principal_name;

        $remoteUser = $this->getUser($userReference);

        if ($remoteUser == null) {
            $user->forceDelete();

            return null;
        }

        $user->remote_principal_name = $remoteUser->remote_principal_name;
        $user->name = $remoteUser->name;
        $user->email = $remoteUser->email;
        $user->mensa_admin = $remoteUser->mensa_admin;
        $user->remote_last_check = Carbon::now()->getTimestamp();
        $user->save();

        return $user;

    }
}
