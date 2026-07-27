<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pengurus;
use Illuminate\Auth\Access\HandlesAuthorization;

class PengurusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pengurus');
    }

    public function view(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('View:Pengurus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pengurus');
    }

    public function update(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('Update:Pengurus');
    }

    public function delete(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('Delete:Pengurus');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Pengurus');
    }

    public function restore(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('Restore:Pengurus');
    }

    public function forceDelete(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('ForceDelete:Pengurus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pengurus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pengurus');
    }

    public function replicate(AuthUser $authUser, Pengurus $pengurus): bool
    {
        return $authUser->can('Replicate:Pengurus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pengurus');
    }

}