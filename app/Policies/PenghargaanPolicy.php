<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Penghargaan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenghargaanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Penghargaan');
    }

    public function view(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('View:Penghargaan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Penghargaan');
    }

    public function update(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('Update:Penghargaan');
    }

    public function delete(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('Delete:Penghargaan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Penghargaan');
    }

    public function restore(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('Restore:Penghargaan');
    }

    public function forceDelete(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('ForceDelete:Penghargaan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Penghargaan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Penghargaan');
    }

    public function replicate(AuthUser $authUser, Penghargaan $penghargaan): bool
    {
        return $authUser->can('Replicate:Penghargaan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Penghargaan');
    }

}