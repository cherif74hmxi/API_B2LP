<?php

namespace App\Policies;

use App\Models\Billet;
use App\Models\User;

class BilletPolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Billet $billet): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Billet $billet): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Billet $billet): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Billet $billet): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Billet $billet): bool
    {
        return $user->isAdmin();
    }
}
