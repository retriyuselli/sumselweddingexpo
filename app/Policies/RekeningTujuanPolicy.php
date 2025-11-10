<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RekeningTujuan;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekeningTujuanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RekeningTujuan');
    }

    public function view(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('View:RekeningTujuan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RekeningTujuan');
    }

    public function update(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('Update:RekeningTujuan');
    }

    public function delete(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('Delete:RekeningTujuan');
    }

    public function restore(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('Restore:RekeningTujuan');
    }

    public function forceDelete(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('ForceDelete:RekeningTujuan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RekeningTujuan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RekeningTujuan');
    }

    public function replicate(AuthUser $authUser, RekeningTujuan $rekeningTujuan): bool
    {
        return $authUser->can('Replicate:RekeningTujuan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RekeningTujuan');
    }

}