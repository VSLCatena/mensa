<?php

namespace App\Policies;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a faq.
     */
    public function create(User $user): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can edit faqs
     */
    public function edit(?User $user, Faq $faq): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can delete the faq.
     */
    public function delete(User $user, Faq $faq): bool
    {
        return $this->isMensaAdmin($user);
    }

    /**
     * Determine whether the user can sort the faqs.
     */
    public function sort(User $user): bool
    {
        return $this->isMensaAdmin($user);
    }

    private function isMensaAdmin(?User $user): bool
    {
        return optional($user)->mensa_admin == true;
    }
}
