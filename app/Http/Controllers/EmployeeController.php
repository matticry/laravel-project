<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use App\Services\Interfaces\EmployeeServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $provincias = Provincia::all();
        $employees = $this->employeeService->getAllEmployees();

        // Verificar si el formulario ha sido enviado con filtros
        if ($request->has('code_emplo') || $request->has('name_emplo')) {
            // Si hay un filtro por código, aplicarlo
            if ($request->has('code_emplo') && $request->code_emplo) {
                $employees = $employees->filter(function ($employee) use ($request) {
                    return str_contains($employee->code_emplo, $request->code_emplo);
                });
            }

            // Si hay un filtro por nombre, aplicarlo
            if ($request->has('name_emplo') && $request->name_emplo) {
                $employees = $employees->filter(function ($employee) use ($request) {
                    return str_contains($employee->name_emplo, $request->name_emplo);
                });
            }
        }

        return view('employees.index', compact('employees' , 'provincias'));
    }


    public function store(Request $request)
    {
        if ($this->employeeService->existsEmployee($request->dni_emplo)) {
            return back()->withErrors(['dni_emplo' => 'El usuario con este DNI ya existe.'])->withInput();
        }

        if ($this->employeeService->existsEmployeeByEmail($request->email_emplo)) {
            return back()->withErrors(['email_emplo' => 'El usuario con este correo ya existe.'])->withInput();
        }

        try {
            $validatedData = $request->validate([
                'name_emplo' => 'required|max:255',
                'last_name_emplo' => 'required|max:255',
                'dni_emplo' => 'required|max:10',
                'id_provincia' => 'required|exists:provincia,id_provincia',
                'birthdate_emplo' => 'required|date',
                'email_emplo' => 'required|email|max:255',
                'description_emplo' => 'nullable|max:255',
                'image_emplo' => 'nullable|image|max:2048',
                'status_emplo' => 'required|in:V,R',
            ]);

            if ($request->hasFile('image_emplo')) {
                $validatedData['image_emplo'] = $request->file('image_emplo')->store('profile_images', 'public');
            }

            $employee = $this->employeeService->createEmployee($validatedData);

            if (!$employee) {
                return back()->with('error', 'Error al registrar el usuario por parte del servidor')->withInput();
            }

            return redirect()->route('employees.index')->with('success', 'Usuario registrado exitosamente');
        }catch (Exception $e) {
            Log::error('Error al registrar el usuario: '. $e->getMessage());
            return back()->withErrors('error', 'Error al registrar el usuario por parte del servidor')->withInput();
        }

    }


    public function edit($id)
    {
        $employee = $this->employeeService->getEmployeeById($id);
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name_emplo' => 'required|max:255',
                'last_name_emplo' => 'required|max:255',
                'dni_emplo' => 'required|max:10',
                'id_provincia' => 'required|exists:provincias,id',
                'birthdate_emplo' => 'required|date',
                'email_emplo' => 'required|email|max:255',
                'description_emplo' => 'nullable|max:255',
                'image_emplo' => 'nullable|max:255',
                'status_emplo' => 'required|in:R,A,I',
            ]);

            if ($request->hasFile('image_emplo')) {
                $validatedData['image_emplo'] = $request->file('image_emplo')->store('profile_images', 'public');
            }

            $employee = $this->employeeService->updateEmployee($id, $validatedData);

            if (!$employee) {
                return back()->with('error', 'Error al actualizar el usuario por parte del servidor')->withInput();
            }

            return redirect()->route('employees.index')->with('success', 'Usuario actualizado exitosamente');
        }catch (Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Error al actualizar el usuario por parte del servidor')->withInput();
        }
    }

    public function destroy($id)
    {
        $this->employeeService->deleteEmployee($id);
        return redirect()->route('employees.index')->with('success', 'Empleado eliminado con éxito.');
    }



}
