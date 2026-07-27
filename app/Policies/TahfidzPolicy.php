<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tahfidz;
use Illuminate\Auth\Access\HandlesAuthorization;

class TahfidzPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tahfidz');
    }

    public function view(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('View:Tahfidz');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tahfidz');
    }

    public function update(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('Update:Tahfidz');
    }

    public function delete(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('Delete:Tahfidz');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Tahfidz');
    }

    public function restore(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('Restore:Tahfidz');
    }

    public function forceDelete(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('ForceDelete:Tahfidz');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tahfidz');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tahfidz');
    }

    public function replicate(AuthUser $authUser, Tahfidz $tahfidz): bool
    {
        return $authUser->can('Replicate:Tahfidz');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tahfidz');
    }

}