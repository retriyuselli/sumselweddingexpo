<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pengeluaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengeluaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pengeluaran');
    }

    public function view(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('View:Pengeluaran');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pengeluaran');
    }

    public function update(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('Update:Pengeluaran');
    }

    public function delete(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('Delete:Pengeluaran');
    }

    public function restore(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('Restore:Pengeluaran');
    }

    public function forceDelete(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('ForceDelete:Pengeluaran');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pengeluaran');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pengeluaran');
    }

    public function replicate(AuthUser $authUser, Pengeluaran $pengeluaran): bool
    {
        return $authUser->can('Replicate:Pengeluaran');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pengeluaran');
    }

}