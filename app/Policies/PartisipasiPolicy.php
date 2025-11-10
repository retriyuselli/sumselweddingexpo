<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Partisipasi;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartisipasiPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Partisipasi');
    }

    public function view(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('View:Partisipasi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Partisipasi');
    }

    public function update(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('Update:Partisipasi');
    }

    public function delete(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('Delete:Partisipasi');
    }

    public function restore(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('Restore:Partisipasi');
    }

    public function forceDelete(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('ForceDelete:Partisipasi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Partisipasi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Partisipasi');
    }

    public function replicate(AuthUser $authUser, Partisipasi $partisipasi): bool
    {
        return $authUser->can('Replicate:Partisipasi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Partisipasi');
    }

}