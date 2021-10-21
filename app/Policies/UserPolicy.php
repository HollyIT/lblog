<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  User  $user
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  User  $account
     * @param  User  $user
     * @return bool
     */
    public function view(User $account, User $user): bool
    {
        return $user->role == 'admin';
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role == 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  User  $account
     * @return bool
     */
    public function update(User $user, User $account): bool
    {
        return $user->role === 'admin' || $user->id === $account->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  User  $account
     * @return bool
     */
    public function delete(User $user, User $account): bool
    {
        return $user->hasRole('admin') || $account->id === $user->id;
    }


    /**
     * @param  User  $user
     * @param  User  $account
     * @return bool
     */
    public function viewEmail(User $user, User $account): bool
    {
        return $user->role === 'admin' ?? $user->id === $account->id;
    }

    public function assignRole(User $user, User $account): bool
    {
        return $user->hasRole('admin');
    }
}
