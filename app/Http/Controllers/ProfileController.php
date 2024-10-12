<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Role;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        $roles = Role::all();
        $users = $this->profileService->getAllUsers();
        return view('profile.index', compact('users', 'roles'));
    }


    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'us_dni' => 'required|string|max:10',
                'us_name' => 'required|string|max:255',
                'us_lastName' => 'required|string|max:255',
                'us_email' => 'required|email|unique:tbl_user,us_email',
                'us_password' => 'required|string|min:6',
                'roles' => 'array'
            ]);

            $user = $this->profileService->createUser($validatedData);

            if(!$user)
            {
                return redirect()->back()->with('error', 'Error al crear el usuario');
            }

            return redirect()->route('profile.index')->with('success', 'Usuario creado correctamente');
        } catch (ValidationException  $e) {
            $errors = $e->validator->errors()->toArray();

            $errorMessage = "Valida bien estos datos:\n";

            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }

    }

    public function edit($id)
    {
        $roles = Role::all();
        $user = $this->profileService->getUserById($id);
        return view('profile.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'us_dni' => 'required|string|max:10',
                'us_name' => 'required|string|max:255',
                'us_lastName' => 'required|string|max:255',
                'us_status' => 'required|in:A,I',
                'roles' => 'array'
            ]);
            $user = $this->profileService->updateUser($id, $validatedData);
            if (!$user) {
                return redirect()->back()->with('error', 'Error al actualizar el usuario');
            }
            return redirect()->route('profile.index')->with('success', 'Usuario actualizado correctamente');
        } catch (ValidationException  $e) {
            $errors = $e->validator->errors()->toArray();
            $errorMessage = "Valida bien estos datos:\n";
            foreach ($errors as $key => $value) {
                $errorMessage .= $key . ": " . implode(", ", $value) . "\n";
            }
            return back()->withErrors('error' . $errorMessage)->withInput();
        }

    }

    public function destroy($id)
    {
        $user = $this->profileService->deleteUser($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Error al eliminar el usuario');
        }
        return redirect()->route('profile.index')->with('success', 'Usuario eliminado correctamente');
    }



}
