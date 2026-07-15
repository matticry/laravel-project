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
        if ($this->profileService->existsUserByDni($request->us_dni)) {
            return back()->withErrors(['us_dni' => 'El usuario con este DNI ya existe.'])->withInput();
        }
        if ($this->profileService->existsUserByEmail($request->us_email)) {
            return back()->withErrors(['us_email' => 'El usuario con este correo ya está registrado.'])->withInput();
        }
        try{

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
// ProfileController.php
    public function getConsultas($id)
    {
        $user = Profile::with([
            'consultas.historialClinico',
        ])->findOrFail($id);

        $consultas = $user->consultas
            ->sortByDesc('fecha_registro')
            ->values()
            ->map(function ($consulta) {
                return [
                    'id_co'           => $consulta->id_co,
                    'motivo'          => $consulta->motivo,
                    'fecha_registro'  => $consulta->fecha_registro?->format('d/m/Y H:i'),
                    'historial'       => $consulta->historialClinico ? [
                        'id_historial_clinico'                => $consulta->historialClinico->id_historial_clinico,
                        'motivo_consulta'                     => $consulta->historialClinico->motivo_consulta,
                        'antecedentes_patologicos_familiares' => $consulta->historialClinico->antecedentes_patologicos_familiares,
                        'antecedentes_patologicos_personales' => $consulta->historialClinico->antecedentes_patologicos_personales,
                        'antecedentes_oculares_familiares'    => $consulta->historialClinico->antecedentes_oculares_familiares,
                        'antecedentes_oculares_personales'    => $consulta->historialClinico->antecedentes_oculares_personales,
                        'utiliza_lentes'                      => $consulta->historialClinico->utiliza_lentes,
                        'tipo_lente'                          => $consulta->historialClinico->tipo_lente,
                        'fecha_inicio_uso_lentes'             => $consulta->historialClinico->fecha_inicio_uso_lentes?->format('Y-m-d'),
                        'observaciones'                       => $consulta->historialClinico->observaciones,
                    ] : null,
                ];
            })
            ->unique('id_co')
            ->values();

        return response()->json([
            'consultas' => $consultas,
        ]);
    }
    public function edit($id)
    {
        $roles = Role::all();
        $user = $this->profileService->getUserById($id);
        return view('profile.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {

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
