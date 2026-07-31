<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JenisUsaha;
use Illuminate\Auth\Access\HandlesAuthorization;

class JenisUsahaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JenisUsaha');
    }

    public function view(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('View:JenisUsaha');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JenisUsaha');
    }

    public function update(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('Update:JenisUsaha');
    }

    public function delete(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('Delete:JenisUsaha');
    }

    public function restore(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('Restore:JenisUsaha');
    }

    public function forceDelete(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('ForceDelete:JenisUsaha');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JenisUsaha');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JenisUsaha');
    }

    public function replicate(AuthUser $authUser, JenisUsaha $jenisUsaha): bool
    {
        return $authUser->can('Replicate:JenisUsaha');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JenisUsaha');
    }

}