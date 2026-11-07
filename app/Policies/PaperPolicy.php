<?php

namespace App\Policies;

use App\Models\Paper;
use App\Models\User;

class PaperPolicy
{
    public function view(User $user, Paper $paper): bool
    {
        return $user->isAdmin() || $paper->user_id === $user->id;
    }

    public function update(User $user, Paper $paper): bool
    {
        return $paper->user_id === $user->id;
    }

    public function delete(User $user, Paper $paper): bool
    {
        return $paper->user_id === $user->id || $user->isAdmin();
    }
}
