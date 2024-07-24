<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function modify(User $user, Comment $comment): Response
    {
        $post = $comment->post;

        return $user->id === $comment->user_id || $user->id === $post->user_id
            ? Response::allow()
            : Response::deny("You don't have permission to modify this comment.");
    }
}
