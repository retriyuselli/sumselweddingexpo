<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TenantSpot;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantSpotPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TenantSpot');
    }

    public function view(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('View:TenantSpot');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TenantSpot');
    }

    public function update(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('Update:TenantSpot');
    }

    public function delete(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('Delete:TenantSpot');
    }

    public function restore(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('Restore:TenantSpot');
    }

    public function forceDelete(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('ForceDelete:TenantSpot');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TenantSpot');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TenantSpot');
    }

    public function replicate(AuthUser $authUser, TenantSpot $tenantSpot): bool
    {
        return $authUser->can('Replicate:TenantSpot');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TenantSpot');
    }

}