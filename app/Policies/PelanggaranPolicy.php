<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pelanggaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class PelanggaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pelanggaran');
    }

    public function view(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('View:Pelanggaran');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pelanggaran');
    }

    public function update(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('Update:Pelanggaran');
    }

    public function delete(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('Delete:Pelanggaran');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Pelanggaran');
    }

    public function restore(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('Restore:Pelanggaran');
    }

    public function forceDelete(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('ForceDelete:Pelanggaran');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pelanggaran');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pelanggaran');
    }

    public function replicate(AuthUser $authUser, Pelanggaran $pelanggaran): bool
    {
        return $authUser->can('Replicate:Pelanggaran');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pelanggaran');
    }

}