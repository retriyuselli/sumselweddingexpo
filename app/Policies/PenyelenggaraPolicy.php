<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Penyelenggara;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenyelenggaraPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Penyelenggara');
    }

    public function view(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('View:Penyelenggara');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Penyelenggara');
    }

    public function update(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('Update:Penyelenggara');
    }

    public function delete(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('Delete:Penyelenggara');
    }

    public function restore(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('Restore:Penyelenggara');
    }

    public function forceDelete(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('ForceDelete:Penyelenggara');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Penyelenggara');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Penyelenggara');
    }

    public function replicate(AuthUser $authUser, Penyelenggara $penyelenggara): bool
    {
        return $authUser->can('Replicate:Penyelenggara');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Penyelenggara');
    }

}