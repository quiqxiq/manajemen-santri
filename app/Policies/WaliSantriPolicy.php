<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WaliSantri;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaliSantriPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WaliSantri');
    }

    public function view(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('View:WaliSantri');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WaliSantri');
    }

    public function update(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('Update:WaliSantri');
    }

    public function delete(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('Delete:WaliSantri');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WaliSantri');
    }

    public function restore(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('Restore:WaliSantri');
    }

    public function forceDelete(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('ForceDelete:WaliSantri');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WaliSantri');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WaliSantri');
    }

    public function replicate(AuthUser $authUser, WaliSantri $waliSantri): bool
    {
        return $authUser->can('Replicate:WaliSantri');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WaliSantri');
    }

}