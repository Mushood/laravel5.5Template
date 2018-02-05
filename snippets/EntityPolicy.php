<?php

namespace App\Policies;

use App\User;
use App\Entity;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntityPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the entity.
     *
     * @param  \App\User  $user
     * @param  \App\Entity  $entity
     * @return mixed
     */
    public function view(User $user, Entity $entity)
    {
        return $user->id === $entity->user_id;
    }

    /**
     * Determine whether the user can create entities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the entity.
     *
     * @param  \App\User  $user
     * @param  \App\Entity  $entity
     * @return mixed
     */
    public function update(User $user, Entity $entity)
    {
        return $user->id === $entity->user_id;
    }

    /**
     * Determine whether the user can delete the entity.
     *
     * @param  \App\User  $user
     * @param  \App\Entity  $entity
     * @return mixed
     */
    public function delete(User $user, Entity $entity)
    {
        return $user->id === $entity->user_id;
    }
}
