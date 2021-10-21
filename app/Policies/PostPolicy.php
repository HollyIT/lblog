<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return bool
     * ool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return bool
     */
    public function view(User $user, Post $post): bool
    {
        return ! empty($post->published_at) || ($post->user_id === $user->id || $user->hasRole(['admin', 'editor']));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return ! empty($user->role);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return ($post->user_id === $user->id || $user->hasRole(['admin', 'editor']));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        return ($post->user_id === $user->id || $user->hasRole(['admin', 'editor']));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return bool
     */
    public function restore(User $user, Post $post): bool
    {
        return ($post->user_id === $user->id || $user->hasRole(['admin', 'editor']));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return bool
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }

    public function assignOwner(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }
}
