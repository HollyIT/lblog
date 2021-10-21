<?php

namespace App\Policies;

use App\Models\Image;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return ! empty($user->role);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $user
     * @param  Image  $image
     * @return bool
     */
    public function view(User $user, Image $image): bool
    {
        return ! empty($user->role);
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
     * @param  Image  $image
     * @return bool
     */
    public function update(User $user, Image $image): bool
    {
        return $user->hasRole(['editor', 'admin']) || $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Image  $image
     * @return bool
     */
    public function delete(User $user, Image $image): bool
    {
        if ($image->group === 'avatars') {
            return $user->hasRole(['admin']) || $user->id === $image->user_id;
        }

        return $user->hasRole(['editor', 'admin']) || $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  User  $user
     * @param  Image  $image
     * @return bool
     */
    public function restore(User $user, Image $image): bool
    {
        return $user->hasRole(['editor', 'admin']) || $user->id === $image->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  User  $user
     * @param  Image  $image
     * @return bool
     */
    public function forceDelete(User $user, Image $image): bool
    {
        return $user->hasRole(['editor', 'admin']);
    }
}
