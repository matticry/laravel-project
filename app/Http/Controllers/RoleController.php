<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Services\Interfaces\RoleServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $permissions = Permission::all();
        $roles = $this->roleService->getAllRoles();

        if ($request->has('rol_name')) {
            if ($request->has('rol_name') && $request->rol_name) {
                $roles = $roles->filter(function ($role) use ($request) {
                    return str_contains($role->rol_name, $request->rol_name);
                });
            }
        }
        return view('roles.index', compact('roles','permissions'));

    }

    public function store(Request $request)
    {
        if ($this->roleService->existsRoleByName($request->rol_name))
        {
            return back()->withErrors(['rol_name' => 'El rol con este nombre ya existe.'])->withInput();

        }
        try {
            $validatedData = $request->validate([
                'rol_name' => 'required|string|max:255|unique:tbl_role,rol_name',
                'rol_status' => 'sometimes|in:A,I',
                'permissions' => 'array'
            ]);

            $role = $this->roleService->createRole($validatedData);

            if (!$role)
            {
                return redirect()->back()->with('error', 'Error al crear el usuario');

            }

            return redirect()->route('roles.index')->with('success', 'Rol creado correctamente');
        }catch (ValidationException $e){

            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }

    }

    public function edit($roleID)
    {
        $permissions = Permission::all();
        $role = $this->roleService->getRoleById($roleID);
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'rol_name' => 'sometimes|string|max:255|unique:tbl_role,rol_name,'.$id.',rol_id',
                'rol_status' => 'sometimes|in:A,I',
                'permissions' => 'required|array|min:1'
            ]);

            $roleUpdated = $this->roleService->updateRole($id, $validatedData);

            if (!$roleUpdated) {
                return redirect()->back()->with('error', 'Error al actualizar el rol');
            }

            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');

        }catch (ValidationException $e){
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Introduce bien estos datos:\n";
            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }
    }

    public function destroy($id)
    {
        $role = $this->roleService->deleteRole($id);
        if (!$role) return redirect()->back()->with('error', 'Error al eliminar el rol');
        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente');
    }

}
