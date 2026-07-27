<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KategoriPelanggaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class KategoriPelanggaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KategoriPelanggaran');
    }

    public function view(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('View:KategoriPelanggaran');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KategoriPelanggaran');
    }

    public function update(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('Update:KategoriPelanggaran');
    }

    public function delete(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('Delete:KategoriPelanggaran');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KategoriPelanggaran');
    }

    public function restore(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('Restore:KategoriPelanggaran');
    }

    public function forceDelete(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('ForceDelete:KategoriPelanggaran');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KategoriPelanggaran');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KategoriPelanggaran');
    }

    public function replicate(AuthUser $authUser, KategoriPelanggaran $kategoriPelanggaran): bool
    {
        return $authUser->can('Replicate:KategoriPelanggaran');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KategoriPelanggaran');
    }

}