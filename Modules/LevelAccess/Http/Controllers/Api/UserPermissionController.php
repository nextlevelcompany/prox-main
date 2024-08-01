<?php

namespace Modules\LevelAccess\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Modules\User\Models\User;


class UserPermissionController extends Controller
{

    /**
     *
     * Permisos de los modulos y submodulos por usuario
     *
     * @param  int $id
     * @return array
     */
    public function getWebUserPermissions($id)
    {
        $user = User::findOrFail($id);

        return [
            'other_permissions' => [
                'permission_edit_cpe' => $user->permission_edit_cpe,
                'recreate_documents' => $user->recreate_documents,
                'permission_force_send_by_summary' => $user->permission_force_send_by_summary,
            ],
            'payments' => [
                'create_payment' => $user->create_payment,
                'delete_payment' => $user->delete_payment,
            ],
            'purchases' => [
                'edit_purchase' => $user->edit_purchase,
                'annular_purchase' => $user->annular_purchase,
                'delete_purchase' => $user->delete_purchase,
            ],
            'modules' => $user->getWebPermissionsByUser(),
        ];
    }

}
