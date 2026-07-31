<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Home;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Home');
    }

    public function view(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('View:Home');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Home');
    }

    public function update(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('Update:Home');
    }

    public function delete(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('Delete:Home');
    }

    public function restore(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('Restore:Home');
    }

    public function forceDelete(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('ForceDelete:Home');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Home');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Home');
    }

    public function replicate(AuthUser $authUser, Home $home): bool
    {
        return $authUser->can('Replicate:Home');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Home');
    }

}