<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserDetails;

class UserDetailsPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function updateAndDelete(User $user, UserDetails $userDetails)
    {
        // return $user->id === $userDetails->user_id;

        // or using declarative way
        return $user->is($userDetails->user);
    }
}
