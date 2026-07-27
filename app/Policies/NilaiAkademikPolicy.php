<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NilaiAkademik;
use Illuminate\Auth\Access\HandlesAuthorization;

class NilaiAkademikPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NilaiAkademik');
    }

    public function view(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('View:NilaiAkademik');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NilaiAkademik');
    }

    public function update(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('Update:NilaiAkademik');
    }

    public function delete(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('Delete:NilaiAkademik');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:NilaiAkademik');
    }

    public function restore(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('Restore:NilaiAkademik');
    }

    public function forceDelete(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('ForceDelete:NilaiAkademik');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NilaiAkademik');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NilaiAkademik');
    }

    public function replicate(AuthUser $authUser, NilaiAkademik $nilaiAkademik): bool
    {
        return $authUser->can('Replicate:NilaiAkademik');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NilaiAkademik');
    }

}