<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RiwayatKesehatan;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiwayatKesehatanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RiwayatKesehatan');
    }

    public function view(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('View:RiwayatKesehatan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RiwayatKesehatan');
    }

    public function update(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('Update:RiwayatKesehatan');
    }

    public function delete(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('Delete:RiwayatKesehatan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RiwayatKesehatan');
    }

    public function restore(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('Restore:RiwayatKesehatan');
    }

    public function forceDelete(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('ForceDelete:RiwayatKesehatan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RiwayatKesehatan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RiwayatKesehatan');
    }

    public function replicate(AuthUser $authUser, RiwayatKesehatan $riwayatKesehatan): bool
    {
        return $authUser->can('Replicate:RiwayatKesehatan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RiwayatKesehatan');
    }

}