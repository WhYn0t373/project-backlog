<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Feature;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy to handle authorization for Feature resource.
 */
class FeaturePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any features.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('read features');
    }

    /**
     * Determine whether the user can view the feature.
     */
    public function view(User $user, Feature $feature): bool
    {
        return $user->can('read features');
    }

    /**
     * Determine whether the user can create features.
     */
    public function create(User $user): bool
    {
        return $user->can('create features');
    }

    /**
     * Determine whether the user can update the feature.
     */
    public function update(User $user, Feature $feature): bool
    {
        return $user->can('update features');
    }

    /**
     * Determine whether the user can delete the feature.
     */
    public function delete(User $user, Feature $feature): bool
    {
        return $user->can('delete features');
    }
}