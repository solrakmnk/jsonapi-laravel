<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function update(User $user, $article)
    {
        return $article->user->is($user);
        //return $user->id === $article->user_id;
    }

    public function delete(User $user, $article)
    {
        return $article->user->is($user);
        //return $user->id === $article->user_id;
    }
}
