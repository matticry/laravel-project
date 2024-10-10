<?php

namespace App\Services;

use App\Models\Role;
use App\Services\Interfaces\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{

    /**
     * @return mixed
     */
    public function getAllRoles()
    {
        return Role::with('permissions')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRoleById($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * @param array $roleData
     * @return mixed
     */
    public function createRole(array $roleData)
    {
        $role = Role::create($roleData);
        if (isset($roleData['permissions'])) {
            $this->assignPermissions($role->rol_id, $roleData['permissions']);
        }
        return $role;
    }

    /**
     * @param $id
     * @param array $roleData
     * @return mixed
     */
    public function updateRole($id, array $roleData)
    {
        $role = Role::findOrFail($id);
        $role->update($roleData);
        if (isset($roleData['permissions'])) {
            $this->assignPermissions($role->rol_id, $roleData['permissions']);
        }
        return $role;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        return $role->delete();
    }

    public function assignPermissions($roleId, array $permissionIds)
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->sync($permissionIds);
    }

    public function existsRoleByName(string $name): bool
    {
        return Role::where('rol_name', $name)->exists();


    }
}
