<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Santri;
use Illuminate\Auth\Access\HandlesAuthorization;

class SantriPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Santri');
    }

    public function view(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('View:Santri');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Santri');
    }

    public function update(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Update:Santri');
    }

    public function delete(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Delete:Santri');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Santri');
    }

    public function restore(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Restore:Santri');
    }

    public function forceDelete(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('ForceDelete:Santri');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Santri');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Santri');
    }

    public function replicate(AuthUser $authUser, Santri $santri): bool
    {
        return $authUser->can('Replicate:Santri');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Santri');
    }

}