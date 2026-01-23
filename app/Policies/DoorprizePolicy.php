<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Doorprize;
use Illuminate\Auth\Access\HandlesAuthorization;

class DoorprizePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Doorprize');
    }

    public function view(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('View:Doorprize');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Doorprize');
    }

    public function update(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('Update:Doorprize');
    }

    public function delete(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('Delete:Doorprize');
    }

    public function restore(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('Restore:Doorprize');
    }

    public function forceDelete(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('ForceDelete:Doorprize');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Doorprize');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Doorprize');
    }

    public function replicate(AuthUser $authUser, Doorprize $doorprize): bool
    {
        return $authUser->can('Replicate:Doorprize');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Doorprize');
    }

}