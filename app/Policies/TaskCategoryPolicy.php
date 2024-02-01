<?php

namespace App\Policies;

use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->userRole->isAdmin() ||
            $user->position->isProjectManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TaskCategory $taskCategory): bool
    {
        return $user->userRole->isAdmin() ||
            $user->position->isProjectManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->userRole->isAdmin() ||
            $user->position->isProjectManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TaskCategory $taskCategory): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TaskCategory $taskCategory): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TaskCategory $taskCategory): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TaskCategory $taskCategory): bool
    {
        return $user->userRole->isAdmin();
    }
}
