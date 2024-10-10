<?php

namespace App\Services\Interfaces;

interface RoleServiceInterface
{

    public function getAllRoles();
    public function getRoleById($id);
    public function createRole(array $roleData);
    public function updateRole($id, array $roleData);
    public function deleteRole($id);
    public function assignPermissions($roleId, array $permissionIds);

}
