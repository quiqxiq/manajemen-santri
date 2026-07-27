<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Kamar;
use Illuminate\Auth\Access\HandlesAuthorization;

class KamarPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Kamar');
    }

    public function view(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('View:Kamar');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Kamar');
    }

    public function update(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('Update:Kamar');
    }

    public function delete(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('Delete:Kamar');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Kamar');
    }

    public function restore(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('Restore:Kamar');
    }

    public function forceDelete(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('ForceDelete:Kamar');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Kamar');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Kamar');
    }

    public function replicate(AuthUser $authUser, Kamar $kamar): bool
    {
        return $authUser->can('Replicate:Kamar');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Kamar');
    }

}