<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductVendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductVendorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductVendor');
    }

    public function view(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('View:ProductVendor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductVendor');
    }

    public function update(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('Update:ProductVendor');
    }

    public function delete(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('Delete:ProductVendor');
    }

    public function restore(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('Restore:ProductVendor');
    }

    public function forceDelete(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('ForceDelete:ProductVendor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductVendor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductVendor');
    }

    public function replicate(AuthUser $authUser, ProductVendor $productVendor): bool
    {
        return $authUser->can('Replicate:ProductVendor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductVendor');
    }

}