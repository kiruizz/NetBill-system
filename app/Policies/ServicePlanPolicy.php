<?php

namespace App\Policies;

use App\Models\ServicePlan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage-service-plans') || $user->can('manage-billing');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServicePlan $servicePlan): bool
    {
        return $user->can('manage-service-plans') || $user->can('manage-billing');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage-service-plans');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServicePlan $servicePlan): bool
    {
        return $user->can('manage-service-plans');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServicePlan $servicePlan): bool
    {
        return $user->can('manage-service-plans');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ServicePlan $servicePlan): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ServicePlan $servicePlan): bool
    {
        return $user->hasRole('super-admin');
    }
}
