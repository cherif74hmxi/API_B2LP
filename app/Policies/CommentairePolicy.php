<?php

namespace App\Policies;

use App\Models\Commentaire;
use App\Models\User;

class CommentairePolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Commentaire $commentaire): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Commentaire $commentaire): bool
    {
        return false;
    }

    public function delete(User $user, Commentaire $commentaire): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Commentaire $commentaire): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Commentaire $commentaire): bool
    {
        return $user->isAdmin();
    }
}
