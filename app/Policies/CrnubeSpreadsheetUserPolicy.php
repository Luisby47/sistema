<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\CrnubeSpreadsheetUser;




// Aqui se hacaen la autorización de los usuarios de manera global
// Si es true = cualquiera puede hacer la acción
// Si es superAdmin = solo el superAdmin puede hacer la acción
// Esto es para la tabla o recurso de CrnubeSpreadsheetUser, se puede hacer el manejo de los roles de esta manera
// Pero no esto seguro a nivel de menu
class CrnubeSpreadsheetUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(CrnubeSpreadsheetUser $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(CrnubeSpreadsheetUser $user, CrnubeSpreadsheetUser $item): bool
    {
        return $user->isSuperAdmin();
    }

    public function create(CrnubeSpreadsheetUser $user): bool
    {
        return true;
    }

    public function update(CrnubeSpreadsheetUser $user, CrnubeSpreadsheetUser $item): bool
    {
        return true;
    }

    public function delete(CrnubeSpreadsheetUser $user, CrnubeSpreadsheetUser $item): bool
    {
        return true;
    }

    public function restore(CrnubeSpreadsheetUser $user, CrnubeSpreadsheetUser $item): bool
    {
        return true;
    }

    public function forceDelete(CrnubeSpreadsheetUser $user, CrnubeSpreadsheetUser $item): bool
    {
        return true;
    }

    public function massDelete(CrnubeSpreadsheetUser $user): bool
    {
        return true;
    }
}
