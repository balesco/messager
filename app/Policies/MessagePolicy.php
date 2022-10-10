<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the message can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list messages');
    }

    /**
     * Determine whether the message can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function view(User $user, Message $model)
    {
        return $user->hasPermissionTo('view messages');
    }

    /**
     * Determine whether the message can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create messages');
    }

    /**
     * Determine whether the message can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function update(User $user, Message $model)
    {
        return $user->hasPermissionTo('update messages');
    }

    /**
     * Determine whether the message can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function delete(User $user, Message $model)
    {
        return $user->hasPermissionTo('delete messages');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete messages');
    }

    /**
     * Determine whether the message can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function restore(User $user, Message $model)
    {
        return false;
    }

    /**
     * Determine whether the message can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Message  $model
     * @return mixed
     */
    public function forceDelete(User $user, Message $model)
    {
        return false;
    }
}
