<?php

namespace App\Policies;

use App\Models\Ngo;
use App\Models\User;

class NgoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ngo $ngo): bool
    {
        return true;
    }

    public function update(User $user, Ngo $ngo): bool
    {
        return $user->id === $ngo->user_id || $user->isAdmin();
    }

    public function verify(User $user): bool
    {
        return $user->isAdmin();
    }

    public function suspend(User $user): bool
    {
        return $user->isAdmin();
    }
}
