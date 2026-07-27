<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Perizinan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerizinanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Perizinan');
    }

    public function view(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('View:Perizinan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Perizinan');
    }

    public function update(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('Update:Perizinan');
    }

    public function delete(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('Delete:Perizinan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Perizinan');
    }

    public function restore(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('Restore:Perizinan');
    }

    public function forceDelete(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('ForceDelete:Perizinan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Perizinan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Perizinan');
    }

    public function replicate(AuthUser $authUser, Perizinan $perizinan): bool
    {
        return $authUser->can('Replicate:Perizinan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Perizinan');
    }

}