<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Rundown;
use Illuminate\Auth\Access\HandlesAuthorization;

class RundownPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Rundown');
    }

    public function view(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('View:Rundown');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Rundown');
    }

    public function update(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('Update:Rundown');
    }

    public function delete(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('Delete:Rundown');
    }

    public function restore(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('Restore:Rundown');
    }

    public function forceDelete(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('ForceDelete:Rundown');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Rundown');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Rundown');
    }

    public function replicate(AuthUser $authUser, Rundown $rundown): bool
    {
        return $authUser->can('Replicate:Rundown');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Rundown');
    }

}