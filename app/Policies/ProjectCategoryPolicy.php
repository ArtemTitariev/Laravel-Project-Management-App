<?php

namespace App\Policies;

use App\Models\ProjectCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectCategoryPolicy
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
    public function view(User $user, ProjectCategory $projectCategory): bool
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
    public function update(User $user, ProjectCategory $projectCategory): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectCategory $projectCategory): bool
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
    public function restore(User $user, ProjectCategory $projectCategory): bool
    {
        return $user->userRole->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectCategory $projectCategory): bool
    {
        return $user->userRole->isAdmin();
    }
}
