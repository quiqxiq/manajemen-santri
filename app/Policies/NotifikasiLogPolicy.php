<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NotifikasiLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotifikasiLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NotifikasiLog');
    }

    public function view(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('View:NotifikasiLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NotifikasiLog');
    }

    public function update(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('Update:NotifikasiLog');
    }

    public function delete(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('Delete:NotifikasiLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:NotifikasiLog');
    }

    public function restore(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('Restore:NotifikasiLog');
    }

    public function forceDelete(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('ForceDelete:NotifikasiLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NotifikasiLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NotifikasiLog');
    }

    public function replicate(AuthUser $authUser, NotifikasiLog $notifikasiLog): bool
    {
        return $authUser->can('Replicate:NotifikasiLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NotifikasiLog');
    }

}