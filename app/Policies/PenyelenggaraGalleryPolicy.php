<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PenyelenggaraGallery;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenyelenggaraGalleryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PenyelenggaraGallery');
    }

    public function view(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('View:PenyelenggaraGallery');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PenyelenggaraGallery');
    }

    public function update(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('Update:PenyelenggaraGallery');
    }

    public function delete(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('Delete:PenyelenggaraGallery');
    }

    public function restore(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('Restore:PenyelenggaraGallery');
    }

    public function forceDelete(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('ForceDelete:PenyelenggaraGallery');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PenyelenggaraGallery');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PenyelenggaraGallery');
    }

    public function replicate(AuthUser $authUser, PenyelenggaraGallery $penyelenggaraGallery): bool
    {
        return $authUser->can('Replicate:PenyelenggaraGallery');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PenyelenggaraGallery');
    }

}