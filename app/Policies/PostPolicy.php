<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;


class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyPermission([
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'unpublish posts',
        ])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create posts')
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Post $post)
    {
        return $user->hasPermissionTo('edit posts') && $user->id === $post->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Post $post)
    {
        return $user->hasPermissionTo('delete posts') && $user->id === $post->user_id
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can publish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function publish(User $user, Post $post)
    {
        if (!$user->hasRole('moderator')) {
            return $user->hasPermissionTo('publish posts') && $user->id === $post->user_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return $user->hasPermissionTo('publish posts')
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can unpublish the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unpublish(User $user, Post $post)
    {
        if (!$user->hasRole('moderator')) {
            return $user->hasPermissionTo('unpublish posts') && $user->id === $post->user_id
                ? Response::allow()
                : Response::denyAsNotFound();
        }

        return $user->hasPermissionTo('unpublish posts')
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
