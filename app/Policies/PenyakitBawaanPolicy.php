<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PenyakitBawaan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenyakitBawaanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PenyakitBawaan');
    }

    public function view(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('View:PenyakitBawaan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PenyakitBawaan');
    }

    public function update(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('Update:PenyakitBawaan');
    }

    public function delete(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('Delete:PenyakitBawaan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PenyakitBawaan');
    }

    public function restore(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('Restore:PenyakitBawaan');
    }

    public function forceDelete(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('ForceDelete:PenyakitBawaan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PenyakitBawaan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PenyakitBawaan');
    }

    public function replicate(AuthUser $authUser, PenyakitBawaan $penyakitBawaan): bool
    {
        return $authUser->can('Replicate:PenyakitBawaan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PenyakitBawaan');
    }

}