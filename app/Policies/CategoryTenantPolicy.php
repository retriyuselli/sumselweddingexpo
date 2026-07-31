<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CategoryTenant;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryTenantPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CategoryTenant');
    }

    public function view(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('View:CategoryTenant');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CategoryTenant');
    }

    public function update(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('Update:CategoryTenant');
    }

    public function delete(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('Delete:CategoryTenant');
    }

    public function restore(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('Restore:CategoryTenant');
    }

    public function forceDelete(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('ForceDelete:CategoryTenant');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CategoryTenant');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CategoryTenant');
    }

    public function replicate(AuthUser $authUser, CategoryTenant $categoryTenant): bool
    {
        return $authUser->can('Replicate:CategoryTenant');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CategoryTenant');
    }

}