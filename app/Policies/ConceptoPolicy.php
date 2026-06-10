<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Concepto;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConceptoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Concepto');
    }

    public function view(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('View:Concepto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Concepto');
    }

    public function update(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('Update:Concepto');
    }

    public function delete(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('Delete:Concepto');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Concepto');
    }

    public function restore(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('Restore:Concepto');
    }

    public function forceDelete(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('ForceDelete:Concepto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Concepto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Concepto');
    }

    public function replicate(AuthUser $authUser, Concepto $concepto): bool
    {
        return $authUser->can('Replicate:Concepto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Concepto');
    }

}